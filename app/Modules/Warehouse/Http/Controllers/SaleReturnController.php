<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\ReturnOrder;

class SaleReturnController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        $customers = Customer::all();
        $users = User::where('is_admin', 0)->get();

        return view('warehouse::saleReturn.index', compact('customers', 'users'));
    }

    public function form(Request $request)
    {
        $return_order = ReturnOrder::find($request->get('return_order_id'));

        return view('warehouse::saleReturn.form', compact('return_order'));
    }
}
