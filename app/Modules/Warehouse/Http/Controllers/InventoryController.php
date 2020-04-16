<?php
namespace App\Modules\Warehouse\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Warehouse\Http\Requests\InventoryRequest;
use Illuminate\Support\Facades\DB;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductSku;
use App\Modules\Warehouse\Models\Inventory;
use App\Modules\Warehouse\Models\InventoryLog;

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

    public function save(InventoryRequest $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->get('inventory') as $sku_id => $inventory) {
                $data = [
                    'highest_stock' => $inventory['highest_stock'] ?: 0,
                    'lowest_stock' => $inventory['lowest_stock'] ?: 0,
                ];
                if (isset($inventory['stock'])) {
                    $data['stock'] = $inventory['stock'] ?: 0;
                }

                $product_sku = ProductSku::find($sku_id);
                $ori_inventory = Inventory::where('sku_id', $sku_id)->first();
                if ($ori_inventory) {
                    $data['sku_id'] = $sku_id;
                    $data['product_id'] = $product_sku->product_id;
                }

                $inventory = Inventory::updateOrCreate(['sku_id' => $sku_id], $data);

                InventoryLog::log($ori_inventory, $inventory);
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'exception' => get_class($e)]);
        }
    }
}
