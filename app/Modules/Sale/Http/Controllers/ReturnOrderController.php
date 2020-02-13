<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnOrderController extends Controller
{
    public function __construct()
    {

	}

    public function index(Request $request)
    {
        return view('sale::returnOrder.index');
    }

    public function form(Request $request)
    {
        return view('sale::returnOrder.form');
    }
}
