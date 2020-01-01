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
        $query = DeliveryOrder::leftJoin('delivery_order_items AS doi', 'doi.delivery_order_id', '=', 'delivery_orders.id')
            ->leftJoin('customers AS c', 'c.id', '=', 'delivery_orders.customer_id');
        $query = $query->where('delivery_orders.status', 2);
        $query = $query->where('doi.is_paid', 0);
        if ($request->get('code')) {
            $query = $query->where('c.code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('c.name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('payment_method')) {
            $query = $query->where('c.payment_method', $request->get('payment_method'));
        }
        $customer_ids = $query->get(['delivery_orders.customer_id']);
        $customer_ids = array_unique(array_column($customer_ids->toArray(), 'customer_id'));


        $query = Customer::whereIn('id', $customer_ids);

        $paginate = $query->paginate($request->get('limit'));

        foreach ($paginate as $customer) {
            $customer->setAppends(['unpaid_items', 'payment_method_name', 'total_remained_amount']);
        }

        return response()->json($paginate);
    }
}
