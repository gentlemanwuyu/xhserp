<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Http\Requests\ReturnOrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\ReturnOrder;
use App\Modules\Sale\Models\ReturnOrderLog;

class ReturnOrderController extends Controller
{
    public function __construct()
    {

	}

    public function index(Request $request)
    {
        $customers = Customer::all();
        $users = User::where('is_admin', NO)->get();

        return view('sale::returnOrder.index', compact('customers', 'users'));
    }

    public function paginate(Request $request)
    {
        $query = ReturnOrder::leftJoin('orders AS o', 'o.id', '=', 'return_orders.order_id');
        if ($request->get('code')) {
            $query = $query->where('return_orders.code', $request->get('code'));
        }
        if ($request->get('customer_id')) {
            $query = $query->where('o.customer_id', $request->get('customer_id'));
        }
        if ($request->get('status')) {
            $query = $query->where('return_orders.status', $request->get('status'));
        }
        if ($request->get('creator_id')) {
            $query = $query->where('return_orders.user_id', $request->get('creator_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('return_orders.created_at', '>=', $created_at_between[0] . ' 00:00:00')
                ->where('return_orders.created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('return_orders.id', 'desc')
            ->select(['return_orders.*'])
            ->paginate($request->get('limit'));

        foreach ($paginate as $ro) {
            $ro->items->map(function ($item) {
                $item->orderItem->setAppends(['deliveried_quantity']);

                return $item;
            });
            $ro->order->customer;
            $ro->user;
            $ro->setAppends(['status_name', 'method_name']);
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->get('return_order_id')) {
            $return_order = ReturnOrder::find($request->get('return_order_id'));
            $data['return_order'] = $return_order;
            $order = $return_order->order;
        }else {
            $data['auto_code'] = ReturnOrder::codeGenerator();
            $order = Order::find($request->get('order_id'));
        }

        // 可退Item
        $order->returnable_items = $order->items->filter(function ($item) {
            return $item->returnable_quantity;
        })->map(function ($item) {
            $item->goods;
            $item->sku;
            $item->setAppends(['deliveried_quantity', 'returnable_quantity']);
            return $item;
        })->pluck(null, 'id');

        $data['order'] = $order;

        return view('sale::returnOrder.form', $data);
    }

    public function save(ReturnOrderRequest $request)
    {
        try {
            $data = [
                'code' => $request->get('code', ''),
                'method' => $request->get('method', 0),
                'reason' => $request->get('reason', ''),
            ];
            if ($request->get('order_id')) {
                $data['order_id'] = $request->get('order_id');
            }

            $user = Auth::user();
            $data['status'] = $user->hasPermissionTo('review_return_order') ? ReturnOrder::AGREED : ReturnOrder::PENDING_REVIEW;

            $return_order = ReturnOrder::find($request->get('return_order_id'));

            DB::beginTransaction();
            if (!$return_order) {
                $data['user_id'] = Auth::user()->id;
                $return_order = ReturnOrder::create($data);
            }else {
                if (!in_array($return_order->status, [ReturnOrder::PENDING_REVIEW, ReturnOrder::REJECTED])) {
                    throw new \Exception("非待审核或驳回状态的退货单不可编辑");
                }
                $return_order->update($data);
            }

            if (!$return_order) {
                throw new \Exception("退货单保存失败");
            }

            // 同步item
            $return_order->syncItems($request->get('items'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function detail(Request $request)
    {
        $return_order = ReturnOrder::find($request->get('return_order_id'));

        return view('sale::returnOrder.detail', compact('return_order'));
    }

    public function agree(Request $request)
    {
        try {
            $return_order = ReturnOrder::find($request->get('return_order_id'));

            if (!$return_order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该退货单']);
            }
            if (ReturnOrder::PENDING_REVIEW != $return_order->status) {
                return response()->json(['status' => 'fail', 'msg' => '该退货单不是待审核状态，禁止操作']);
            }

            DB::beginTransaction();
            $return_order->update(['status' => ReturnOrder::AGREED]);
            ReturnOrderLog::create([
                'return_order_id' => $return_order->id,
                'action' => ReturnOrderLog::AGREE,
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
            $return_order = ReturnOrder::find($request->get('return_order_id'));

            if (!$return_order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该退货单']);
            }
            if (ReturnOrder::PENDING_REVIEW != $return_order->status) {
                return response()->json(['status' => 'fail', 'msg' => '该退货单不是待审核状态，禁止操作']);
            }

            DB::beginTransaction();
            $return_order->update(['status' => ReturnOrder::REJECTED]);
            ReturnOrderLog::create([
                'return_order_id' => $return_order->id,
                'action' => ReturnOrderLog::REJECT,
                'content' => $request->get('reason'),
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $return_order = ReturnOrder::find($request->get('return_order_id'));

            if (!$return_order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该退货单']);
            }

            DB::beginTransaction();
            $return_order->delete();
            $return_order->items->each(function ($item) {
                $item->delete();
            });

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
