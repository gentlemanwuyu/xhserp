<?php
namespace App\Modules\Category\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Category\Http\Requests\CategoryRequest;
use App\Modules\Goods\Models\Goods;
use App\Modules\Product\Models\Product;
use App\Modules\Category\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {

	}

    public function tree($type, Request $request)
    {
        $request->merge(['type' => $type]);

        return view('category::category.tree');
    }

    public function data($type)
    {
        return response()->json(['status' => ['code' => 200, 'message' => ''], 'data' => Category::tree($type)]);
    }

    public function save(CategoryRequest $request)
    {
        try {
            $data = [
                'name' => $request->get('name', ''),
            ];

            if ($request->get('type')) {
                $data['type'] = $request->get('type');
            }

            if ($request->get('parent_id')) {
                $data['parent_id'] = $request->get('parent_id');
            }

            Category::updateOrCreate(['id' => $request->get('category_id')], $data);

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $category = Category::find($request->get('category_id'));

            if (!$category->children->isEmpty()) {
                throw new \Exception("该分类下还有子分类");
            }

            $category_ids = array_unique(array_merge([$category->id], $category->children_ids));

            if (1 == $category->type && Product::whereIn('category_id', $category_ids)->exists()) {
                throw new \Exception("该分类或子分类下还有产品，不能删除");
            }elseif (2 == $category->type && Goods::whereIn('category_id', $category_ids)->exists()) {
                throw new \Exception("该分类或子分类下还有商品，不能删除");
            }

            $category->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
