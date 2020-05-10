<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Index\Models\User;
use Illuminate\Http\Request;
use App\Modules\Index\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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
            'status' => User::ENABLED,
        ];
        $login = Auth::attempt($parameters,$request->get('remember'));

        if($login){
            return ['status' => 'success'];
        }else{
            return ['status' => 'fail', 'msg'=>'邮箱或密码不正确'];
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return Redirect::intended('/');
    }

    public function index()
    {
        return view('index::index.index');
    }

    public function home()
    {
        return view('index::index.home');
    }
}
