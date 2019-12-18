<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Sale\Models\Customer;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\DeliveryOrder;

class EgressController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $customers = Customer::all();
        $users = User::where('is_admin', 0)->get();

        return view('warehouse::egress.index', compact('customers', 'users'));
    }

    public function finish(Request $request)
    {
        try {
            $delivery_order = DeliveryOrder::find($request->get('delivery_order_id'));

            if (!$delivery_order) {
                return response()->json(['status' => 'fail', 'msg' => '找不到该出货单']);
            }

            DB::beginTransaction();
            $delivery_order->status = 2;
            $delivery_order->save();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
