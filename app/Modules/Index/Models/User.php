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

    /**
     * gender字段对应的性别
     *
     * @var array
     */
    protected $genders = [
        0 => '未知',
        1 => '男',
        2 => '女',
    ];

    /**
     * gender属性访问器
     *
     * @param $gender
     * @return string
     */
    public function getGenderAttribute()
    {
        return isset($this->genders[$this->gender_id]) ? $this->genders[$this->gender_id] : '未知';
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param  string  $ability
     * @param  array|mixed  $arguments
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        $can = Parent::can($ability, $arguments);

        $local = 'local' == env('APP_ENV');
        $is_admin = 1 == $this->is_admin;

        return $local || $is_admin || $can;
    }
}