<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\EgressFinished;
use App\Modules\Sale\Models\Customer;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\DeliveryOrder;
use App\Modules\Goods\Models\ComboProduct;
use App\Modules\Goods\Models\SingleSkuProductSku;
use App\Modules\Goods\Models\ComboSkuProductSku;
use App\Modules\Warehouse\Models\Inventory;

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

            // 减少对应的产品数量
            $delivery_order->items->each(function ($item) {
                $order_item = $item->orderItem;
                $goods = $order_item->goods;
                if (1 == $goods->type) {
                    $product_sku_id = SingleSkuProductSku::where('goods_sku_id', $order_item->sku_id)->value('product_sku_id');
                    $inventory = Inventory::where('sku_id', $product_sku_id)->first();
                    if (!$inventory) {
                        throw new \Exception("没有找到产品{$product_sku_id}的库存信息");
                    }
                    if ($inventory->stock < $item->quantity) {
                        throw new \Exception("产品{$product_sku_id}库存不足");
                    }
                    $inventory->stock -= $item->quantity;
                    $inventory->save();
                }elseif (2 == $goods->type) {
                    $product_sku_ids = array_column(ComboSkuProductSku::where('goods_sku_id', $order_item->sku_id)->get(['product_id', 'product_sku_id'])->toArray(), 'product_id', 'product_sku_id');

                    foreach ($product_sku_ids as $product_sku_id => $product_id) {
                        $inventory = Inventory::where('sku_id', $product_sku_id)->first();
                        if (!$inventory) {
                            throw new \Exception("没有找到产品{$product_sku_id}的库存信息");
                        }

                        $combo_product = ComboProduct::where('goods_id', $goods->id)->where('product_id', $product_id)->first();
                        if (!$combo_product) {
                            throw new \Exception("商品{$goods->id}对应产品{$product_id}的记录");
                        }
                        $product_sku_quantity = $item->quantity * $combo_product->quantity;

                        if ($inventory->stock < $product_sku_quantity) {
                            throw new \Exception("产品{$product_sku_id}库存不足");
                        }
                        $inventory->stock -= $product_sku_quantity;
                        $inventory->save();
                    }
                }
            });

            event(new EgressFinished($request->get('delivery_order_id')));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
