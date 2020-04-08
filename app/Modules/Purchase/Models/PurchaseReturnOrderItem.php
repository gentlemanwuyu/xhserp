<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/5
 * Time: 3:01
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Warehouse\Models\SkuEntryExchange;

class PurchaseReturnOrderItem extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function purchaseReturnOrder()
    {
        return $this->belongsTo(PurchaseReturnOrder::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function exchangeEntries()
    {
        return $this->hasMany(SkuEntryExchange::class);
    }

    public function getEntriedQuantityAttribute()
    {
        $exchange_entries = $this->exchangeEntries;

        return $exchange_entries->isEmpty() ? 0 : array_sum(array_column($exchange_entries->toArray(), 'quantity'));
    }
}