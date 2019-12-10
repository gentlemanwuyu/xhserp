<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Product\Models\Product;

class OrderController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('purchase::order.index');
    }

    public function form()
    {
        $suppliers = Supplier::all(['id', 'name']);
        $products = Product::all()->map(function ($product) {
            $product->skus;
            return $product;
        })->pluck(null, 'id');

        return view('purchase::order.form', compact('suppliers', 'products'));
    }
}
