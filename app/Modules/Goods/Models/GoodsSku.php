<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 11:03
 */

namespace App\Modules\Goods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Warehouse\Models\Inventory;
use App\Modules\Sale\Models\OrderItem;

class GoodsSku extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getProductSkuId($product_id)
    {
        return ComboSkuProductSku::where('goods_sku_id', $this->id)->where('product_id', $product_id)->value('product_sku_id');
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function getStockAttribute()
    {
        $goods = $this->goods;

        if (1 == $goods->type) {
            $product_sku_id = SingleSkuProductSku::where('goods_sku_id', $this->id)->value('product_sku_id');

            return Inventory::where('sku_id', $product_sku_id)->value('stock');
        }elseif (2 == $goods->type) {
            $products = array_column(ComboProduct::where('goods_id', $goods->id)->get()->toArray(), 'quantity', 'product_id');
            $product_sku_ids = array_column(ComboSkuProductSku::where('goods_sku_id', $this->id)->get()->toArray(), 'product_sku_id', 'product_id');
            foreach ($product_sku_ids as $product_id => $product_sku_id) {
                $stock = Inventory::where('sku_id', $product_sku_id)->value('stock');
                if (!$stock) {
                    $stock = 0;
                }

                $valid_stock = floor($stock/$products[$product_id]);

                if (!isset($min_stock) || $valid_stock < $min_stock) {
                    $min_stock = $valid_stock;
                }
            }

            return $min_stock;
        }else {
            return null;
        }
    }

    /**
     * 需求数量
     *
     * @return int
     */
    public function getRequiredQuantityAttribute()
    {
        $items = OrderItem::leftJoin('orders AS o', 'o.id', '=', 'order_items.order_id')
            ->where('o.status', 3)
            ->where('order_items.sku_id', $this->id)
            ->select(['order_items.*'])
            ->get();

        $quantity = 0;
        if (!$items->isEmpty()) {
            foreach ($items as $item) {
                $delivery_quantity = array_sum(array_column($item->deliveryItems->toArray(), 'quantity'));

                $quantity += $item->quantity - $delivery_quantity;
            }
        }

        return $quantity;
    }

    /**
     * 增加库存
     *
     * @param $quantity
     * @return $this
     * @throws \Exception
     */
    public function increase($quantity)
    {
        $goods = $this->goods;

        if (1 == $goods->type) {
            $product_sku_id = SingleSkuProductSku::where('goods_sku_id', $this->id)->value('product_sku_id');
            $inventory = Inventory::where('sku_id', $product_sku_id)->first();
            if (!$inventory) {
                throw new \Exception("没有找到产品{$product_sku_id}的库存信息");
            }

            $inventory->stock += $quantity;
            $inventory->save();
        }elseif (2 == $goods->type) {
            $product_sku_ids = array_column(ComboSkuProductSku::where('goods_sku_id', $this->id)->get(['product_id', 'product_sku_id'])->toArray(), 'product_id', 'product_sku_id');

            foreach ($product_sku_ids as $product_sku_id => $product_id) {
                $inventory = Inventory::where('sku_id', $product_sku_id)->first();
                if (!$inventory) {
                    throw new \Exception("没有找到产品{$product_sku_id}的库存信息");
                }

                $combo_product = ComboProduct::where('goods_id', $goods->id)->where('product_id', $product_id)->first();
                if (!$combo_product) {
                    throw new \Exception("商品{$goods->id}对应产品{$product_id}的记录");
                }

                $inventory->stock += $quantity * $combo_product->quantity;
                $inventory->save();
            }
        }

        return $this;
    }

    /**
     * 减少库存
     *
     * @param $quantity
     * @return $this
     */
    public function reduce($quantity)
    {
        $goods = $this->goods;

        if (1 == $goods->type) {
            $product_sku_id = SingleSkuProductSku::where('goods_sku_id', $this->id)->value('product_sku_id');
            $inventory = Inventory::where('sku_id', $product_sku_id)->first();
            if (!$inventory) {
                throw new \Exception("没有找到产品{$product_sku_id}的库存信息");
            }
            if ($inventory->stock < $quantity) {
                throw new \Exception("产品{$product_sku_id}库存不足");
            }
            $inventory->stock -= $quantity;
            $inventory->save();
        }elseif (2 == $goods->type) {
            $product_sku_ids = array_column(ComboSkuProductSku::where('goods_sku_id', $this->id)->get(['product_id', 'product_sku_id'])->toArray(), 'product_id', 'product_sku_id');

            foreach ($product_sku_ids as $product_sku_id => $product_id) {
                $inventory = Inventory::where('sku_id', $product_sku_id)->first();
                if (!$inventory) {
                    throw new \Exception("没有找到产品{$product_sku_id}的库存信息");
                }

                $combo_product = ComboProduct::where('goods_id', $goods->id)->where('product_id', $product_id)->first();
                if (!$combo_product) {
                    throw new \Exception("商品{$goods->id}对应产品{$product_id}的记录");
                }
                $product_sku_quantity = $quantity * $combo_product->quantity;

                if ($inventory->stock < $product_sku_quantity) {
                    throw new \Exception("产品{$product_sku_id}库存不足");
                }
                $inventory->stock -= $product_sku_quantity;
                $inventory->save();
            }
        }

        return $this;
    }
}