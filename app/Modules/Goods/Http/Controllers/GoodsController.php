<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList(Request $request)
    {
        return view('goods::goods.list');
    }
}
