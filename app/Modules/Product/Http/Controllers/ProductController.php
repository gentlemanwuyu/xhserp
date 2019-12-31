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
        $categories = Category::tree(1);

        return view('product::product.list', compact('categories'));
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
        $query = Product::query();
        if ($request->get('code')) {
            $query = $query->where('code', $request->get('code'));
        }
        if ($request->get('name')) {
            $query = $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('created_at', '>=', $created_at_between[0] . ' 00:00:00')->where('created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $product) {
            $product->category;
            $product->skus->map(function ($sku) {
                $sku->inventory;
            });
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
