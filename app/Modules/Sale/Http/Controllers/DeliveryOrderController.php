<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryOrderController extends Controller
{
    public function __construct()
    {

	}

    public function form()
    {
        return view('sale::deliveryOrder.form');
    }
}
