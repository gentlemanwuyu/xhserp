<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Goods\Models\Goods;
use App\Modules\Goods\Models\GoodsSku;
use App\Modules\Goods\Models\Single;
use App\Modules\Goods\Models\SingleProduct;
use App\Modules\Goods\Models\SingleSkuProductSku;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class SingleController extends Controller
{
    public function __construct()
    {
        
	}

    public function selectProduct()
    {
        $categories = Category::where('type', 1)->get();

        return view('goods::single.select_product', compact('categories'));
    }

    public function productPaginate(Request $request)
    {
        $query = Product::leftJoin('single_products AS sp', 'sp.product_id', '=', 'products.id');

        $query = $query->whereNull('sp.goods_id');
        if ($request->get('category_id')) {
            $query = $query->where('products.category_id', $request->get('category_id'));
        }
        if ($request->get('code')) {
            $query = $query->where('products.code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('products.name', 'like', '%' . $request->get('name') . '%');
        }

        $paginate = $query->select(['products.*'])->orderBy('products.id', 'desc')->paginate($request->get('limit'));

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

        if ($request->get('product_id')) {
            $product = Product::find($request->get('product_id'));
            $data['product'] = $product;
        }else {
            $goods = Single::find($request->get('goods_id'));
            $data['goods'] = $goods;
            $data['product'] = $goods->product;
        }

        return view('goods::single.form', $data);
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
                $goods_data['type'] = Goods::SINGLE;
            }

            DB::beginTransaction();

            $single = Single::updateOrCreate(['id' => $request->get('goods_id')], $goods_data);

            if (!$single) {
                throw new \Exception("商品保存失败");
            }

            if (!$request->get('goods_id')) {
                SingleProduct::create(['goods_id' => $single->id, 'product_id' => $request->get('product_id')]);
            }

            // 同步sku
            $single->syncSkus($request->get('skus'));

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
            $single = Single::find($request->get('goods_id'));

            if (!$single) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该商品']);
            }

            DB::beginTransaction();
            $single->delete();
            SingleProduct::where('goods_id', $request->get('goods_id'))->delete();
            GoodsSku::withTrashed()->where('goods_id', $request->get('goods_id'))->get()->map(function ($goods_sku) {
                $goods_sku->delete();
                SingleSkuProductSku::where('goods_sku_id', $goods_sku->id)->delete();
            });

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
