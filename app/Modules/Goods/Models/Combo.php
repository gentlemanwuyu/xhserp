<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 20:42
 */

namespace App\Modules\Goods\Models;

use Illuminate\Support\Facades\DB;
use App\Modules\Product\Models\Product;
use App\Modules\Sale\Models\OrderItem;
use App\Modules\Sale\Models\ReturnOrderItem;

class Combo extends Goods
{
    public function syncSkus($skus)
    {
        if (!$skus || !is_array($skus)) {
            return false;
        }

        $indexes = array_keys($skus);

        // 将不在请求中的sku删除
        foreach ($this->skus as $ori_goods_sku) {
            if (!in_array($ori_goods_sku->id, $indexes)) {
                $ori_goods_sku->delete();
            }
        }

        foreach ($skus as $flag => $item) {
            // 如果有订单存在，不可编辑，以免出货的时候零件不对
            $order_exists = OrderItem::leftJoin('orders AS o', 'o.id', '=', 'order_items.order_id')
                ->whereNull('o.deleted_at')
                ->whereIn('o.status', [1, 2, 3])
                ->where('order_items.sku_id', $flag)
                ->exists();

            if ($order_exists) {
                throw new \Exception("SKU[{$item['code']}]有进行中的订单，不可编辑。");
            }

            // 如果有换货的退货单，不可编辑，以免入库或出货的时候，零件不对
            $return_order_exists = ReturnOrderItem::leftJoin('return_orders AS ro', 'ro.id', '=', 'return_order_items.return_order_id')
                ->leftJoin('order_items AS oi', 'oi.id', '=', 'return_order_items.order_item_id')
                ->whereNull('ro.deleted_at')
                ->whereNull('oi.deleted_at')
                ->where('oi.sku_id', $flag)
                ->where(function ($query) {
                    $query->where(function ($q1) {
                        $q1->where('ro.method', 1)->whereIn('ro.status', [1, 2, 3, 4]);
                    })->orWhere(function ($q2) {
                        $q2->where('ro.method', 2)->whereIn('ro.status', [1, 2, 3]);
                    });
                })
                ->exists();
            if ($return_order_exists) {
                throw new \Exception("SKU[{$item['code']}]有进行中的退货单，不可编辑。");
            }

            $goods_sku = GoodsSku::updateOrCreate(['id' => $flag], [
                'goods_id' => $this->id,
                'code' => $item['code'],
                'lowest_price' => $item['lowest_price'],
                'msrp' => $item['msrp'],
            ]);

            foreach ($item['parts'] as $product_id => $product_sku_id) {
                DB::table('combo_sku_product_skus')->updateOrInsert(['goods_sku_id' => $goods_sku->id, 'product_id' => $product_id], [
                    'goods_sku_id' => $goods_sku->id,
                    'product_id' => $product_id,
                    'product_sku_id' => $product_sku_id,
                ]);
            }
        }

        return $this;
    }

    public function getProductIdsAttribute()
    {
        return array_column(ComboProduct::where('goods_id', $this->id)->get(['product_id', 'quantity'])->toArray(), 'quantity', 'product_id');
    }

    public function getProductsAttribute()
    {
        $product_ids = $this->product_ids;
        return Product::whereIn('id', array_keys($product_ids))->get()->map(function ($product) use ($product_ids) {
            $product->quantity = isset($product_ids[$product->id]) ? $product_ids[$product->id] : 0;

            return $product;
        });
    }
}