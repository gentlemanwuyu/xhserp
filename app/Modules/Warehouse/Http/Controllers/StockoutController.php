<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Models\Product;

class StockoutController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('warehouse::stockout.index');
    }

    public function paginate(Request $request)
    {
        $paginate = Product::leftJoin('product_skus AS ps', 'ps.product_id', '=', 'products.id')
            ->leftjoin('inventories AS i', 'i.sku_id', '=', 'ps.id')
            ->select('products.*')
            ->whereRaw('i.stock < i.lowest_stock')
            ->groupBy('products.id')
            ->paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->stockoutSkus;
        }

        return response()->json($paginate);
    }
}
