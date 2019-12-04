<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class ComboController extends Controller
{
    public function __construct()
    {
        
	}

    public function selectProduct()
    {
        $categories = Category::where('type', 1)->get();

        return view('goods::combo.select_product', compact('categories'));
    }

    public function productPaginate(Request $request)
    {
        $query = Product::query();
        if ($request->get('excepted_ids')) {
            $query = $query->whereNotIn('id', $request->get('excepted_ids'));
        }
        if ($request->get('category_id')) {
            $query = $query->where('category_id', $request->get('category_id'));
        }
        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->skus;
        }

        return response()->json($paginate);
    }
}
