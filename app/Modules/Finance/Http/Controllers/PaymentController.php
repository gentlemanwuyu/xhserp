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
use App\Modules\Finance\Models\PaymentItem;
use App\Modules\Warehouse\Models\SkuEntry;

class PaymentController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('finance::payment.index');
    }

    public function form(Request $request)
    {
        $users = User::where('is_admin', 0)->get();
        $supplier = Supplier::find($request->get('supplier_id'));
        $accounts = Account::all();

        return view('finance::payment.form', compact('users', 'supplier', 'accounts'));
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
            // 未抵扣的付款单
            $remained_payments = Payment::where('is_finished', 0)->orderBy('created_at', 'asc')->get();

            if ($request->get('checked_entry_ids')) {
                foreach ($request->get('checked_entry_ids') as $entry_id) {
                    $entry = SkuEntry::find($entry_id);
                    $order_item = $entry->orderItem;
                    // 需要抵扣的金额
                    $amount = $order_item->price * $entry->quantity;
                    $remained_payment = $remained_payments->first();
                    while ($remained_payment) {
                        if ($amount <= $remained_payment->remained_amount) {
                            // 剩余未抵扣的金额
                            $remained_amount = $remained_payment->remained_amount;
                            PaymentItem::create([
                                'payment_id' => $remained_payment->id,
                                'entry_id' => $entry_id,
                                'amount' => $amount,
                            ]);
                            $remained_amount -= $amount;
                            $remained_payment->remained_amount = $remained_amount;
                            if (0 == $remained_amount) {
                                $remained_payment->is_finished = 1;
                            }

                            $remained_payment->save();
                            $amount = 0; // 已经完全抵扣，需要抵扣的金额置0
                        }else {
                            PaymentItem::create([
                                'payment_id' => $remained_payment->id,
                                'entry_id' => $entry_id,
                                'amount' => $remained_payment->remained_amount,
                            ]);
                            $amount -= $remained_payment->remained_amount;
                            // 该付款单已经抵扣完
                            $remained_payment->remained_amount = 0;
                            $remained_payment->is_finished = 1;
                            $remained_payment->save();
                            // 将该付款单弹出
                            $remained_payments->shift();
                            $remained_payment = $remained_payments->first();
                        }
                        if (0 == $amount) {
                            break;
                        }
                    }

                    if ($amount > 0) {
                        throw new \Exception("选中的明细金额不可大于付款金额");
                    }
                    $entry->is_paid = 1;
                    $entry->save();
                }
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
