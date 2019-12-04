<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Category\Models\Category;

class ComboController extends Controller
{
    public function __construct()
    {
        
	}

    public function selectProduct()
    {
        $categories = Category::where('type', 1)->get();

        return view('goods::goods.combo_select_product', compact('categories'));
    }
}
