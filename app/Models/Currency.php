<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/20
 * Time: 17:14
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
}