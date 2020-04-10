<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Index\Models\Role;

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
        $data = [];
        if ($request->get('role_id')) {
            $data['role'] = Role::find($request->get('role_id'));
        }

        return view('index::role.form', $data);
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'name' => $request->get('name', ''),
                'display_name' => $request->get('display_name', ''),
            ];

            Role::updateOrCreate(['id' => $request->get('role_id')], $data);

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
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
