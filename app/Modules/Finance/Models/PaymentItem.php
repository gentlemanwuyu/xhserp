<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/30
 * Time: 20:01
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Warehouse\Models\SkuEntry;

class PaymentItem extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function entry()
    {
        return $this->belongsTo(SkuEntry::class, 'entry_id');
    }
}