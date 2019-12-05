<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 11:09
 */

namespace App\Modules\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    protected $fillable = ['goods_id', 'product_id', 'quantity'];

    protected $timestamps = false;
}