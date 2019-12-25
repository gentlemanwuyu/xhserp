<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sale\Models\Customer;

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
        $customer = Customer::find($request->get('customer_id'));

        return view('finance::collection.form', compact('customer'));
    }
}