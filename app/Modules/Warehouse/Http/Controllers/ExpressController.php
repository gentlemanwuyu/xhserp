<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Warehouse\Models\Express;

class ExpressController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('warehouse::express.index');
    }

    public function paginate(Request $request)
    {
        $paginate = Express::orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $exp) {
            $exp->user;
        }

        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            $express = Express::find($request->get('express_id'));
            if ($express) {
                $express->name = $request->get('name');
                $express->save();
            }else {
                Express::create([
                    'name' => $request->get('name'),
                    'user_id' => Auth::user()->id,
                ]);
            }

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $express = Express::find($request->get('express_id'));

            if (!$express) {
                return response()->json(['status' => 'fail', 'msg' => '找不到该快递']);
            }

            $express->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
