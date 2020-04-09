<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Goods\Models\Goods;
use App\Modules\Category\Models\Category;

class GoodsController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList(Request $request)
    {
        $categories = Category::tree(2);

        return view('goods::goods.list', compact('categories'));
    }

    public function paginate(Request $request)
    {
        $query = Goods::query();

        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->get('category_id')) {
            $query = $query->where('category_id', $request->get('category_id'));
        }
        if ($request->get('type')) {
            $query = $query->where('type', $request->get('type'));
        }

        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $g) {
            $g->category;
            $g->skus->map(function ($sku) {
                $sku->setAppends(['stock']);

                return $sku;
            });
            $g->setAppends(['type_name']);
        }

        return response()->json($paginate);
    }

    public function detail(Request $request)
    {
        $goods = Goods::find($request->get('goods_id'));

        return view('goods::goods.detail', compact('goods'));
    }
}
