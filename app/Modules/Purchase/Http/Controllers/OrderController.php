<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('purchase::order.index');
    }

    public function form()
    {
        return view('purchase::order.form');
    }
}
