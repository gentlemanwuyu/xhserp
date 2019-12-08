<?php
namespace App\Modules\Product\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {

	}

    public function form()
    {
        return view('product::inventory.form');
    }
}
