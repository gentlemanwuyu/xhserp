<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('finance::payment.index');
    }
}
