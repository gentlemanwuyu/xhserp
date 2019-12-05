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

    public function syncSkus($skus)
    {
        if ($skus && is_array($skus)) {
            $sku_ids = array_column($this->skus->toArray(), 'id');
            $diff_ids = array_diff($sku_ids, array_keys($skus));
            if ($diff_ids) {
                ProductSku::whereIn('id', $diff_ids)->delete();
            }

            foreach ($skus as $flag => $data) {
                ProductSku::updateOrCreate(['id' => $flag], [
                    'product_id' => $this->id,
                    'code' => $data['code'] ?: '',
                    'weight' => $data['weight'] ?: 0.00,
                    'cost_price' => $data['cost_price'] ?: 0.00,
                ]);
            }
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->where('type', 1);
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }
}