<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/10
 * Time: 19:02
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Index\Models\User;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    static $statuses = [
        1 => '待审核',
        2 => '已驳回',
        3 => '已通过',
        4 => '已完成',
        5 => '已取消',
    ];

    public function syncItems($items)
    {
        if (!$items || !is_array($items)) {
            return false;
        }

        // 将不在请求中的item删除
        PurchaseOrderItem::where('order_id', $this->id)->whereNotIn('id', array_keys($items))->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($items as $flag => $item) {
            PurchaseOrderItem::updateOrCreate(['id' => $flag], [
                'order_id' => $this->id,
                'product_id' => $item['product_id'],
                'sku_id' => $item['sku_id'],
                'title' => $item['title'],
                'unit' => $item['unit'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'delivery_date' => $item['delivery_date'] ?: null,
                'note' => $item['note'],
            ]);
        }

        return $this;
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'order_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaymentMethodNameAttribute()
    {
        return isset(Supplier::$payment_methods[$this->payment_method]) ? Supplier::$payment_methods[$this->payment_method] : '';
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }
}