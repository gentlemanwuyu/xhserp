<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/10
 * Time: 19:05
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductSku;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'sku_id');
    }

    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_id');
    }
}