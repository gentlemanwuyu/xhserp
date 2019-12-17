<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Models\Order;
use App\Modules\Warehouse\Models\Express;
use App\Modules\Sale\Models\Customer;

class DeliveryOrderController extends Controller
{
    public function __construct()
    {

	}

    public function form(Request $request)
    {
        $orders = Order::where('customer_id', $request->get('customer_id'))->where('status', 3)->get()->pluck(null, 'id')->map(function ($o) {
            $o->pis = $o->pendingItems->pluck(null, 'id');

            return $o;
        });
        $expresses = Express::all(['id', 'name']);
        $customer = Customer::find($request->get('customer_id'));

        return view('sale::deliveryOrder.form', compact('orders', 'expresses', 'customer'));
    }
}
