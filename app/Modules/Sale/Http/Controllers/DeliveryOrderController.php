<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Http\Requests\TrackNoRequest;
use App\Modules\Sale\Http\Requests\DeliveryOrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Order;
use App\Modules\Warehouse\Models\Express;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\DeliveryOrder;

class DeliveryOrderController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $customers = Customer::all();
        $users = User::where('is_admin', NO)->get();

        return view('sale::deliveryOrder.index', compact('customers', 'users'));
    }

    public function form(Request $request)
    {
        $expresses = Express::all(['id', 'name']);
        $data = compact('expresses');
        if (!empty($request->get('customer_id'))) {
            $data['auto_code'] = DeliveryOrder::codeGenerator();
            $customer = Customer::find($request->get('customer_id'));
        }elseif (!empty($request->get('delivery_order_id'))) {
            $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));
            $data['delivery_order'] = $delivery_order;
            $customer = $delivery_order->customer;
        }

        $orders = Order::where('customer_id', $customer->id)
            ->where(function ($query) {
                $query->where('status', Order::AGREED)->orWhere('exchange_status', 1);
            })
            ->get()
            ->pluck(null, 'id')
            ->map(function ($o) {
                $pis = $o->items->map(function ($item) {
                    $item->setAppends(['pending_delivery_quantity']);
                    // 待出货数量 <= 0的item过滤掉
                    if (0 >= $item->pending_delivery_quantity) {
                        return null;
                    }

                    $item->sku->setAppends(['stock']);

                    return $item;
                })->filter()->pluck(null, 'id');

                if ($pis->isEmpty()) {
                    return null;
                }

                $o->pis = $pis;
                $o->currency;
                return $o;
            })->filter();
        $data['customer'] = $customer;
        $data['orders'] = $orders;

        return view('sale::deliveryOrder.form', $data);
    }

    public function paginate(Request $request)
    {
        $query = DeliveryOrder::query();
        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('customer_id')) {
            $query = $query->where('customer_id', $request->get('customer_id'));
        }
        if ($request->get('status')) {
            $query = $query->where('status', $request->get('status'));
        }
        if ($request->get('creator_id')) {
            $query = $query->where('user_id', $request->get('creator_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $order) {
            $order->items->map(function ($item) {
                $item->order;
                $item->orderItem->sku->setAppends(['stock']);

                return $item;
            });
            $order->customer;
            $order->user;
            $order->setAppends(['delivery_method_name', 'status_name']);
        }

        return response()->json($paginate);
    }

    public function save(DeliveryOrderRequest $request)
    {
        try {
            $data = [
                'code' => $request->get('code', ''),
                'delivery_method' => $request->get('delivery_method', 0),
                'express_id' => $request->get('express_id', 0),
                'is_collected' => $request->get('is_collected', 0),
                'collected_amount' => $request->get('collected_amount', 0.00),
                'address' => $request->get('address', ''),
                'consignee' => $request->get('consignee', ''),
                'consignee_phone' => $request->get('consignee_phone', ''),
                'track_no' => $request->get('track_no', ''),
                'note' => $request->get('note', ''),
            ];
            if ($request->get('customer_id')) {
                $data['customer_id'] = $request->get('customer_id');
            }

            $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));

            DB::beginTransaction();
            if (!$delivery_order) {
                $data['user_id'] = Auth::user()->id;
                $data['status'] = DeliveryOrder::PENDING_DELIVERY;
                $delivery_order = DeliveryOrder::create($data);
            }else {
                if (DeliveryOrder::PENDING_DELIVERY != $delivery_order->status) {
                    throw new \Exception("非待出货状态的出货单不可编辑");
                }
                $delivery_order->update($data);
            }

            if (!$delivery_order) {
                throw new \Exception("出货单保存失败");
            }

            // 同步item
            $delivery_order->syncItems($request->get('items'));

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
            $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));

            if (!$delivery_order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该出货单']);
            }

            DB::beginTransaction();
            $delivery_order->items->each(function ($item) {
                $item->delete();
            });
            $delivery_order->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function detail(Request $request)
    {
        $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));

        return view('sale::deliveryOrder.detail', compact('delivery_order'));
    }

    public function bill(Request $request)
    {
        $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));

        return view('sale::deliveryOrder.bill', compact('delivery_order'));
    }

    public function editTrackNo(TrackNoRequest $request)
    {
        try {
            $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));

            if (!$delivery_order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该出货单']);
            }
            if (DeliveryOrder::EXPRESS != $delivery_order->delivery_method) {
                return response()->json(['status' => 'fail', 'msg' => '该出货单的发货方式不是快递']);
            }

            DB::beginTransaction();
            $delivery_order->track_no = $request->get('track_no');
            $delivery_order->save();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
