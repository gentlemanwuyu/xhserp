<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Warehouse\Models\Express;
use App\Modules\Purchase\Models\PurchaseOrder;
use App\Modules\Purchase\Models\PurchaseReturnOrder;

class ReturnOrderController extends Controller
{
    public function __construct()
    {
        
	}

    public function index(Request $request)
    {
        $suppliers = Supplier::all();
        $users = User::where('is_admin', 0)->get();

        return view('purchase::returnOrder.index', compact('suppliers', 'users'));
    }

    public function paginate(Request $request)
    {
        $query = PurchaseReturnOrder::leftJoin('purchase_orders AS po', 'po.id', '=', 'purchase_return_orders.purchase_order_id');
        if ($request->get('code')) {
            $query = $query->where('purchase_return_orders.code', $request->get('code'));
        }
        if ($request->get('supplier_id')) {
            $query = $query->where('po.supplier_id', $request->get('supplier_id'));
        }
        if ($request->get('status')) {
            $query = $query->where('purchase_return_orders.status', $request->get('status'));
        }
        if ($request->get('creator_id')) {
            $query = $query->where('purchase_return_orders.user_id', $request->get('creator_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('purchase_return_orders.created_at', '>=', $created_at_between[0] . ' 00:00:00')
                ->where('purchase_return_orders.created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('purchase_return_orders.id', 'desc')
            ->select(['purchase_return_orders.*'])
            ->paginate($request->get('limit'));

        foreach ($paginate as $pro) {
            $pro->items->map(function ($item) {
                $item->purchaseOrderItem->setAppends(['entried_quantity']);

                return $item;
            });
            $pro->purchaseOrder->supplier;
            $pro->user;
            $pro->setAppends(['status_name', 'method_name', 'delivery_method_name']);
        }

        return response()->json($paginate);
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
            $item->sku;
            $item->product;
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
                'track_no' => $request->get('track_no', ''),
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

    public function detail(Request $request)
    {
        $purchase_return_order = PurchaseReturnOrder::find($request->get('purchase_return_order_id'));

        return view('purchase::returnOrder.detail', compact('purchase_return_order'));
    }

    public function delete(Request $request)
    {
        try {
            $purchase_return_order = PurchaseReturnOrder::find($request->get('purchase_return_order_id'));

            if (!$purchase_return_order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该采购退货单']);
            }

            DB::beginTransaction();
            $purchase_return_order->items->each(function ($item) {
                $item->delete();
            });
            $purchase_return_order->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
