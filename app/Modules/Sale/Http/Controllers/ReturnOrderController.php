<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Models\Order;

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
        $order = Order::find($request->get('order_id'));
        $order->indexItems = $order->items
            ->map(function ($item) {
                $item->setAppends(['deliveried_quantity']);

                return $item;
            })
            ->pluck(null, 'id');

        return view('sale::returnOrder.form', compact('order'));
    }

    public function save(Request $request)
    {

    }
}
