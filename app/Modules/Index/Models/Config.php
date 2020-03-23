<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/3/23
 * Time: 19:06
 */

namespace App\Modules\Index\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}