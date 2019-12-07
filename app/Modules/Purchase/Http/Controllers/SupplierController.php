<?php
namespace App\Modules\Purchase\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\WorldService;
use App\Modules\Purchase\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('purchase::supplier.index');
    }

    public function form(Request $request)
    {
        $chinese_regions = WorldService::chineseTree();
        $data = compact('chinese_regions');
        if ($request->get('supplier_id')) {
            $supplier = Supplier::find($request->get('supplier_id'));
            $data['supplier'] = $supplier;
        }

        return view('purchase::supplier.form', $data);
    }

    public function paginate(Request $request)
    {
        $paginate = Supplier::orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $supplier) {
            $supplier->contacts;
        }

        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            $supplier_data = [
                'code' => $request->get('code', ''),
                'name' => $request->get('name', ''),
                'company' => $request->get('company', ''),
                'phone' => $request->get('phone', ''),
                'fax' => $request->get('fax', ''),
                'state_id' => $request->get('state_id', 0),
                'city_id' => $request->get('city_id', 0),
                'county_id' => $request->get('county_id', 0),
                'street_address' => $request->get('street_address', ''),
                'intro' => $request->get('intro', ''),
            ];

            DB::beginTransaction();

            $supplier = Supplier::updateOrCreate(['id' => $request->get('supplier_id')], $supplier_data);

            if (!$supplier) {
                throw new \Exception("供应商保存失败");
            }

            // 同步联系人
            $supplier->syncContacts($request->get('contacts'));

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
            $supplier = Supplier::find($request->get('supplier_id'));

            if (!$supplier) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该供应商']);
            }

            DB::beginTransaction();
            $supplier->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
