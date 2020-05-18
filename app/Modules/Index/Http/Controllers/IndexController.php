<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Index\Models\User;
use Illuminate\Http\Request;
use App\Modules\Index\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Product\Models\Product;
use App\Modules\Sale\Models\DeliveryOrder;
use App\Modules\Purchase\Models\PurchaseOrder;
use App\Modules\Purchase\Models\PurchaseOrderItem;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\ReturnOrder;
use App\Modules\Sale\Models\PaymentMethodApplication;

class IndexController extends Controller
{
    public function __construct()
    {

	}

    public function loginPage()
    {
        return view('index::index.login');
    }

    public function login(LoginRequest $request)
    {
        $parameters = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'status' => User::ENABLED,
        ];
        $login = Auth::attempt($parameters,$request->get('remember'));

        if($login){
            return ['status' => 'success'];
        }else{
            return ['status' => 'fail', 'msg'=>'邮箱或密码不正确'];
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return Redirect::intended('/');
    }

    public function index()
    {
        return view('index::index.index');
    }

    public function home()
    {
        $delivery_order_number = DeliveryOrder::where('status', DeliveryOrder::PENDING_DELIVERY)->count();
        $purchase_order_sku_number = PurchaseOrderItem::leftJoin('purchase_orders AS po', 'po.id', '=', 'purchase_order_items.purchase_order_id')
            ->whereNull('po.deleted_at')
            ->where(function ($query) {
                $query->where('po.status', PurchaseOrder::AGREED)->orWhere('po.exchange_status', 1);
            })
            ->pluck('purchase_order_items.sku_id')
            ->unique()
            ->count();
        $return_order_number = ReturnOrder::where('status', ReturnOrder::AGREED)->count();
        $stockout_sku_number = Product::leftJoin('product_skus AS ps', 'ps.product_id', '=', 'products.id')
            ->leftjoin('inventories AS i', 'i.sku_id', '=', 'ps.id')
            ->whereRaw('i.stock < i.lowest_stock')
            ->pluck('ps.product_id')
            ->unique()
            ->count();
        $pending_review_order_number = Order::where('status', Order::PENDING_REVIEW)->count();
        $pending_review_return_order_number = ReturnOrder::where('status', ReturnOrder::PENDING_REVIEW)->count();
        $pending_review_mpa_number = PaymentMethodApplication::where('status', PaymentMethodApplication::PENDING_REVIEW)->count();

        return view('index::index.home', compact('delivery_order_number', 'purchase_order_sku_number', 'return_order_number', 'stockout_sku_number', 'pending_review_order_number', 'pending_review_return_order_number', 'pending_review_mpa_number'));
    }
}
