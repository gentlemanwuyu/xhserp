<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/26
 * Time: 15:28
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
}