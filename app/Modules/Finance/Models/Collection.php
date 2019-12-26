<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/26
 * Time: 15:28
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
}