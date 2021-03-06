<?php
namespace App\Modules\Goods\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Goods\Http\Requests\SingleRequest;
use Illuminate\Support\Facades\DB;
use App\Modules\Goods\Models\Goods;
use App\Modules\Goods\Models\Single;
use App\Modules\Goods\Models\SingleProduct;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class SingleController extends Controller
{
    public function __construct()
    {
        
	}

    public function selectProduct()
    {
        $categories = Category::tree(Category::PRODUCT);

        return view('goods::single.select_product', compact('categories'));
    }

    public function productPaginate(Request $request)
    {
        $query = Product::leftJoin('single_products AS sp', 'sp.product_id', '=', 'products.id');

        $query = $query->whereNull('sp.goods_id');
        if ($request->get('category_ids')) {
            $category_ids = explode(',', $request->get('category_ids'));
            foreach ($category_ids as $category_id) {
                $category = Category::find($category_id);
                $category_ids = array_merge($category_ids, $category->children_ids);
            }

            $query = $query->whereIn('category_id', array_unique($category_ids));
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
        $categories = Category::tree(Category::GOODS);
        $data = compact('categories');

        if ($request->get('product_id')) {
            $product = Product::find($request->get('product_id'));
        }else {
            $goods = Single::find($request->get('goods_id'));
            $goods->category->setAppends(['parent_ids']);
            $data['goods'] = $goods;
            $product = $goods->product;
        }
        $product->indexSkus = $product->skus->map(function ($product_sku) {
            $product_sku->setAppends(['single_sku']);

            return $product_sku;
        })->pluck(null, 'id');
        $data['product'] = $product;

        return view('goods::single.form', $data);
    }

    public function save(SingleRequest $request)
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
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
