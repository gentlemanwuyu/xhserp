<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Models\Customer;
use App\Modules\Index\Models\User;

class EgressController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $customers = Customer::all();
        $users = User::where('is_admin', 0)->get();

        return view('warehouse::egress.index', compact('customers', 'users'));
    }
}
