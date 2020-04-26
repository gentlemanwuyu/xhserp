<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Sale\Models\Customer;
use App\Modules\Finance\Models\Collection;
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
        $query = $query->where('delivery_orders.status', DeliveryOrder::FINISHED)
            ->where('doi.is_paid', NO)
            ->where('doi.real_quantity', '>', 0);

        if ($request->get('code')) {
            $query = $query->where('c.code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('c.name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('payment_method')) {
            $query = $query->where('c.payment_method', $request->get('payment_method'));
        }
        $customer_ids = $query->pluck('delivery_orders.customer_id');
        $customer_ids = array_unique($customer_ids->toArray());

        $query = Customer::whereIn('id', $customer_ids);

        $paginate = $query->paginate($request->get('limit'));

        foreach ($paginate as $customer) {
            $customer->unpaidItems;
            $customer->setAppends(['payment_method_name', 'total_remained_amount', 'back_amount']);
        }

        return response()->json($paginate);
    }

    public function deduction(Request $request)
    {
        $collections = Collection::where('customer_id', $request->get('customer_id'))
            ->where('is_finished', NO)
            ->orderBy('id', 'desc')
            ->get();
        $customer = Customer::find($request->get('customer_id'));
        $customer->unpaidItems;
        $customer->setAppends(['total_remained_amount', 'back_amount']);

        return view('finance::pendingCollection.deduction', compact('collections', 'customer'));
    }

    public function deduct(Request $request)
    {
        try {
            $customer = Customer::find($request->get('customer_id'));
            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            DB::beginTransaction();
            $customer->deduct($request->get('checked_doi_ids'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
