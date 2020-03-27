<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\ReturnOrder;
use App\Modules\Sale\Models\ReturnOrderLog;
use App\Modules\Sale\Models\ReturnOrderItem;

class SaleReturnController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $customers = Customer::all();
        $users = User::where('is_admin', 0)->get();

        return view('warehouse::saleReturn.index', compact('customers', 'users'));
    }

    public function form(Request $request)
    {
        $return_order = ReturnOrder::find($request->get('return_order_id'));

        $return_order->indexItems = $return_order->items
            ->map(function ($item) {

                return $item;
            })
            ->pluck(null, 'id');

        return view('warehouse::saleReturn.form', compact('return_order'));
    }

    public function save(Request $request)
    {
        try {
            $return_order = ReturnOrder::find($request->get('return_order_id'));

            if (empty($return_order)) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该退货单']);
            }

            if (3 != $return_order->status) {
                return response()->json(['status' => 'fail', 'msg' => '非已通过状态的退货单不可处理']);
            }

            DB::beginTransaction();
            // 更新退货单状态
            $return_order->status = 4;
            $return_order->save();
            // 同步item入库数量
            $return_order->syncItems($request->get('items'));

            if (1 == $return_order->method) {
                // 如果退货方式为换货，更新订单的exchange_status字段
                $order = $return_order->order;
                $order->exchange_status = 1;
                $order->save();
            }elseif (2 == $return_order->method) {
                // 如果退货方式为退货
                $return_order->backDeduction();
            }

            // 更新库存
            foreach ($request->get('items') as $roii => $item) {
                $return_order_item = ReturnOrderItem::find($roii);
                $goods_sku = $return_order_item->orderItem->sku;
                $goods_sku->increase($item['entry_quantity']);
            }
            // 记录操作日志
            ReturnOrderLog::create([
                'return_order_id' => $request->get('return_order_id'),
                'action' => 3,
                'content' => $request->get('handle_suggestion', ''),
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
