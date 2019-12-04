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
        return view('goods::single.select_product');
    }

    public function form(Request $request)
    {
        $product = Product::find($request->get('product_id'));
        $categories = Category::where('type', 2)->get();

        return view('goods::single.form', compact('product', 'categories'));
    }
}
