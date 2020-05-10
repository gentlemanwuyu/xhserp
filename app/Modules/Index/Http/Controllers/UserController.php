<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;
use App\Modules\Index\Models\Role;
use App\Modules\Index\Models\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('index::user.index');
    }

    public function paginate(Request $request)
    {
        $query = User::query();

        if ($request->get('email')) {
            $query = $query->where('email', $request->get('email'));
        }
        if ($request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('status')) {
            $query = $query->where('status', $request->get('status'));
        }
        if (NO == Auth::user()->is_admin) {
            $query = $query->where('is_admin', NO);
        }else {
            if ('' !== $request->get('is_admin', '')) {
                $query = $query->where('is_admin', $request->get('is_admin'));
            }
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));
        foreach ($paginate as $user) {
            $user->roles;
            $user->setAppends(['gender', 'status_name', 'is_admin_name']);
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $roles = Role::all();
        $data = compact('roles');

        if ($request->get('user_id')) {
            $data['user'] = User::find($request->get('user_id'));
        }

        return view('index::user.form', $data);
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'name' => $request->get('name', ''),
                'birthday' => $request->get('birthday') ?: null,
                'telephone' => $request->get('telephone', ''),
                'gender_id' => $request->get('gender_id', 0),
            ];

            // 判断邮箱是否已存在
            if ($request->get('email')) {
                $user = User::where('email', $request->get('email'))->first();
                if ($user) {
                    return response()->json(['status' => 'fail', 'msg' => '邮箱已存在！']);
                }

                $data['email'] = $request->get('email');
            }

            if ($request->get('is_admin')) {
                $data['is_admin'] = $request->get('is_admin');
            }

            DB::beginTransaction();
            $user = User::updateOrCreate(['id' => $request->get('user_id')], $data);
            $roles = $request->get('roles') ? explode(',', $request->get('roles')) : [];
            $user->syncRoles($roles);

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
            $user = User::find($request->get('user_id'));

            if (!$user) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该用户']);
            }

            $user->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function passwordForm(Request $request)
    {
        return view('index::user.password_form');
    }

    public function resetPassword(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['status' => 'fail', 'msg' => '非法用户']);
            }

            if ($request->get('password') != $request->get('confirm_password')) {
                return response()->json(['status' => 'fail', 'msg' => '确认密码不一致']);
            }

            $user->password = bcrypt($request->get('password'));

            $user->save();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function assignPermission(Request $request)
    {
        $tree = Permission::tree();
        $user = User::find($request->get('user_id'));
        $roles_permissions = array_unique(array_column($user->getPermissionsViaRoles()->toArray(), 'name'));
        $permissions = array_unique(array_column($user->permissions->toArray(), 'name'));

        return view('index::user.assign_permission', compact('tree', 'user', 'roles_permissions', 'permissions'));
    }

    public function assign(Request $request)
    {
        try {
            $user = User::find($request->get('user_id'));

            if (!$user) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该用户']);
            }

            DB::beginTransaction();
            $user->syncPermissions($request->get('permissions', []));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
