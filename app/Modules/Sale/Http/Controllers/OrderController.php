<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Index\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Goods\Models\Goods;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {

	}

    public function index(Request $request)
    {
        $customers = Customer::all();
        $users = User::where('is_admin', 0)->get();

        return view('sale::order.index', compact('customers', 'users'));
    }

    public function form(Request $request)
    {
        $customers = Customer::all(['id', 'name', 'payment_method'])->pluck(null, 'id');
        $goods = Goods::all()->map(function ($g) {
            $g->skus;
            return $g;
        })->pluck(null, 'id');
        $data = compact('customers', 'goods');
        if ($request->get('order_id')) {
            $order = Order::find($request->get('order_id'));
            $data['order'] = $order;
        }

        return view('sale::order.form', $data);
    }

    public function paginate(Request $request)
    {
        $paginate = Order::orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $order) {
            $order->items->map(function ($item) {
                $item->goods;
                $item->sku;

                return $item;
            });
            $order->customer;
            $order->setAppends(['payment_method_name']);
        }

        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            $order_data = [
                'code' => $request->get('code', ''),
                'customer_id' => $request->get('customer_id'),
                'payment_method' => $request->get('payment_method'),
            ];

            DB::beginTransaction();

            $order = Order::updateOrCreate(['id' => $request->get('order_id')], $order_data);

            if (!$order) {
                throw new \Exception("订单保存失败");
            }

            // 同步item
            $order->syncItems($request->get('items'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $order = Order::find($request->get('order_id'));

            if (!$order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该订单']);
            }

            DB::beginTransaction();
            $order->delete();
            $order->items->each(function ($item) {
                $item->delete();
            });

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
