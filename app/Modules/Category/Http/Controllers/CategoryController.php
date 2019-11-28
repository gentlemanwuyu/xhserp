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
}
