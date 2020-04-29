<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/25
 * Time: 20:49
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * 是否可删除
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        if (Collection::where('account_id', $this->id)->exists()) {
            return false;
        }
        if (Payment::where('account_id', $this->id)->exists()) {
            return false;
        }

        return true;
    }
}