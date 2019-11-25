<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Index\Models\User;

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

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));
        foreach ($paginate as $user) {
            $gender = $user->gender;
            $user->gender = $gender;
        }

        return response()->json($paginate);
    }

    public function form()
    {
        return view('index::user.form');
    }
}
