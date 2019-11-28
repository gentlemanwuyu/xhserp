<?php
namespace App\Modules\Product\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList()
    {
        return view('product::product.list');
    }
}
