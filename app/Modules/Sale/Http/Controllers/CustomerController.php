<?php
namespace App\Modules\Sale\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Sale\Models\Customer;
use App\Services\WorldService;

class CustomerController extends Controller
{
    public function __construct()
    {

	}

    public function index()
    {
        return view('sale::customer.index');
    }

    public function form(Request $request)
    {
        $chinese_regions = WorldService::chineseTree();
        $data = compact('chinese_regions');
        if ($request->get('customer_id')) {
            $customer = Customer::find($request->get('customer_id'));
            $data['customer'] = $customer;
        }

        return view('sale::customer.form', $data);
    }

    public function paginate(Request $request)
    {
        $paginate = Customer::orderBy('id', 'desc')->paginate($request->get('limit'));

        foreach ($paginate as $customer) {
            $customer->contacts;
            $customer->setAppends(['payment_method_name']);
        }

        return response()->json($paginate);
    }

    public function save(Request $request)
    {
        try {
            $customer_data = [
                'code' => $request->get('code', ''),
                'name' => $request->get('name', ''),
                'company' => $request->get('company', ''),
                'phone' => $request->get('phone', ''),
                'fax' => $request->get('fax', ''),
                'payment_method' => $request->get('payment_method', 0),
                'credit' => $request->get('credit', 0),
                'monthly_day' => $request->get('monthly_day', 0),
                'state_id' => $request->get('state_id', 0),
                'city_id' => $request->get('city_id', 0),
                'county_id' => $request->get('county_id', 0),
                'street_address' => $request->get('street_address', ''),
                'intro' => $request->get('intro', ''),
            ];

            DB::beginTransaction();

            $customer = Customer::updateOrCreate(['id' => $request->get('customer_id')], $customer_data);

            if (!$customer) {
                throw new \Exception("供应商保存失败");
            }

            // 同步联系人
            $customer->syncContacts($request->get('contacts'));

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
            $customer = Customer::find($request->get('customer_id'));

            if (!$customer) {
                return response()->json(['status' => 'fail', 'msg' => '没有找到该客户']);
            }

            DB::beginTransaction();
            $customer->delete();

            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'msg' => '[' . get_class($e) . ']' . $e->getMessage()]);
        }
    }
}
