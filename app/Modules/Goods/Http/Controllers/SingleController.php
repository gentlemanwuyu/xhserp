<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class SingleController extends Controller
{
    public function __construct()
    {
        
	}

    public function selectProduct()
    {
        $categories = Category::where('type', 1)->get();

        return view('goods::single.select_product', compact('categories'));
    }

    public function productPaginate(Request $request)
    {
        $query = Product::leftJoin('single_products AS sp', 'sp.product_id', '=', 'products.id');

        $query = $query->whereNull('sp.goods_id');
        if ($request->get('category_id')) {
            $query = $query->where('products.category_id', $request->get('category_id'));
        }
        if ($request->get('code')) {
            $query = $query->where('products.code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('products.name', 'like', '%' . $request->get('name') . '%');
        }

        $paginate = $query->orderBy('products.id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->skus;
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $product = Product::find($request->get('product_id'));
        $categories = Category::where('type', 2)->get();

        return view('goods::single.form', compact('product', 'categories'));
    }
}
