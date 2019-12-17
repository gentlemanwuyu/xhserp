<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpressController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('warehouse::express.index');
    }
}
