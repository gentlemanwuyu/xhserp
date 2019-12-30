<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/30
 * Time: 20:00
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function items()
    {
        return $this->hasMany(PaymentItem::class);
    }
}