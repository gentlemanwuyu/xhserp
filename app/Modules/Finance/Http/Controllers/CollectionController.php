<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\DeliveryOrderItem;
use App\Modules\Finance\Models\Account;
use App\Modules\Finance\Models\Collection;
use App\Modules\Finance\Models\CollectionItem;

class CollectionController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('finance::collection.index');
    }

    public function form(Request $request)
    {
        $users = User::where('is_admin', 0)->get();
        $customer = Customer::find($request->get('customer_id'));
        $accounts = Account::all();

        return view('finance::collection.form', compact('users', 'customer', 'accounts'));
    }

    public function save(Request $request)
    {
        try {
            $customer = Customer::find($request->get('customer_id'));
            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            $collection_data = [
                'customer_id' => $request->get('customer_id'),
                'amount' => $request->get('amount'),
                'method' => $request->get('method'),
                'collect_user_id' => $request->get('collect_user_id', 0),
                'account_id' => $request->get('account_id', 0),
                'user_id' => Auth::user()->id,
            ];


            DB::beginTransaction();
            $collection = Collection::create($collection_data);
            $used_amount = 0;
            if ($request->get('checked_doi_ids')) {
                foreach ($request->get('checked_doi_ids') as $doi_id) {
                    $delivery_order_item = DeliveryOrderItem::find($doi_id);
                    $order_item = $delivery_order_item->orderItem;
                    $amount = $order_item->price * $delivery_order_item->quantity;
                    CollectionItem::create([
                        'collection_id' => $collection->id,
                        'doi_id' => $doi_id,
                        'is_full' => 1,
                        'amount' => $amount,
                    ]);
                    $used_amount += $amount;
                    $delivery_order_item->is_paid = 1;
                    $delivery_order_item->save();
                }
            }
            if ($used_amount > $collection->amount) {
                throw new \Exception("选中的明细金额不可大于收款金额");
            }
            $remained_amount = $collection->amount - $used_amount;
            $collection->remained_amount = $remained_amount;
            if (0 == $remained_amount) {
                $collection->is_finished = 1;
            }
            $collection->save();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
