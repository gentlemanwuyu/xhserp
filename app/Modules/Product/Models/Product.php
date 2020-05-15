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
use App\Modules\Category\Models\Category;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * 重写delete方法
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        // 删除之前先删除sku
        foreach ($this->skus as $sku) {
            $sku->delete();
        }

        return parent::delete();
    }

    public function syncSkus($skus)
    {
        if ($skus && is_array($skus)) {
            $sku_ids = array_column($this->skus->toArray(), 'id');
            $diff_ids = array_diff($sku_ids, array_keys($skus));
            if ($diff_ids) {
                foreach ($diff_ids as $sku_id) {
                    $product_sku = ProductSku::find($sku_id);
                    $product_sku && $product_sku->delete();
                }
            }

            foreach ($skus as $flag => $data) {
                ProductSku::updateOrCreate(['id' => $flag], [
                    'product_id' => $this->id,
                    'code' => $data['code'] ?: '',
                    'size' => $data['size'] ?: '',
                    'model' => $data['model'] ?: '',
                    'weight' => $data['weight'] ?: 0.00,
                    'cost_price' => $data['cost_price'] ?: 0.00,
                ]);
            }
        }

        return $this;
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->where('type', Category::PRODUCT);
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function stockoutSkus()
    {
        return $this->hasMany(ProductSku::class)->leftjoin('inventories AS i', 'i.sku_id', '=', 'product_skus.id')->whereRaw('i.stock < i.lowest_stock');
    }

    /**
     * 是否可删除
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        foreach ($this->skus as $sku) {
            if (!$sku->deletable) {
                return false;
            }
        }

        return true;
    }
}