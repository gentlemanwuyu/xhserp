<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/17
 * Time: 12:45
 */

namespace App\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Index\Models\User;

class Express extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}