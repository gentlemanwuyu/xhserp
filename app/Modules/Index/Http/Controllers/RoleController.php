<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Index\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;
use App\Modules\Index\Models\Role;
use App\Modules\Index\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('index::role.index');
    }

    public function paginate(Request $request)
    {
        $query = Role::query();

        if ($request->get('name')) {
            $query = $query->where('name', $request->get('name'));
        }
        if ($request->get('display_name')) {
            $query = $query->where('display_name', 'like', '%' . $request->get('display_name') . '%');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $tree = Permission::tree();
        $data = compact('tree');
        $permissions = [];
        if ($request->get('role_id')) {
            $role = Role::find($request->get('role_id'));
            $data['role'] = $role;
            $permissions = array_unique(array_column($role->permissions->toArray(), 'name'));
        }
        $data['permissions'] = $permissions;

        return view('index::role.form', $data);
    }

    public function save(RoleRequest $request)
    {
        try {
            $data = [
                'name' => $request->get('name', ''),
                'display_name' => $request->get('display_name', ''),
            ];

            $name_exist = Role::where('name', $request->get('name'));
            if ($request->get('role_id')) {
                $name_exist->where('id', '!=', $request->get('role_id'));
            }
            if ($name_exist->first()) {
                throw new \Exception("角色名已存在");
            }

            DB::beginTransaction();
            $role = Role::updateOrCreate(['id' => $request->get('role_id')], $data);
            $permissions = $request->get('permissions') ? array_values($request->get('permissions')) : [];
            $role->syncPermissions($permissions);

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $role = Role::find($request->get('role_id'));

            if (!$role) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该角色']);
            }

            $role->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
