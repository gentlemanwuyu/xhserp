<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Goods\Models\Goods;

class GoodsController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList(Request $request)
    {
        return view('goods::goods.list');
    }

    public function paginate(Request $request)
    {
        $paginate = Goods::orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $g) {
            $g->category;
            $g->skus;
        }

        return response()->json($paginate);
    }
}
