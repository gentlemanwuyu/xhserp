<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Goods\Models\Goods;
use App\Modules\Goods\Models\GoodsSku;
use App\Modules\Goods\Models\Combo;
use App\Modules\Goods\Models\ComboProduct;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class ComboController extends Controller
{
    public function __construct()
    {
        
	}

    public function selectProduct()
    {
        $categories = Category::where('type', 1)->get();

        return view('goods::combo.select_product', compact('categories'));
    }

    public function productPaginate(Request $request)
    {
        $query = Product::query();
        if ($request->get('excepted_ids')) {
            $query = $query->whereNotIn('id', $request->get('excepted_ids'));
        }
        if ($request->get('category_id')) {
            $query = $query->where('category_id', $request->get('category_id'));
        }
        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->skus;
        }

        return response()->json($paginate);
    }

    public function form(Request $request)
    {
        $categories = Category::where('type', 2)->get();
        $data = compact('categories');

        if ($request->get('goods_id')) {
            $goods = Combo::find($request->get('goods_id'));
            $data['goods'] = $goods;
            $data['products'] = $goods->products;
        }else {
            $selectedInputs = $request->get('selected');
            $products = Product::whereIn('id', array_keys($selectedInputs))->get();
            foreach ($products as $product) {
                $product->quantity = $selectedInputs[$product->id] ?: 0;
            }
            $data['products'] = $products;
        }

        return view('goods::combo.form', $data);
    }

    public function save(Request $request)
    {
        try {
            $goods_data = [
                'code' => $request->get('code', ''),
                'name' => $request->get('name', ''),
                'category_id' => $request->get('category_id', 0),
                'desc' => $request->get('desc', ''),
            ];

            if (!$request->get('goods_id')) {
                $goods_data['type'] = Goods::COMBO;
            }

            DB::beginTransaction();

            $combo = Combo::updateOrCreate(['id' => $request->get('goods_id')], $goods_data);

            if (!$combo) {
                throw new \Exception("商品保存失败");
            }

            if (!$request->get('goods_id')) {
                foreach ($request->get('product_ids') as $product_id => $quantity) {
                    ComboProduct::create([
                        'goods_id' => $combo->id,
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                    ]);
                }
            }

            // 同步sku
            $combo->syncSkus($request->get('skus'));

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $combo = Combo::find($request->get('goods_id'));

            if (!$combo) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该商品']);
            }

            DB::beginTransaction();
            $combo->delete();
            GoodsSku::where('goods_id', $request->get('goods_id'))->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
