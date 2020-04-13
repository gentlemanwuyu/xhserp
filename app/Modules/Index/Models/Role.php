<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/10
 * Time: 13:38
 */

namespace App\Modules\Index\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    public function giveAllPermissions()
    {
        $permissions = Permission::pluck('name')->toArray();
        $this->syncPermissions($permissions);

        return $this;
    }
}