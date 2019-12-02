<?php
namespace App\Modules\Product\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Category\Models\Category;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductSku;

class ProductController extends Controller
{
    public function __construct()
    {
        
	}

    public function getList()
    {
        return view('product::product.list');
    }

    public function form(Request $request)
    {
        $categories = Category::where('type', 1)->get();
        $data = compact('categories');
        if ($request->get('product_id')) {
            $product = Product::find($request->get('product_id'));
            $data['product'] = $product;
        }

        return view('product::product.form', $data);
    }

    public function paginate(Request $request)
    {


        $paginate = Product::orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->skus;
        }


        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            $product_data = [
                'code' => $request->get('code', ''),
                'name' => $request->get('name', ''),
                'category_id' => $request->get('category_id', 0),
                'desc' => $request->get('desc', ''),
            ];

            DB::beginTransaction();

            $product = Product::updateOrCreate(['id' => $request->get('product_id')], $product_data);

            if (!$product) {
                throw new \Exception("产品保存失败");
            }

            // 同步sku
            $product->syncSkus($request->get('skus'));

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
            $product = Product::find($request->get('product_id'));

            if (!$product) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该产品']);
            }

            DB::beginTransaction();
            $product->delete();
            ProductSku::where('product_id', $request->get('product_id'))->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}