<?php
namespace App\Modules\Product\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use App\Modules\Category\Models\Category;
use App\Modules\Product\Models\Product;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Warehouse\Models\Inventory;
use App\Modules\Purchase\Models\PurchaseOrderItem;

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
        $categories = Category::tree(1);
        $data = compact('categories');
        if ($request->get('product_id')) {
            $product = Product::find($request->get('product_id'));
            $product->category->setAppends(['parent_ids']);
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
        if ($request->get('category_ids')) {
            $category_ids = explode(',', $request->get('category_ids'));
            foreach ($category_ids as $category_id) {
                $category = Category::find($category_id);
                $category_ids = array_merge($category_ids, $category->children_ids);
            }

            $query = $query->whereIn('category_id', array_unique($category_ids));
        }

        if ($request->get('inventory_init')) {
            $product_ids = Inventory::pluck('product_id')->toArray();
            $product_ids = array_unique($product_ids);
            if (1 == $request->get('inventory_init')) {
                $query->whereIn('id', $product_ids);
            }elseif (2 == $request->get('inventory_init')) {
                $query->whereNotIn('id', $product_ids);
            }
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
            $product->setAppends(['deletable']);
        }

        return response()->json($paginate);
    }

    public function save(ProductRequest $request)
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
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $product = Product::find($request->get('product_id'));

            if (!$product) {
                throw new \Exception("没有找到该产品");
            }

            if (!$product->deletable) {
                throw new \Exception("该产品不可删除");
            }

            DB::beginTransaction();
            $product->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }

    public function detail(Request $request)
    {
        $suppliers = Supplier::all();
        $product = Product::find($request->get('product_id'));
        $product->skus->map(function ($product_sku) {
            $product_sku->inventoryLogs->map(function ($inventory_log) {
                $inventory_log->user;
            });
        });

        return view('product::product.detail', compact('product', 'suppliers'));
    }

    public function orderPaginate(Request $request)
    {
        $query = PurchaseOrderItem::leftJoin('purchase_orders AS po', 'po.id', '=', 'purchase_order_items.purchase_order_id')
            ->where('product_id', $request->get('product_id'));

        if ($request->get('purchase_order_code')) {
            $query = $query->where('po.code', $request->get('purchase_order_code'));
        }
        if ($request->get('supplier_id')) {
            $query = $query->where('po.supplier_id', $request->get('supplier_id'));
        }
        if ($request->get('created_at_between')) {
            $created_at_between = explode(' - ', $request->get('created_at_between'));
            $query = $query->where('purchase_order_items.created_at', '>=', $created_at_between[0] . ' 00:00:00')
                ->where('purchase_order_items.created_at', '<=', $created_at_between[1] . ' 23:59:59');
        }

        $paginate = $query->select(['purchase_order_items.*'])->orderBy('purchase_order_items.id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $poi) {
            $purchaseOrder = $poi->purchaseOrder;
            $purchaseOrder->supplier;
            $purchaseOrder->setAppends(['status_name']);
            $poi->sku->product;
        }

        return response()->json($paginate);
    }
}
