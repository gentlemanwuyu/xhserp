<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/10
 * Time: 13:38
 */

namespace App\Modules\Index\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\DB;

class Role extends SpatieRole
{
    public function giveAllPermissions()
    {
        $permissions = Permission::pluck('name')->toArray();
        $this->syncPermissions($permissions);

        return $this;
    }

    public function getDeletableAttribute()
    {
        if (DB::table('user_has_roles')->where('role_id', $this->id)->exists()) {
            return false;
        }

        return true;
    }
}