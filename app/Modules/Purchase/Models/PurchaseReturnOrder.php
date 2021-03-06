<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/5
 * Time: 3:00
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CodeTrait;
use App\Modules\Index\Models\User;
use App\Modules\Warehouse\Models\Express;
use App\Modules\Finance\Models\SkuEntryDeduction;

class PurchaseReturnOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'PRO';

    // 采购退货单状态
    const AGREED = 1;
    const EGRESSED = 2;
    const FINISHED = 3;
    const CANCELED = 4;
    static $statuses = [
        self::AGREED    => '已通过',
        self::EGRESSED  => '已出库',
        self::FINISHED  => '已完成',
        self::CANCELED  => '已取消',
    ];

    // 退货方式
    const EXCHANGE = 1;
    const BACK = 2;
    static $methods = [
        self::EXCHANGE  => '换货',
        self::BACK      => '退货',
    ];

    // 出货方式
    const BY_SELF   = 1;
    const SEND      = 2;
    const EXPRESS   = 3;
    static $delivery_methods = [
        self::BY_SELF => '供应商自取',
        self::SEND => '送货',
        self::EXPRESS => '快递物流',
    ];

    public function syncItems($items)
    {
        if (!$items || !is_array($items)) {
            return false;
        }

        // 将不在请求中的item删除
        PurchaseReturnOrderItem::where('purchase_return_order_id', $this->id)->whereNotIn('id', array_keys($items))->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($items as $flag => $item) {
            $item_data = array_only($item, [
                'purchase_order_item_id',
                'quantity',
                'egress_quantity',
            ]);

            $item = PurchaseReturnOrderItem::find($flag);

            if (!$item) {
                // 判断是否超出已出货数量
                $purchase_order_item = PurchaseOrderItem::find($item_data['purchase_order_item_id']);
                if ($purchase_order_item->returnable_quantity < $item_data['quantity']) {
                    throw new \Exception("[{$purchase_order_item->title}]退货数量[{$item_data['quantity']}]不可超出可退数量[{$purchase_order_item->returnable_quantity}]");
                }

                $item_data['purchase_return_order_id'] = $this->id;
                $item = PurchaseReturnOrderItem::create($item_data);
            }else {
                // 判断是否超出已出货数量
                if (!empty($item_data['quantity'])) {
                    $purchase_order_item = $item->purchaseOrderItem;
                    $returnable_quantity = $purchase_order_item->returnable_quantity + $item->quantity;
                    if ($item_data['quantity'] > $returnable_quantity) {
                        throw new \Exception("[{$purchase_order_item->title}]退货数量[{$item_data['quantity']}]不可超出可退数量[{$returnable_quantity}]");
                    }
                }
                $item->update($item_data);
            }
        }

        return $this;
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnOrderItem::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function express()
    {
        return $this->belongsTo(Express::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getMethodNameAttribute()
    {
        return isset(self::$methods[$this->method]) ? self::$methods[$this->method] : '';
    }

    public function getDeliveryMethodNameAttribute()
    {
        return isset(self::$delivery_methods[$this->delivery_method]) ? self::$delivery_methods[$this->delivery_method] : '';
    }

    /**
     * 退货单金额(人民币)
     *
     * @return int
     */
    public function getAmountAttribute()
    {
        $amount = 0;
        foreach ($this->items as $proi) {
            $purchase_order_item = $proi->purchaseOrderItem;
            $purchase_order = $purchase_order_item->purchaseOrder;
            $currency = $purchase_order->currency;
            $amount += $proi->quantity * $purchase_order_item->price * $currency->rate;
        }

        return $amount;
    }

    /**
     * 未抵扣金额
     *
     * @return mixed
     */
    public function getUndeductedAmountAttribute()
    {
        $deductions = SkuEntryDeduction::where('purchase_return_order_id', $this->id)->pluck('amount')->toArray();

        return $deductions ? $this->amount - array_sum($deductions) : $this->amount;
    }
}