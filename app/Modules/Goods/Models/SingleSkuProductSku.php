<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 11:10
 */

namespace App\Modules\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class SingleSkuProductSku extends Model
{
    protected $fillable = ['goods_sku_id', 'product_sku_id'];

    public $timestamps = false;
}