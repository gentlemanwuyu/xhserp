<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Services\WorldService;

class CustomerController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $users = User::where('is_admin', 0)->get();

        return view('sale::customer.index', compact('users'));
    }

    public function form(Request $request)
    {
        $chinese_regions = WorldService::chineseTree();
        $data = compact('chinese_regions');
        if ($request->get('customer_id')) {
            $customer = Customer::find($request->get('customer_id'));
            $data['customer'] = $customer;
        }

        return view('sale::customer.form', $data);
    }

    public function paginate(Request $request)
    {
        $query = Customer::query();

        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('tax')) {
            $query = $query->where('tax', $request->get('tax'));
        }
        if ($request->get('payment_method')) {
            $query = $query->where('payment_method', $request->get('payment_method'));
        }

        if ('' != $request->get('manager_id')) {
            $query = $query->where('manager_id', $request->get('manager_id'));
        }

        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $customer) {
            $customer->contacts;
            $customer->manager;
            $customer->setAppends(['payment_method_name', 'tax_name']);
        }

        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            // code去重判断
            $query = Customer::where('code', $request->get('code'));
            if ($request->get('customer_id')) {
                $query = $query->where('id', '!=', $request->get('customer_id'));
            }
            $code_exists = $query->first();
            if ($code_exists) {
                return response()->json(['status' => 'fail', 'msg' => '客户编号[' . $request->get('code') . ']已存在']);
            }

            // name去重判断
            $query = Customer::where('name', $request->get('name'));
            if ($request->get('customer_id')) {
                $query = $query->where('id', '!=', $request->get('customer_id'));
            }
            $name_exists = $query->first();
            if ($name_exists) {
                return response()->json(['status' => 'fail', 'msg' => '客户名称[' . $request->get('name') . ']已存在']);
            }

            $customer_data = [
                'code' => $request->get('code', ''),
                'name' => $request->get('name', ''),
                'company' => $request->get('company', ''),
                'phone' => $request->get('phone', ''),
                'fax' => $request->get('fax', ''),
                'tax' => $request->get('tax', 0),
                'payment_method' => $request->get('payment_method', 0),
                'credit' => $request->get('credit', 0),
                'monthly_day' => $request->get('monthly_day', 0),
                'state_id' => $request->get('state_id', 0),
                'city_id' => $request->get('city_id', 0),
                'county_id' => $request->get('county_id', 0),
                'street_address' => $request->get('street_address', ''),
                'intro' => $request->get('intro', ''),
            ];

            $customer = Customer::find($request->get('customer_id'));

            DB::beginTransaction();

            if ($customer) {
                if ($request->get('in_pool')) {
                    $customer_data['manager_id'] = 0;
                }elseif (empty($customer->manager_id)) {
                    $customer_data['manager_id'] = Auth::user()->id;
                }
                $customer->update($customer_data);
            }else {
                $customer_data['manager_id'] = $request->get('in_pool') ? 0 : Auth::user()->id;
                $customer = Customer::create($customer_data);
            }

            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '客户保存失败']);
            }

            // 同步联系人
            $customer->syncContacts($request->get('contacts'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $customer = Customer::find($request->get('customer_id'));

            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            DB::beginTransaction();
            $customer->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }

    public function detail(Request $request)
    {
        $customer = Customer::find($request->get('customer_id'));

        return view('sale::customer.detail', compact('customer'));
    }
}
