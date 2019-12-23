<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/8
 * Time: 11:18
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ChineseRegion;

class Customer extends Model
{
    use SoftDeletes;

    static $payment_methods = [
        1 => '现金',
        2 => '货到付款',
        3 => '月结',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
    }

    public function getPaymentMethodNameAttribute()
    {
        return isset(self::$payment_methods[$this->payment_method]) ? self::$payment_methods[$this->payment_method] : '';
    }

    public function syncContacts($contacts)
    {
        if (!$contacts || !is_array($contacts)) {
            return false;
        }

        // 将不在请求中的联系人删除
        CustomerContact::where('customer_id', $this->id)->whereNotIn('id', array_keys($contacts))->get()->map(function ($contact) {
            $contact->delete();
        });

        foreach ($contacts as $flag => $item) {
            CustomerContact::updateOrCreate(['id' => $flag], [
                'customer_id' => $this->id,
                'name' => $item['name'],
                'position' => $item['position'],
                'phone' => $item['phone'],
            ]);
        }

        return $this;
    }

    public function state()
    {
        return $this->belongsTo(ChineseRegion::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(ChineseRegion::class, 'city_id');
    }

    public function county()
    {
        return $this->belongsTo(ChineseRegion::class, 'county_id');
    }

    public function getFullAddressAttribute()
    {
        $full_address = '';
        if ($this->state_id) {
            $full_address .= $this->state->name;
        }
        if ($this->city_id) {
            $full_address .= $this->city->name;
        }
        if ($this->county_id) {
            $full_address .= $this->county->name;
        }
        $full_address .= $this->street_address;

        return $full_address;
    }

    public function getUnpaidItemsAttribute()
    {
        return DeliveryOrder::leftJoin('delivery_order_items AS doi', 'doi.delivery_order_id', '=', 'delivery_orders.id')
            ->leftJoin('orders AS o', 'o.id', '=', 'doi.order_id')
            ->leftJoin('order_items AS oi', 'oi.id', '=', 'doi.order_item_id')
            ->leftJoin('goods_skus AS gs', 'gs.id', '=', 'oi.sku_id')
            ->where('delivery_orders.status', '2')
            ->where('delivery_orders.customer_id', $this->id)
            ->where('doi.is_paid', 0)
            ->get([
                'o.code AS order_code',
                'gs.code AS sku_code',
                'oi.quantity AS order_quantity',
                'doi.quantity AS delivery_quantity',
                'oi.price',
                'doi.created_at AS delivery_at',
            ]);
    }
}