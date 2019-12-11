<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/11
 * Time: 16:57
 */

namespace App\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

}