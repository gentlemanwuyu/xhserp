<?php
namespace App\Modules\Category\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function save(Request $request)
    {
        try {
            $data = [
                'name' => $request->get('name', ''),
            ];

            // 判断分类名是否已存在
            $query = Category::where('name', $data['name']);
            if ($request->get('category_id')) {
                $query = $query->where('id', '!=', $request->get('category_id'));
            }
            if ($query->first()) {
                return response()->json(['status' => 'fail', 'msg' => '分类名已存在！']);
            }

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
