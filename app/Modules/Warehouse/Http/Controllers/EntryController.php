<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Models\ProductSku;
use App\Modules\Purchase\Models\PurchaseOrder;

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
            $sku->purchase_orders;
            $sku->setAppends(['purchase_orders']);
        }

        return response()->json($paginate);
    }

    public function form()
    {
        return view('warehouse::entry.form');
    }
}
