<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Index\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __construct()
    {

	}

    public function loginPage()
    {
        return view('index::index.login');
    }

    public function login(LoginRequest $request)
    {
        $parameters = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];
        $login = Auth::attempt($parameters,$request->get('remember'));

        if($login){
            return ['status' => 'success'];
        }else{
            return ['status' => 'fail', 'msg'=>'邮箱或密码不正确'];
        }
    }

    public function index()
    {
        return view('index::index.index');
    }
}
