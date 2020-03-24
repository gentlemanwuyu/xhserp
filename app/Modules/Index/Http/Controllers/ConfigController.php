<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\Config;

class ConfigController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('index::config.index');
    }

    public function paginate(Request $request)
    {
        $query = Config::query();

        if ($request->get('key')) {
            $query = $query->where('key', 'like', '%' . $request->get('key') . '%');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));
        foreach ($paginate as $config) {
            $config->user;
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->get('config_id')) {
            $data['config'] = Config::find($request->get('config_id'));
        }

        return view('index::config.form', $data);
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'key' => $request->get('key', ''),
                'value' => $request->get('value', ''),
            ];

            if (!$request->get('config_id')) {
                $data['user_id'] = Auth::user()->id;
            }

            Config::updateOrCreate(['id' => $request->get('config_id')], $data);
            // 刷新缓存
            flush_sys_configs();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $config = Config::find($request->get('config_id'));

            if (!$config) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该配置']);
            }

            $config->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
