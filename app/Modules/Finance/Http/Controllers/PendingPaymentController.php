<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Finance\Models\Payment;

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
        $query = SkuEntry::leftJoin('purchase_order_items AS poi', 'poi.id', '=', 'sku_entries.order_item_id')
            ->leftJoin('purchase_orders AS po', 'po.id', '=', 'poi.order_id')
            ->leftJoin('suppliers AS s', 's.id', '=', 'po.supplier_id');
        $query = $query->where('sku_entries.is_paid', 0);
        if ($request->get('code')) {
            $query = $query->where('s.code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('s.name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('payment_method')) {
            $query = $query->where('s.payment_method', $request->get('payment_method'));
        }
        $supplier_ids = $query->get(['po.supplier_id']);
        $supplier_ids = array_unique(array_column($supplier_ids->toArray(), 'supplier_id'));

        $query = Supplier::whereIn('id', $supplier_ids);

        $paginate = $query->paginate($request->get('limit'));

        foreach ($paginate as $customer) {
            $customer->setAppends(['unpaid_items', 'payment_method_name', 'total_remained_amount']);
        }

        return response()->json($paginate);
    }

    public function deduction(Request $request)
    {
        $payments = Payment::where('supplier_id', $request->get('supplier_id'))
            ->where('is_finished', 0)
            ->orderBy('id', 'desc')
            ->get();
        $supplier = Supplier::find($request->get('supplier_id'));
        $supplier->setAppends(['total_remained_amount', 'unpaid_items']);

        return view('finance::pendingPayment.deduction', compact('payments', 'supplier'));
    }

    public function deduct(Request $request)
    {
        try {
            $supplier = Supplier::find($request->get('supplier_id'));
            if (!$supplier) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该供应商']);
            }

            DB::beginTransaction();
            $supplier->deduct($request->get('checked_entry_ids'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
