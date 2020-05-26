<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Index\Models\Permission;
use App\Services\EntrustService;

class PermissionController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('index::permission.index');
    }

    public function paginate(Request $request)
    {
        $query = Permission::query();

        if ($request->get('name')) {
            $query = $query->where('name', $request->get('name'));
        }
        if ($request->get('display_name')) {
            $query = $query->where('display_name', 'like', '%' . $request->get('display_name') . '%');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $permission) {
            $permission->parent;
            $permission->setAppends(['parent_ids', 'deletable']);
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $tree = EntrustService::permissionTree();
        $data = compact('tree');
        if ($request->get('permission_id')) {
            $data['permission'] = Permission::find($request->get('permission_id'));
        }

        return view('index::permission.form', $data);
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'type' => $request->get('type', 0),
                'name' => $request->get('name', ''),
                'display_name' => $request->get('display_name', ''),
                'route' => $request->get('route', ''),
                'parent_id' => $request->get('parent_id', 0),
            ];

            Permission::updateOrCreate(['id' => $request->get('permission_id')], $data);
            // 更新完刷新缓存
            flush_permission_tree();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $permission = Permission::find($request->get('permission_id'));

            if (!$permission) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该权限']);
            }

            $permission->delete();
            // 删除完刷新缓存
            flush_permission_tree();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
