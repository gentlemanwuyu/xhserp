<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Index\Models\User;
use Illuminate\Http\Request;
use App\Modules\Index\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Modules\Product\Models\Product;
use App\Modules\Sale\Models\DeliveryOrder;
use App\Modules\Purchase\Models\PurchaseOrder;
use App\Modules\Purchase\Models\PurchaseOrderItem;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\ReturnOrder;
use App\Modules\Sale\Models\PaymentMethodApplication;
use App\Modules\Finance\Models\Collection;

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
        return view('index::index.home');
    }

    public function homeData()
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

        // 销售业绩
        $month_sale_amounts = [];
        $month_delivery_amounts = [];
        $month_collect_amounts = [];
        foreach (range(1, 12) as $key => $month) {
            $carbon = Carbon::create(Carbon::now()->year, $month, 1);
            $start = $carbon->firstOfMonth()->toDateString() . ' 00:00:00';
            $end = $carbon->lastOfMonth()->toDateString() . ' 23:59:59';
            $month_sale_amount = Order::leftJoin('order_items AS oi', 'oi.order_id', '=', 'orders.id')
                ->leftJoin('currencies AS c', 'c.code', '=', 'orders.currency_code')
                ->whereNull('oi.deleted_at')
                ->whereIn('orders.status', [Order::AGREED, Order::FINISHED, Order::CANCELED])
                ->where('orders.created_at', '>=', $start)
                ->where('orders.created_at', '<=', $end)
                ->select(DB::raw('oi.quantity * oi.price * c.rate AS amount'))
                ->pluck('amount')
                ->sum();
            $month_sale_amounts[] = number_format($month_sale_amount, 2, '.', '');
            $month_delivery_amount = DeliveryOrder::leftJoin('delivery_order_items AS doi', 'doi.delivery_order_id', '=', 'delivery_orders.id')
                ->leftJoin('order_items AS oi', 'oi.id', '=', 'doi.order_item_id')
                ->leftJoin('orders AS o', 'o.id', '=', 'oi.order_id')
                ->leftJoin('currencies AS c', 'c.code', '=', 'o.currency_code')
                ->whereNull('o.deleted_at')
                ->whereNull('oi.deleted_at')
                ->whereIn('delivery_orders.status', [DeliveryOrder::FINISHED])
                ->where('delivery_orders.created_at', '>=', $start)
                ->where('delivery_orders.created_at', '<=', $end)
                ->select(DB::raw('doi.quantity * oi.price * c.rate AS amount'))
                ->pluck('amount')
                ->sum();
            $month_delivery_amounts[] = number_format($month_delivery_amount, 2, '.', '');
            $month_collect_amount = Collection::leftJoin('currencies AS c', 'c.code', '=', 'collections.currency_code')
                ->where('collections.created_at', '>=', $start)
                ->where('collections.created_at', '<=', $end)
                ->select(DB::raw('collections.amount * c.rate AS amount'))
                ->pluck('amount')
                ->sum();
            $month_collect_amounts[] = number_format($month_collect_amount, 2, '.', '');
        }

        return response()->json(compact('delivery_order_number', 'purchase_order_sku_number', 'return_order_number', 'stockout_sku_number', 'pending_review_order_number', 'pending_review_return_order_number', 'pending_review_mpa_number', 'month_sale_amounts', 'month_delivery_amounts', 'month_collect_amounts'));
    }
}
