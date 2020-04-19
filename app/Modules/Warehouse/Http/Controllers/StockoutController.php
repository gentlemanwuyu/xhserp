<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class StockoutController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        $categories = Category::tree(1);

        return view('warehouse::stockout.index', compact('categories'));
    }

    public function paginate(Request $request)
    {
        $query = Product::leftJoin('product_skus AS ps', 'ps.product_id', '=', 'products.id')
            ->leftjoin('inventories AS i', 'i.sku_id', '=', 'ps.id');

        $query = $query->whereRaw('i.stock < i.lowest_stock');
        if ($request->get('code')) {
            $query = $query->where('products.code', $request->get('code'));
        }
        if ($request->get('category_ids')) {
            $category_ids = explode(',', $request->get('category_ids'));
            foreach ($category_ids as $category_id) {
                $category = Category::find($category_id);
                $category_ids = array_merge($category_ids, $category->children_ids);
            }

            $query = $query->whereIn('products.category_id', array_unique($category_ids));
        }

        $paginate = $query->select('products.*')->groupBy('products.id')->paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->stockoutSkus;
        }

        return response()->json($paginate);
    }
}
