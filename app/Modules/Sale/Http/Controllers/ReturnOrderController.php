<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\ReturnOrder;

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

        $data = compact('order');
        if ($request->get('return_order_id')) {
            $return_order = ReturnOrder::find($request->get('return_order_id'));
            $data['return_order'] = $return_order;
        }else {
            $data['auto_code'] = ReturnOrder::codeGenerator();
        }

        return view('sale::returnOrder.form', $data);
    }

    public function save(Request $request)
    {

    }
}
