<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\Entried;
use App\Modules\Product\Models\ProductSku;
use App\Modules\Purchase\Models\PurchaseOrder;
use App\Modules\Purchase\Models\PurchaseOrderItem;
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Warehouse\Models\Inventory;

class EntryController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('warehouse::entry.index');
    }

    public function paginate(Request $request)
    {
        $sku_ids = [];
        $purchase_order_items = PurchaseOrderItem::leftJoin('purchase_orders AS po', 'po.id', '=', 'purchase_order_items.purchase_order_id')
            ->whereNull('po.deleted_at')
            ->where('po.status', 3)
            ->get();
        foreach ($purchase_order_items as $poi) {
            if (0 < $poi->pending_entry_quantity) {
                $sku_ids[] = $poi->sku_id;
            }
        }

        $paginate = ProductSku::whereIn('id', array_unique($sku_ids))->paginate($request->get('limit'));

        foreach ($paginate as $sku) {
            $sku->product->category;
            $sku->inventory;
            $sku->purchaseOrderItems->filter(function ($po_items) {
                return 0 < $po_items->pending_entry_quantity;
            })->map(function ($po_items) {
                $po_items->purchaseOrder->supplier;
                $po_items->setAppends(['entried_quantity', 'pending_entry_quantity']);

                return $po_items;
            });
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $sku = ProductSku::find($request->get('sku_id'));
        $sku->pois = $sku->purchaseOrderItems
            ->map(function ($item) {
                $item->purchaseOrder->supplier;
                $item->setAppends(['entried_quantity', 'pending_entry_quantity']);

                return $item;
            })
            ->pluck(null, 'id');

        return view('warehouse::entry.form', compact('sku'));
    }

    public function save(Request $request)
    {
        try {
            $order_item = PurchaseOrderItem::where('id', $request->get('order_item_id'))->first();
            if (!$order_item) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该订单']);
            }

            DB::beginTransaction();
            $entry = SkuEntry::create([
                'sku_id' => $order_item->sku_id,
                'purchase_order_item_id' => $request->get('order_item_id'),
                'quantity' => $request->get('quantity'),
                'user_id' => Auth::user()->id,
            ]);
            $inventory = Inventory::where('sku_id', $order_item->sku_id)->first();
            if (!$inventory) {
                Inventory::create([
                    'sku_id' => $order_item->sku_id,
                    'stock' => $request->get('quantity'),
                ]);
            }else {
                $inventory->stock += $request->get('quantity');
                $inventory->save();
            }

            event(new Entried($entry->id));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
