<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return view('purchase::order.index');
    }

    public function form()
    {
        $suppliers = Supplier::all(['id', 'name', 'payment_method'])->pluck(null, 'id');
        $products = Product::all()->map(function ($product) {
            $product->skus;
            return $product;
        })->pluck(null, 'id');

        return view('purchase::order.form', compact('suppliers', 'products'));
    }

    public function save(Request $request)
    {
        try {
            $order_data = [
                'code' => $request->get('code', ''),
                'supplier_id' => $request->get('supplier_id'),
                'payment_method' => $request->get('payment_method'),
            ];

            DB::beginTransaction();

            $order = PurchaseOrder::updateOrCreate(['id' => $request->get('order_id')], $order_data);

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
}
