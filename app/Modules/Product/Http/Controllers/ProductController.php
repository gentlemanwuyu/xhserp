<?php
namespace App\Modules\Product\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Category\Models\Category;
use App\Modules\Product\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList()
    {
        return view('product::product.list');
    }

    public function form(Request $request)
    {
        $categories = Category::where('type', 1)->get();

        return view('product::product.form', compact('categories'));
    }

    public function paginate(Request $request)
    {


        $paginate = Product::paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->skus;
        }


        return response()->json($paginate);
    }
}
