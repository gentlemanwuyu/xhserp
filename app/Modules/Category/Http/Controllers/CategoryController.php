<?php
namespace App\Modules\Category\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Category\Http\Requests\CategoryRequest;
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
                return response()->json(['status' => 'fail', 'msg' => '该分类下还有子分类']);
            }

            $category->delete();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
