<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Goods\Models\Goods;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\OrderItem;
use App\Modules\Category\Models\Category;

class GoodsController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList(Request $request)
    {
        $categories = Category::tree(Category::GOODS);

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
        if ($request->get('category_ids')) {
            $category_ids = explode(',', $request->get('category_ids'));
            foreach ($category_ids as $category_id) {
                $category = Category::find($category_id);
                $category_ids = array_merge($category_ids, $category->children_ids);
            }

            $query = $query->whereIn('category_id', array_unique($category_ids));
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
            $g->setAppends(['type_name', 'deletable']);
        }

        return response()->json($paginate);
    }

    public function detail(Request $request)
    {
        $goods = Goods::find($request->get('goods_id'));
        $customers = Customer::all();

        return view('goods::goods.detail', compact('goods', 'customers'));
    }

    public function delete(Request $request)
    {
        try {
            $goods = Goods::find($request->get('goods_id'));

            if (!$goods) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该商品']);
            }

            DB::beginTransaction();
            $goods->skus->map(function ($sku) {
                $sku->delete();
            });
            $goods->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function orderPaginate(Request $request)
    {
        $query = OrderItem::leftJoin('orders AS o', 'o.id', '=', 'order_items.order_id')
            ->whereNull('o.deleted_at')
            ->where('order_items.goods_id', $request->get('goods_id'));

        if ($request->get('order_code')) {
            $query = $query->where('o.code', $request->get('order_code'));
        }
        if ($request->get('customer_id')) {
            $query = $query->where('o.customer_id', $request->get('customer_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('order_items.created_at', '>=', $created_at_between[0] . ' 00:00:00')
                ->where('order_items.created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->select(['order_items.*'])->orderBy('order_items.id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $oi) {
            $order = $oi->order;
            $order->customer;
            $order->setAppends(['status_name']);
            $oi->sku->goods;
        }

        return response()->json($paginate);
    }
}
