<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 11:05
 */

namespace App\Modules\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class SingleProduct extends Model
{
    protected $fillable = ['goods_id', 'product_id'];

    public $timestamps = false;
}