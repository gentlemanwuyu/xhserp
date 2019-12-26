<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/26
 * Time: 15:28
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Sale\Models\DeliveryOrderItem;

class CollectionItem extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function deliveryOrderItem()
    {
        return $this->belongsTo(DeliveryOrderItem::class, 'doi_id');
    }
}