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

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
    }

    public function syncContacts($contacts)
    {
        if (!$contacts || !is_array($contacts)) {
            return false;
        }

        // 将不在请求中的联系人删除
        CustomerContact::where('supplier_id', $this->id)->whereNotIn('id', array_keys($contacts))->get()->map(function ($contact) {
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
}