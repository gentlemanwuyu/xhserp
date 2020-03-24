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
        $customers = Customer::all()->pluck(null, 'id')->map(function ($customer) {
            $customer->setAppends(['remained_credit']);

            return $customer;
        });
        $goods = Goods::all()->map(function ($g) {
            $g->skus->map(function ($sku) {
                $sku->setAppends(['required_quantity', 'stock']);

                return $sku;
            });
            return $g;
        })->pluck(null, 'id');
        $data = compact('customers', 'goods');
        if ($request->get('order_id')) {
            $order = Order::find($request->get('order_id'));
            $data['order'] = $order;
        }else {
            $data['auto_code'] = Order::codeGenerator();
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
                $item->sku->setAppends(['stock']);
                $item->setAppends(['pending_delivery_quantity']);

                return $item;
            });
            $order->customer;
            $order->user;
            $order->setAppends(['payment_method_name', 'status_name', 'tax_name', 'returnable', 'deliverable']);
        }

        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            $order_data = [
                'code' => $request->get('code', ''),
                'payment_method' => $request->get('payment_method'),
                'tax' => $request->get('tax'),
            ];

            $order = Order::find($request->get('order_id'));
            if ($request->get('customer_id')) {
                $order_data['customer_id'] = $request->get('customer_id');
                $customer = Customer::find($request->get('customer_id'));
            }elseif ($order) {
                $customer = $order->customer;
            }

            if (empty($customer)) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            // 判断订单号是否存在
            $query = Order::where('code', $request->get('code'));
            if ($request->get('order_id')) {
                $query = $query->where('id', '!=', $request->get('order_id'));
            }
            if ($query->first()) {
                return response()->json(['status' => 'fail', 'msg' => '订单号[' . $request->get('code') . ']已存在']);
            }

            // 分析订单需要进入什么状态
            if (1 == $request->get('payment_method')) {
                // 如果付款方式是现金，直接进入已通过状态
                $order_data['status'] = 3;
            }elseif (2 == $request->get('payment_method')) {
                if (2 == $customer->payment_method) {
                    // 判断是否超过额度
                    $current_amount = 0; // 当前订单得金额
                    foreach ($request->get('items') as $item) {
                        $current_amount += $item['quantity'] * $item['price'];
                    }
                    if ($customer->remained_credit >= $current_amount) {
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
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
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
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function detail(Request $request)
    {
        $order = Order::find($request->get('order_id'));

        return view('sale::order.detail', compact('order'));
    }

    public function review(Request $request)
    {
        $order = Order::find($request->get('order_id'));

        return view('sale::order.detail', compact('order'));
    }

    public function agree(Request $request)
    {
        try {
            $order = Order::find($request->get('order_id'));

            if (!$order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该订单']);
            }
            if (1 != $order->status) {
                return response()->json(['status' => 'fail', 'msg' => '该订单不是待审核状态，禁止操作']);
            }

            DB::beginTransaction();
            $order->update(['status' => 3, 'payment_status' => 1]);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function reject(Request $request)
    {
        try {
            $order = Order::find($request->get('order_id'));

            if (!$order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该订单']);
            }
            if (1 != $order->status) {
                return response()->json(['status' => 'fail', 'msg' => '该订单不是待审核状态，禁止操作']);
            }

            DB::beginTransaction();
            $order->update(['status' => 2]);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $order = Order::find($request->get('order_id'));

            if (!$order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该订单']);
            }
            if (3 != $order->status) {
                return response()->json(['status' => 'fail', 'msg' => '该订单不是已通过状态，禁止操作']);
            }

            DB::beginTransaction();
            $order->update(['status' => 5]);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
