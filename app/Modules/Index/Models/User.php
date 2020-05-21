<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/23
 * Time: 21:51
 */

namespace App\Modules\Index\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Modules\Warehouse\Models\Express;
use App\Modules\Warehouse\Models\InventoryLog;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\OrderLog;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\ReturnOrder;
use App\Modules\Sale\Models\DeliveryOrder;
use App\Modules\Sale\Models\ReturnOrderLog;
use App\Modules\Sale\Models\PaymentMethodApplication;
use App\Modules\Sale\Models\PaymentMethodApplicationLog;
use App\Modules\Purchase\Models\PurchaseOrder;
use App\Modules\Purchase\Models\PurchaseOrderLog;
use App\Modules\Purchase\Models\PurchaseReturnOrder;
use App\Modules\Finance\Models\Payment;
use App\Modules\Finance\Models\Collection;

class User extends Authenticatable
{
    use SoftDeletes, HasRoles;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 性别
    const MALE = 1;
    const FEMALE = 2;
    static $genders = [
        self::MALE      => '男',
        self::FEMALE    => '女',
    ];

    // 状态
    const ENABLED = 1;
    const DISABLED = 2;
    static $statuses = [
        self::ENABLED => '正常',
        self::DISABLED => '已禁用',
    ];

    /**
     * gender属性访问器
     *
     * @param $gender
     * @return string
     */
    public function getGenderAttribute()
    {
        return isset(self::$genders[$this->gender_id]) ? self::$genders[$this->gender_id] : '';
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getIsAdminNameAttribute()
    {
        return YES == $this->is_admin ? '是' : '否';
    }

    /**
     * 是否可删除
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        if (Payment::where('user_id', $this->id)->orWhere('pay_user_id', $this->id)->exists()) {
            return false;
        }
        if (Collection::where('user_id', $this->id)->orWhere('collect_user_id', $this->id)->exists()) {
            return false;
        }
        if (Config::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (Customer::where('manager_id', $this->id)->exists()) {
            return false;
        }
        if (DeliveryOrder::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (Express::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (InventoryLog::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (Order::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (OrderLog::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (PaymentMethodApplication::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (PaymentMethodApplicationLog::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (PurchaseOrder::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (PurchaseOrderLog::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (PurchaseReturnOrder::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (ReturnOrder::where('user_id', $this->id)->exists()) {
            return false;
        }
        if (ReturnOrderLog::where('user_id', $this->id)->exists()) {
            return false;
        }

        return true;
    }

    public function giveAllPermissions()
    {
        $permissions = Permission::pluck('name')->toArray();
        $this->syncPermissions($permissions);

        return $this;
    }

    /**
     * 重写方法，本地环境和管理员拥有所有权限
     *
     * @param $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = app(Permission::class)->findByName($permission);
        }

        $has_permission = $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);

        $local = 'local' == env('APP_ENV');
        $is_admin = 1 == $this->is_admin;

        return $local || $is_admin || $has_permission;
    }
}