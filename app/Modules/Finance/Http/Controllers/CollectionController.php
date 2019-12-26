<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Finance\Models\Account;

class CollectionController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('finance::collection.index');
    }

    public function form(Request $request)
    {
        $users = User::where('is_admin', 0)->get();
        $customer = Customer::find($request->get('customer_id'));
        $accounts = Account::all();

        return view('finance::collection.form', compact('users', 'customer', 'accounts'));
    }
}
