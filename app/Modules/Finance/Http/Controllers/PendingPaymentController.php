<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Purchase\Models\Supplier;

class PendingPaymentController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('finance::pendingPayment.index');
    }

    public function paginate(Request $request)
    {
        $supplier_ids = SkuEntry::leftJoin('purchase_order_items AS poi', 'poi.id', '=', 'sku_entries.order_item_id')
            ->leftJoin('purchase_orders AS po', 'po.id', '=', 'poi.order_id')
            ->where('sku_entries.is_paid', 0)
            ->get(['po.supplier_id']);
        $supplier_ids = array_unique(array_column($supplier_ids->toArray(), 'supplier_id'));

        $query = Supplier::whereIn('id', $supplier_ids);

        $paginate = $query->paginate($request->get('limit'));

        foreach ($paginate as $customer) {
            $customer->setAppends(['unpaid_items', 'payment_method_name', 'total_remained_amount']);
        }

        return response()->json($paginate);
    }
}
