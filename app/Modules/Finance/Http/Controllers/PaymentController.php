<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Finance\Models\Account;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Finance\Models\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        $suppliers = Supplier::all();
        $users = User::where('is_admin', 0)->get();

        return view('finance::payment.index', compact('suppliers', 'users'));
    }

    public function paginate(Request $request)
    {
        $query = Payment::query();
        if ($request->get('supplier_id')) {
            $query = $query->where('supplier_id', $request->get('supplier_id'));
        }
        if ($request->get('creator_id')) {
            $query = $query->where('user_id', $request->get('creator_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $payment) {
            $payment->deductions->map(function ($item) {
                $item->skuEntry->purchaseOrderItem->purchaseOrder;

                return $item;
            });
            $payment->supplier;
            $payment->user;
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $users = User::where('is_admin', 0)->get();
        $suppliers = Supplier::all()
            ->map(function ($supplier) {
                $supplier->unpaidItems;
                $supplier->backOrders->map(function ($purchase_return_order) {
                    $purchase_return_order->user;
                    $purchase_return_order->items->map(function ($purchase_return_order_item) {
                        $purchase_return_order_item->purchaseOrderItem;

                        return $purchase_return_order_item;
                    });
                    $purchase_return_order->setAppends(['undeducted_amount']);

                    return $purchase_return_order;
                });
                $supplier->setAppends(['total_remained_amount', 'back_amount']);

                return $supplier;
            })
            ->pluck(null, 'id');
        $accounts = Account::all();

        return view('finance::payment.form', compact('users', 'suppliers', 'accounts'));
    }

    public function save(Request $request)
    {
        try {
            $supplier = Supplier::find($request->get('supplier_id'));
            if (!$supplier) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该供应商']);
            }

            $payment_data = [
                'supplier_id' => $request->get('supplier_id'),
                'amount' => $request->get('amount'),
                'method' => $request->get('method'),
                'pay_user_id' => $request->get('pay_user_id', 0),
                'account_id' => $request->get('account_id', 0),
                'user_id' => Auth::user()->id,
                'remained_amount' => $request->get('amount'),
            ];

            DB::beginTransaction();
            Payment::create($payment_data);
            $supplier->deduct($request->get('checked_entry_ids'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
