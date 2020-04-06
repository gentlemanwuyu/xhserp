<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Warehouse\Models\Express;
use App\Modules\Purchase\Models\PurchaseOrder;
use App\Modules\Purchase\Models\PurchaseReturnOrder;

class ReturnOrderController extends Controller
{
    public function __construct()
    {
        
	}

    public function form(Request $request)
    {
        $expresses = Express::all();
        $data = compact('expresses');
        if ($request->get('purchase_return_order_id')) {
            $purchase_return_order = PurchaseReturnOrder::find($request->get('purchase_return_order_id'));
            $data['purchase_return_order'] = $purchase_return_order;
            $purchase_order = $purchase_return_order->purchaseOrder;
        }else {
            $data['auto_code'] = PurchaseReturnOrder::codeGenerator();
            $purchase_order = PurchaseOrder::find($request->get('purchase_order_id'));
        }

        $supplier = $purchase_order->supplier;
        $supplier->contacts;
        $supplier->setAppends(['full_address']);
        $data['supplier'] = $supplier;

        // 可退Item
        $purchase_order->returnable_items = $purchase_order->items->filter(function ($item) {
            return $item->returnable_quantity;
        })->map(function ($item) {
            $item->setAppends(['returnable_quantity', 'entried_quantity']);
            return $item;
        })->pluck(null, 'id');

        $data['purchase_order'] = $purchase_order;

        return view('purchase::returnOrder.form', $data);
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'code' => $request->get('code', ''),
                'method' => $request->get('method', 0),
                'reason' => $request->get('reason', ''),
                'delivery_method' => $request->get('delivery_method', 0),
                'express_id' => $request->get('express_id', 0),
                'address' => $request->get('address', ''),
                'consignee' => $request->get('consignee', ''),
                'consignee_phone' => $request->get('consignee_phone', ''),
                'status' => 1,
            ];
            if ($request->get('purchase_order_id')) {
                $data['purchase_order_id'] = $request->get('purchase_order_id');
            }

            $purchase_return_order = PurchaseReturnOrder::find($request->get('purchase_return_order_id'));

            DB::beginTransaction();
            if (!$purchase_return_order) {
                $data['user_id'] = Auth::user()->id;
                $purchase_return_order = PurchaseReturnOrder::create($data);
            }else {
                $purchase_return_order->update($data);
            }

            if (!$purchase_return_order) {
                throw new \Exception("退货单保存失败");
            }

            // 同步item
            $purchase_return_order->syncItems($request->get('items'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
