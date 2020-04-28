<?php
namespace App\Modules\Finance\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function __construct()
    {
        
	}

    public function index(Request $request)
    {
        return view('finance::currency.index');
    }

    public function paginate(Request $request)
    {
        $query = Currency::query();
        if ($request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $paginate = $query->orderBy('id')->paginate($request->get('limit'));

        return response()->json($paginate);
    }
}
