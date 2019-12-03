<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class GoodsController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList(Request $request)
    {
        return view('goods::goods.list');
    }

    public function selectProduct($type)
    {
        if (1 == $type) {
            return view('goods::goods.single_select_product');
        }elseif (2 == $type) {
            return view('goods::goods.combo_select_product');
        }


    }

    public function singleForm(Request $request)
    {
        $product = Product::find($request->get('product_id'));
        $categories = Category::where('type', 2)->get();

        return view('goods::goods.single_form', compact('product', 'categories'));
    }
}
