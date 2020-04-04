<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnOrderController extends Controller
{
    public function __construct()
    {
        
	}

    public function form(Request $request)
    {
        $data = [];

        return view('purchase::returnOrder.form', $data);
    }
}
