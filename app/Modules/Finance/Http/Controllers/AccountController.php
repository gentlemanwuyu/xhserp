<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Models\Account;

class AccountController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('finance::account.index');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->get('account_id')) {
            $data['account'] = Account::find($request->get('account_id'));
        }

        return view('finance::account.form', $data);
    }

    public function save(Request $request)
    {
        try {

            Account::updateOrCreate(['id' => $request->get('account_id')], [
                'name' => $request->get('name'),
                'bank' => $request->get('bank'),
                'branch' => $request->get('branch', ''),
                'payee' => $request->get('payee'),
                'account' => $request->get('account'),
            ]);

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function paginate(Request $request)
    {
        $paginate = Account::orderBy('id', 'desc')->paginate($request->get('limit'));

        return response()->json($paginate);
    }

    public function delete(Request $request)
    {
        try {
            $account = Account::find($request->get('account_id'));

            if (!$account) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该账户']);
            }

            $account->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
