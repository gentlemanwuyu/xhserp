<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Http\Requests\PaymentMethodApplicationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\PaymentMethodApplication;
use App\Modules\Sale\Models\PaymentMethodApplicationLog;

class PaymentMethodController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $customers = Customer::all();
        $users = User::where('is_admin', NO)->get();

        return view('sale::paymentMethod.index', compact('customers', 'users'));
    }

    public function paginate(Request $request)
    {
        $query = PaymentMethodApplication::query();

        if ($request->get('customer_id')) {
            $query = $query->where('customer_id', $request->get('customer_id'));
        }
        if ($request->get('status')) {
            $query = $query->where('status', $request->get('status'));
        }
        if ($request->get('user_id')) {
            $query = $query->where('user_id', $request->get('user_id'));
        }

        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $application) {
            $application->customer;
            $application->user;
            $application->setAppends(['status_name', 'payment_method_name']);
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $application = PaymentMethodApplication::find($request->get('application_id'));

        return view('sale::paymentMethod.form', compact('application'));
    }

    public function save(PaymentMethodApplicationRequest $request)
    {
        try {
            $application = PaymentMethodApplication::find($request->get('application_id'));

            $data = [
                'payment_method' => $request->get('payment_method', 0),
                'credit' => $request->get('credit', 0),
                'monthly_day' => $request->get('monthly_day', 0),
                'reason' => $request->get('reason', ''),
            ];

            // 所有编辑过的付款申请都进入待审核状态
            $data['status'] = PaymentMethodApplication::PENDING_REVIEW;

            $application->update($data);

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function detail(Request $request)
    {
        $application = PaymentMethodApplication::find($request->get('application_id'));

        return view('sale::paymentMethod.detail', compact('application'));
    }

    public function review(Request $request)
    {
        $application = PaymentMethodApplication::find($request->get('application_id'));

        return view('sale::paymentMethod.detail', compact('application'));
    }

    public function agree(Request $request)
    {
        try {
            $application = PaymentMethodApplication::find($request->get('application_id'));

            if (!$application) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该付款方式申请']);
            }
            if (PaymentMethodApplication::PENDING_REVIEW != $application->status) {
                return response()->json(['status' => 'fail', 'msg' => '该付款方式申请不是待审核状态，禁止操作']);
            }

            $customer = $application->customer;
            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '找不到该付款方式申请对应的客户']);
            }

            DB::beginTransaction();
            $application->update(['status' => PaymentMethodApplication::AGREED]);
            // 将付款申请的数据写入客户表
            $customer->update([
                'payment_method' => $application->payment_method,
                'credit' => $application->credit,
                'monthly_day' => $application->monthly_day,
            ]);
            PaymentMethodApplicationLog::create([
                'payment_method_application_id' => $application->id,
                'customer_id' => $customer->id,
                'message' => '同意',
                'content' => '',
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function reject(Request $request)
    {
        try {
            $application = PaymentMethodApplication::find($request->get('application_id'));

            if (!$application) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该付款方式申请']);
            }
            if (PaymentMethodApplication::PENDING_REVIEW != $application->status) {
                return response()->json(['status' => 'fail', 'msg' => '该付款方式申请不是待审核状态，禁止操作']);
            }

            $customer = $application->customer;
            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '找不到该付款方式申请对应的客户']);
            }

            $application->update(['status' => PaymentMethodApplication::REJECTED]);
            PaymentMethodApplicationLog::create([
                'payment_method_application_id' => $application->id,
                'customer_id' => $customer->id,
                'message' => '驳回',
                'content' => $request->get('reject_reason'),
                'user_id' => Auth::user()->id,
            ]);

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $application = PaymentMethodApplication::find($request->get('application_id'));

            if (!$application) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到付款方式申请']);
            }

            $application->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
