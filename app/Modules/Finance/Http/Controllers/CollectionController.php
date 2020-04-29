<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Http\Requests\CollectionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Finance\Models\Account;
use App\Modules\Finance\Models\Collection;
use App\Services\WorldService;

class CollectionController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $customers = Customer::all();
        $users = User::where('is_admin', NO)->get();

        return view('finance::collection.index', compact('customers', 'users'));
    }

    public function paginate(Request $request)
    {
        $query = Collection::query();
        if ($request->get('customer_id')) {
            $query = $query->where('customer_id', $request->get('customer_id'));
        }
        if ($request->get('creator_id')) {
            $query = $query->where('user_id', $request->get('creator_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $collection) {
            $collection->deductions->map(function ($deduction) {
                $deduction->deliveryOrderItem->orderItem->order->currency;


                return $deduction;
            });
            $collection->customer;
            $collection->user;
            $collection->currency;
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $users = User::where('is_admin', 0)->get();
        $customers = Customer::all()->map(function ($customer) {
            $customer->unpaidItems;
            $customer->backOrders->map(function ($return_order) {
                $return_order->user;
                $return_order->items->map(function ($return_order_item) {
                    $return_order_item->orderItem;

                    return $return_order_item;
                });
                $return_order->setAppends(['undeducted_amount']);

                return $return_order;
            });
            $customer->setAppends(['total_remained_amount', 'back_amount']);

            return $customer;
        })->pluck(null, 'id');
        $accounts = Account::all();
        $currencies = WorldService::currencies();

        return view('finance::collection.form', compact('users', 'customers', 'accounts', 'currencies'));
    }

    public function save(CollectionRequest $request)
    {
        try {
            $customer = Customer::find($request->get('customer_id'));
            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            $collection_data = [
                'customer_id' => $request->get('customer_id'),
                'currency_code' => $request->get('currency_code'),
                'amount' => $request->get('amount'),
                'method' => $request->get('method'),
                'collect_user_id' => $request->get('collect_user_id', 0),
                'account_id' => $request->get('account_id', 0),
                'user_id' => Auth::user()->id,
                'remained_amount' => $request->get('amount'),
            ];

            DB::beginTransaction();
            Collection::create($collection_data);
            $customer->deduct($request->get('checked_doi_ids'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
