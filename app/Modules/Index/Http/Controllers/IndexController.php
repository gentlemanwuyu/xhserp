<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {

	}

    public function loginPage()
    {
        return view('index::index.login');
    }
}
