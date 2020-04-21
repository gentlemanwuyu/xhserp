<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Index\Models\User;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Purchase\Models\PurchaseReturnOrder;

class PurchaseReturnController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $suppliers = Supplier::all();
        $users = User::where('is_admin', 0)->get();

        return view('warehouse::purchaseReturn.index', compact('suppliers', 'users'));
    }

    public function egress(Request $request)
    {
        try {
            $purchase_return_order = PurchaseReturnOrder::find($request->get('purchase_return_order_id'));

            if (empty($purchase_return_order)) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该采购退货单']);
            }

            if (1 != $purchase_return_order->status) {
                return response()->json(['status' => 'fail', 'msg' => '非已通过状态的采购退货单不可处理']);
            }

            DB::beginTransaction();
            // 更新退货单状态
            $purchase_return_order->status = 2;
            if (3 == $purchase_return_order->delivery_method) {
                $purchase_return_order->track_no = $request->get('track_no', '');
            }
            $purchase_return_order->save();

            if (1 == $purchase_return_order->method) {
                // 如果退货方式为换货，更新订单的exchange_status字段
                $purchase_order = $purchase_return_order->purchaseOrder;
                $purchase_order->exchange_status = 1;
                $purchase_order->save();
            }

            // 更新库存
            foreach ($purchase_return_order->items as $purchase_return_order_item) {
                $product_sku = $purchase_return_order_item->purchaseOrderItem->sku;
                $inventory = $product_sku->inventory;
                if ($inventory->stock < $purchase_return_order_item->egress_quantity) {
                    throw new \Exception("SKU[{$product_sku->code}]的库存数量[$inventory->stock]小于退货单的实出数量[{$purchase_return_order_item->egress_quantity}]");
                }
                $inventory->stock -= $purchase_return_order_item->egress_quantity;
                $inventory->save();
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
