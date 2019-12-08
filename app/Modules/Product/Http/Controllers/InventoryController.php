<?php
namespace App\Modules\Product\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Models\Product;

class InventoryController extends Controller
{
    public function __construct()
    {

	}

    public function form(Request $request)
    {
        $product = Product::find($request->get('product_id'));

        return view('product::inventory.form', compact('product'));
    }
}
