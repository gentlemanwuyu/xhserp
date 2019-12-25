<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/25
 * Time: 20:49
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
}