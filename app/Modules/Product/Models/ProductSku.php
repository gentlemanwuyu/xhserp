<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/2
 * Time: 14:10
 */

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Goods\Models\SingleSkuProductSku;
use App\Modules\Goods\Models\GoodsSku;
use App\Modules\Warehouse\Models\Inventory;

class ProductSku extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'sku_id');
    }

    public function getSingleSkuAttribute()
    {
        $goods_sku_id = SingleSkuProductSku::where('product_sku_id', $this->id)->value('goods_sku_id');

        return $goods_sku_id ? GoodsSku::find($goods_sku_id) : null;
    }
}