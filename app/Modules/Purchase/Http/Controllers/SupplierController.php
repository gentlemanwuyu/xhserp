<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WorldService;

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
}
