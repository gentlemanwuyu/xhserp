<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        try {
            $data = [
                'code' => $request->get('code', ''),
                'method' => $request->get('method', 0),
                'reason' => $request->get('reason', ''),
            ];
            if ($request->get('order_id')) {
                $data['order_id'] = $request->get('order_id');
            }

            $return_order = ReturnOrder::find($request->get('return_order_id'));

            DB::beginTransaction();
            if (!$return_order) {
                $data['user_id'] = Auth::user()->id;
                $data['status'] = 1;
                $return_order = ReturnOrder::create($data);
            }else {
                if (!in_array($return_order->status, [1, 2])) {
                    throw new \Exception("非待审核或驳回状态的退货单不可编辑");
                }
                $return_order->update($data);
            }

            if (!$return_order) {
                throw new \Exception("退货单保存失败");
            }

            // 同步item
            $return_order->syncItems($request->get('items'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
