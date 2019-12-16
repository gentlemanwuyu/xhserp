<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Index\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $query = Order::query();
        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('customer_id')) {
            $query = $query->where('customer_id', $request->get('customer_id'));
        }
        if ($request->get('status')) {
            $query = $query->where('status', $request->get('status'));
        }
        if ($request->get('payment_method')) {
            $query = $query->where('payment_method', $request->get('payment_method'));
        }
        if ($request->get('creator_id')) {
            $query = $query->where('user_id', $request->get('creator_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $order) {
            $order->items->map(function ($item) {
                $item->goods;
                $item->sku;

                return $item;
            });
            $order->customer;
            $order->user;
            $order->setAppends(['payment_method_name', 'status_name']);
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

            $customer = Customer::find($request->get('customer_id'));
            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            // 分析订单需要进入什么状态
            if (1 == $request->get('payment_method')) {
                // 如果付款方式是现金，直接进入已通过状态
                $order_data['status'] = 3;
            }elseif (2 == $request->get('payment_method')) {
                if (2 == $customer->payment_method) {
                    // 判断是否超过额度
                    $orders = Order::where('customer_id', $request->get('customer_id'))->whereIn('status', [3, 4])->get();
                    $used_credit = 0; // 已使用额度
                    $orders->each(function ($order) use (&$used_credit) {
                        $order->items->each(function ($item) use (&$used_credit) {
                            $used_credit += $item->quantity * $item->price;
                        });
                    });
                    $current_amount = 0; // 当前订单得金额
                    foreach ($request->get('items') as $item) {
                        $current_amount += $item['quantity'] * $item['price'];
                    }
                    if ($customer->credit >= $used_credit + $current_amount) {
                        $order_data['status'] = 3;
                    }else{
                        $order_data['status'] = 1;
                    }
                }else {
                    $order_data['status'] = 1;
                }
            }elseif (3 == $request->get('payment_method')) {
                if (3 == $customer->payment_method) {
                    $order_data['status'] = 3;
                }else {
                    return response()->json(['status' => 'fail', 'msg' => '该客户不是月结客户']);
                }
            }else {
                return response()->json(['status' => 'fail', 'msg' => '未知的付款方式']);
            }

            if (3 == $order_data['status']) {
                $order_data['payment_status'] = 1;
            }

            $order = Order::find($request->get('order_id'));

            DB::beginTransaction();
            if (!$order) {
                $order_data['user_id'] = Auth::user()->id;
                $order = Order::create($order_data);
            }else {
                $order->update($order_data);
            }

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
