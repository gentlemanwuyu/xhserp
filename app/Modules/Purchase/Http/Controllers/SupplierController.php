<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WorldService;
use App\Modules\Purchase\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('purchase::supplier.index');
    }

    public function form()
    {
        $chinese_regions = WorldService::chineseTree();

        return view('purchase::supplier.form', compact('chinese_regions'));
    }

    public function paginate(Request $request)
    {
        $paginate = Supplier::orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $supplier) {
            $supplier->contacts;
        }

        return response()->json($paginate);
    }
}
