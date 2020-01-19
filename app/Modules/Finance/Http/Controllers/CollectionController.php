<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\DeliveryOrderItem;
use App\Modules\Finance\Models\Account;
use App\Modules\Finance\Models\Collection;
use App\Modules\Finance\Models\CollectionItem;

class CollectionController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $customers = Customer::all();
        $users = User::where('is_admin', 0)->get();

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
            $collection->items->map(function ($item) {
                $deliveryOrderItem = $item->deliveryOrderItem;
                $deliveryOrderItem->order;
                $deliveryOrderItem->orderItem;

                return $item;
            });
            $collection->customer;
            $collection->user;
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $users = User::where('is_admin', 0)->get();
        $customers = Customer::all()->map(function ($customer) {
            $customer->setAppends(['total_remained_amount', 'unpaid_items']);

            return $customer;
        })->pluck(null, 'id');
        $accounts = Account::all();

        return view('finance::collection.form', compact('users', 'customers', 'accounts'));
    }

    public function save(Request $request)
    {
        try {
            $customer = Customer::find($request->get('customer_id'));
            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            $collection_data = [
                'customer_id' => $request->get('customer_id'),
                'amount' => $request->get('amount'),
                'method' => $request->get('method'),
                'collect_user_id' => $request->get('collect_user_id', 0),
                'account_id' => $request->get('account_id', 0),
                'user_id' => Auth::user()->id,
                'remained_amount' => $request->get('amount'),
            ];

            DB::beginTransaction();
            Collection::create($collection_data);
            // 未抵扣的付款单
            $remained_collections = Collection::where('customer_id', $request->get('customer_id'))->where('is_finished', 0)->orderBy('created_at', 'asc')->get();

            if ($request->get('checked_doi_ids')) {
                foreach ($request->get('checked_doi_ids') as $doi_id) {
                    $delivery_order_item = DeliveryOrderItem::find($doi_id);
                    $order_item = $delivery_order_item->orderItem;
                    // 需要抵扣的金额
                    $amount = $order_item->price * $delivery_order_item->quantity;
                    $remained_collection = $remained_collections->first();
                    while ($remained_collection) {
                        if ($amount <= $remained_collection->remained_amount) {
                            // 剩余未抵扣的金额
                            $remained_amount = $remained_collection->remained_amount;
                            CollectionItem::create([
                                'collection_id' => $remained_collection->id,
                                'doi_id' => $doi_id,
                                'amount' => $amount,
                            ]);
                            $remained_amount -= $amount;
                            $remained_collection->remained_amount = $remained_amount;
                            if (0 == $remained_amount) {
                                $remained_collection->is_finished = 1;
                            }

                            $remained_collection->save();
                            $amount = 0; // 已经完全抵扣，需要抵扣的金额置0
                        }else {
                            CollectionItem::create([
                                'collection_id' => $remained_collection->id,
                                'doi_id' => $doi_id,
                                'amount' => $remained_collection->remained_amount,
                            ]);
                            $amount -= $remained_collection->remained_amount;
                            // 该收款单已经抵扣完
                            $remained_collection->remained_amount = 0;
                            $remained_collection->is_finished = 1;
                            $remained_collection->save();
                            // 将该收款单弹出
                            $remained_collections->shift();
                            $remained_collection = $remained_collections->first();
                        }
                        if (0 == $amount) {
                            break;
                        }
                    }

                    if ($amount > 0) {
                        throw new \Exception("选中的明细金额不可大于收款金额");
                    }
                    $delivery_order_item->is_paid = 1;
                    $delivery_order_item->save();
                }
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
