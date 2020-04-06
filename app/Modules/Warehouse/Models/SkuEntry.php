<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/14
 * Time: 17:10
 */

namespace App\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Purchase\Models\PurchaseOrderItem;

class SkuEntry extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }
}