@extends('layouts.default')
@section('css')
    <style>
        .layui-fluid>.layui-row+.layui-row{margin-top: 30px;}
        #tasks .layuiadmin-big-font{min-height: 36px;}
    </style>
@endsection
@section('content')
    <div class="layui-row layui-col-space15" id="tasks">
        @can('egress_finished')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::egress.index')}}" lay-text="出库管理">
                <div class="layui-card" erp-card-id="delivery_order_number">
                    <div class="layui-card-header">
                        <h3>待出库</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"></p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('entry')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::entry.index')}}" lay-text="入库管理">
                <div class="layui-card" erp-card-id="purchase_order_sku_number">
                    <div class="layui-card-header">
                        <h3>待入库</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"></p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('sale_return_handle')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::saleReturn.index')}}" lay-text="销售退货管理">
                <div class="layui-card" erp-card-id="return_order_number">
                    <div class="layui-card-header">
                        <h3>退货待入库</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"></p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('add_purchase_order')
        <div class="layui-col-xs3">
            <a lay-href="{{route('warehouse::stockout.index')}}" lay-text="备货管理">
                <div class="layui-card" erp-card-id="stockout_sku_number">
                    <div class="layui-card-header">
                        <h3>待备货</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"></p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('review_order')
        <div class="layui-col-xs3">
            <a lay-href="{{route('sale::order.index')}}?status={{\App\Modules\Sale\Models\Order::PENDING_REVIEW}}" lay-text="订单管理">
                <div class="layui-card" erp-card-id="pending_review_order_number">
                    <div class="layui-card-header">
                        <h3>待审核订单</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"></p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('review_return_order')
        <div class="layui-col-xs3">
            <a lay-href="{{route('sale::returnOrder.index')}}?status={{\App\Modules\Sale\Models\ReturnOrder::PENDING_REVIEW}}" lay-text="退货单管理">
                <div class="layui-card" erp-card-id="pending_review_return_order_number">
                    <div class="layui-card-header">
                        <h3>待审核退货单</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"></p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('review_payment_method_application')
        <div class="layui-col-xs3">
            <a lay-href="{{route('sale::paymentMethod.index')}}?status={{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}" lay-text="付款方式申请">
                <div class="layui-card" erp-card-id="pending_review_mpa_number">
                    <div class="layui-card-header">
                        <h3>待审批付款方式</h3>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"></p>
                    </div>
                </div>
            </a>
        </div>
        @endcan
    </div>
    <div class="layui-row">
        <div class="layui-col-xs12">
            <div class="layui-card" id="sales_target">
                <div class="layui-card-header">
                    <h3>业绩目标图</h3>
                </div>
                <div class="layui-card-body">
                    <div class="erp-charts" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-row">
        <div class="layui-col-xs12">
            <div class="layui-card" id="sales_performance">
                <div class="layui-card-header">
                    <h3>销售业绩图</h3>
                </div>
                <div class="layui-card-body">
                    <div class="erp-charts" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var salesPerformanceEcharts = echarts.init($('#sales_performance').find('.erp-charts')[0])
                    ,saleTargetEcharts = echarts.init($('#sales_target').find('.erp-charts')[0]);

            $.get("{{route('index::index.home_data')}}").done(function (data) {
                $tasks = $('#tasks');
                $tasks.find('.layui-card[erp-card-id=delivery_order_number] .layuiadmin-card-list p').html(data['delivery_order_number']);
                $tasks.find('.layui-card[erp-card-id=purchase_order_sku_number] .layuiadmin-card-list p').html(data['purchase_order_sku_number']);
                $tasks.find('.layui-card[erp-card-id=return_order_number] .layuiadmin-card-list p').html(data['return_order_number']);
                $tasks.find('.layui-card[erp-card-id=stockout_sku_number] .layuiadmin-card-list p').html(data['stockout_sku_number']);
                $tasks.find('.layui-card[erp-card-id=pending_review_order_number] .layuiadmin-card-list p').html(data['pending_review_order_number']);
                $tasks.find('.layui-card[erp-card-id=pending_review_return_order_number] .layuiadmin-card-list p').html(data['pending_review_return_order_number']);
                $tasks.find('.layui-card[erp-card-id=pending_review_mpa_number] .layuiadmin-card-list p').html(data['pending_review_mpa_number']);

                saleTargetEcharts.setOption($.extend(true, barDefaultOpts, {
                    legend: {
                        data:[data.sale_target.last_year.name, data.sale_target.this_year.name, data.sale_target.target.name]
                    },
                    xAxis: {
                        type: 'category',
                        data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                    },
                    series: [
                        {
                            name: data.sale_target.last_year.name,
                            type: 'bar',
                            data: data.sale_target.last_year.data
                        }, {
                            name: data.sale_target.this_year.name,
                            type: 'bar',
                            data: data.sale_target.this_year.data
                        }, {
                            name: data.sale_target.target.name,
                            type: 'bar',
                            data: data.sale_target.target.data
                        }
                    ]
                }));

                salesPerformanceEcharts.setOption($.extend(true, barDefaultOpts, {
                    legend: {
                        data:['销售金额', '出货金额', '回款金额']
                    },
                    xAxis: {
                        type: 'category',
                        data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                    },
                    series: [
                        {
                            name: '销售金额',
                            type: 'bar',
                            data: data.sale_performance.month_sale_amounts
                        }, {
                            name: '出货金额',
                            type: 'bar',
                            data: data.sale_performance.month_delivery_amounts
                        }, {
                            name: '回款金额',
                            type: 'bar',
                            data: data.sale_performance.month_collect_amounts
                        }
                    ]
                }));

            });


        });
    </script>
@endsection