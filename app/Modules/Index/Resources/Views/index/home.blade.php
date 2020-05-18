@extends('layouts.default')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="layui-row layui-col-space15" id="tasks">
        @can('egress_finished')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::egress.index')}}" lay-text="出库管理">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h3>待出库</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$delivery_order_number or 0}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('entry')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::entry.index')}}" lay-text="入库管理">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h3>待入库</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$purchase_order_sku_number or 0}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('sale_return_handle')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::saleReturn.index')}}" lay-text="销售退货管理">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h3>退货待入库</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$return_order_number or 0}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('add_purchase_order')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::stockout.index')}}" lay-text="备货管理">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h3>待备货</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$stockout_sku_number or 0}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('review_order')
        <div class="layui-col-xs3">
            <a lay-href="{{route('sale::order.index')}}?status={{\App\Modules\Sale\Models\Order::PENDING_REVIEW}}" lay-text="订单管理">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h3>待审核订单</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$pending_review_order_number or 0}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('review_return_order')
        <div class="layui-col-xs3">
            <a lay-href="{{route('sale::returnOrder.index')}}?status={{\App\Modules\Sale\Models\ReturnOrder::PENDING_REVIEW}}" lay-text="退货单管理">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h3>待审核退货单</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$pending_review_return_order_number or 0}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('review_payment_method_application')
        <div class="layui-col-xs3">
            <a lay-href="{{route('sale::paymentMethod.index')}}?status={{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}" lay-text="付款方式申请">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h3>待审批付款方式</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$pending_review_mpa_number or 0}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
    </div>
@endsection