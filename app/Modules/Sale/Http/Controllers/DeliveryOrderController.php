<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Sale\Models\Order;
use App\Modules\Warehouse\Models\Express;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\DeliveryOrder;

class DeliveryOrderController extends Controller
{
    public function __construct()
    {

	}

    public function form(Request $request)
    {
        $orders = Order::where('customer_id', $request->get('customer_id'))->where('status', 3)->get()->pluck(null, 'id')->map(function ($o) {
            $o->pis = $o->pendingItems->pluck(null, 'id');

            return $o;
        });
        $expresses = Express::all(['id', 'name']);
        $customer = Customer::find($request->get('customer_id'));

        return view('sale::deliveryOrder.form', compact('orders', 'expresses', 'customer'));
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'code' => $request->get('code', ''),
                'delivery_method' => $request->get('delivery_method', 0),
                'address' => $request->get('address', ''),
                'consignee' => $request->get('consignee', ''),
                'consignee_phone' => $request->get('consignee_phone', ''),
                'note' => $request->get('note', ''),
            ];
            if (3 == $data['delivery_method']) {
                $data['express_id'] = $request->get('express_id', 0);
                $data['is_collected'] = $request->get('is_collected', 0);
                $data['collected_amount'] = $request->get('collected_amount', 0.00);
            }
            if ($request->get('customer_id')) {
                $data['customer_id'] = $request->get('customer_id', 0);
            }

            $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));

            DB::beginTransaction();
            if (!$delivery_order) {
                $data['user_id'] = Auth::user()->id;
                $delivery_order = DeliveryOrder::create($data);
            }else {
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
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
