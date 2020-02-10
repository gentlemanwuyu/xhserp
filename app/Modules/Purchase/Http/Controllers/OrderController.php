<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Product\Models\Product;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Purchase\Models\PurchaseOrder;

class OrderController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $suppliers = Supplier::all(['id', 'name']);
        $users = User::where('is_admin', 0)->get();

        return view('purchase::order.index', compact('suppliers', 'users'));
    }

    public function form(Request $request)
    {
        $suppliers = Supplier::all(['id', 'name', 'payment_method', 'tax'])->pluck(null, 'id');
        $products = Product::all()->map(function ($product) {
            $product->skus;
            return $product;
        })->pluck(null, 'id');
        $data = compact('suppliers', 'products');
        if ($request->get('order_id')) {
            $order = PurchaseOrder::find($request->get('order_id'));
            $data['order'] = $order;
        }else {
            $data['auto_code'] = PurchaseOrder::codeGenerator();
        }

        return view('purchase::order.form', $data);
    }

    public function paginate(Request $request)
    {
        $query = PurchaseOrder::query();
        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('supplier_id')) {
            $query = $query->where('supplier_id', $request->get('supplier_id'));
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
                $item->product;
                $item->sku;
                $item->setAppends(['entried_quantity']);

                return $item;
            });
            $order->supplier;
            $order->user;
            $order->setAppends(['payment_method_name', 'status_name', 'tax_name']);
        }

        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            $order_data = [
                'code' => $request->get('code', ''),
                'supplier_id' => $request->get('supplier_id'),
                'payment_method' => $request->get('payment_method'),
                'tax' => $request->get('tax'),
                'status' => 1,
            ];

            $order = PurchaseOrder::find($request->get('order_id'));

            DB::beginTransaction();
            if (!$order) {
                $order_data['user_id'] = Auth::user()->id;
                $order = PurchaseOrder::create($order_data);
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
            $order = PurchaseOrder::find($request->get('order_id'));

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

    public function detail(Request $request)
    {
        $order = PurchaseOrder::find($request->get('order_id'));

        return view('purchase::order.detail', compact('order'));
    }

    public function review(Request $request)
    {
        $order = PurchaseOrder::find($request->get('order_id'));

        return view('purchase::order.detail', compact('order'));
    }

    public function agree(Request $request)
    {
        try {
            $order = PurchaseOrder::find($request->get('order_id'));

            if (!$order) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该订单']);
            }
            if (1 != $order->status) {
                return response()->json(['status' => 'fail', 'msg' => '该订单不是待审核状态，禁止操作']);
            }

            DB::beginTransaction();
            $order->update(['status' => 3]);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }

    public function reject(Request $request)
    {
        try {
            $order = PurchaseOrder::find($request->get('order_id'));

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
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $order = PurchaseOrder::find($request->get('order_id'));

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
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
