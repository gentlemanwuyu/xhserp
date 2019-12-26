<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\DeliveryOrder;

class PendingCollectionController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('finance::pendingCollection.index');
    }

    public function paginate(Request $request)
    {
        $customer_ids = DeliveryOrder::leftJoin('delivery_order_items AS doi', 'doi.delivery_order_id', '=', 'delivery_orders.id')
            ->leftJoin('customers AS c', 'c.id', '=', 'delivery_orders.customer_id')
            ->where('delivery_orders.status', 2)
            ->where('doi.is_paid', 0)
            ->get(['delivery_orders.customer_id']);
        $customer_ids = array_unique(array_column($customer_ids->toArray(), 'customer_id'));


        $query = Customer::whereIn('id', $customer_ids);

        $paginate = $query->paginate($request->get('limit'));

        foreach ($paginate as $customer) {
            $customer->setAppends(['unpaid_items', 'payment_method_name', 'total_remained_amount']);
        }

        return response()->json($paginate);
    }
}
