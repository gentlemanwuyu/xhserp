<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $sku_ids = PurchaseOrder::leftJoin('purchase_order_items AS poi', 'poi.order_id', '=', 'purchase_orders.id')
            ->where('purchase_orders.status', 3)
            ->where('poi.delivery_status', 1)
            ->pluck('poi.sku_id')->toArray();

        $paginate = ProductSku::whereIn('id', $sku_ids)->paginate($request->get('limit'));

        foreach ($paginate as $sku) {
            $sku->product->category;
            $sku->inventory;
            $sku->setAppends(['purchase_order_items']);
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $sku = ProductSku::find($request->get('sku_id'));
        $sku->pois = $sku->purchase_order_items
            ->map(function ($item) {
                $item->order->supplier;

                return $item;
            })
            ->pluck(null, 'id');

        return view('warehouse::entry.form', compact('sku'));
    }

    public function save(Request $request)
    {
        try {
            $order_item = PurchaseOrderItem::where('id', $request->get('order_item_id'))->where('delivery_status', 1)->first();
            if (!$order_item) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该订单']);
            }

            DB::beginTransaction();
            SkuEntry::create([
                'sku_id' => $order_item->sku_id,
                'order_item_id' => $request->get('order_item_id'),
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

            // 检查是否已经全部入库
            $entries = SkuEntry::where('order_item_id', $request->get('order_item_id'))->get(['quantity']);
            $entried_quantity = array_sum(array_column($entries->toArray(), 'quantity'));
            if ($entried_quantity >= $order_item->quantity) {
                $order_item->delivery_status = 2;
                $order_item->save();
            }
            // 检查订单是否已完成
            $unfinished_order_items = PurchaseOrderItem::where('order_id', $order_item->order_id)->where('delivery_status', '!=', 2)->get(['id', 'order_id', 'delivery_status']);
            if ($unfinished_order_items->isEmpty()) {
                PurchaseOrder::where('id', $order_item->order_id)->update(['status' => 4]);
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
