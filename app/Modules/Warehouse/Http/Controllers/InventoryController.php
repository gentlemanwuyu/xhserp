<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Product\Models\Product;
use App\Modules\Warehouse\Models\Inventory;

class InventoryController extends Controller
{
    public function __construct()
    {

    }

    public function form(Request $request)
    {
        $product = Product::find($request->get('product_id'));

        return view('warehouse::inventory.form', compact('product'));
    }

    public function save(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->get('inventory') as $sku_id => $inventory) {
                $data = [
                    'highest_stock' => $inventory['highest_stock'],
                    'lowest_stock' => $inventory['lowest_stock'],
                ];
                if (!empty($inventory['stock'])) {
                    $data['stock'] = $inventory['stock'];
                }

                Inventory::updateOrCreate(['sku_id' => $sku_id], $data);
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
