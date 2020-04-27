@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="code" placeholder="订单编号" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <select name="customer_id" lay-search="">
                    <option value="">客户</option>
                    @foreach($customers as $customer)
                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <select name="status">
                    <option value="">状态</option>
                    @foreach(\App\Modules\Sale\Models\Order::$statuses as $status_id => $status_name)
                        <option value="{{$status_id}}">{{$status_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <select name="payment_method">
                    <option value="">付款方式</option>
                    @foreach(\App\Modules\Sale\Models\Customer::$payment_methods as $method_id => $payment_method_name)
                        <option value="{{$method_id}}">{{$payment_method_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <select name="currency_code" lay-search="">
                    <option value="">币种</option>
                    @foreach($currencies as $currency)
                        <option value="{{$currency['code']}}">{{$currency['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <select name="creator_id" lay-search="">
                    <option value="">创建人</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="created_at_between" placeholder="创建时间" class="layui-input" autocomplete="off">
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                @can('add_order')
                <a class="layui-btn layui-btn-normal" lay-href="{{route('sale::order.form')}}">添加订单</a>
                @endcan
            </div>
        </div>
    </form>
    <table id="list" class="layui-table"  lay-filter="list">

    </table>
    <script type="text/html" id="action">
        <div class="urp-dropdown urp-dropdown-table" title="操作">
            <i class="layui-icon layui-icon-more-vertical urp-dropdown-btn"></i>
        </div>
    </script>
@endsection
@section('scripts')
    <script>
        layui.extend({
            dropdown: '/assets/layui-table-dropdown/dropdown'
        }).use(['table', 'dropdown', 'laydate', 'form'], function () {
            var table = layui.table
                    ,dropdown = layui.dropdown
                    ,laydate = layui.laydate
                    ,form = layui.form
                    ,tableOpts = {
                elem: '#list',
                url: "{{route('sale::order.paginate')}}",
                page: true,
                parseData: function (res) {
                    return {
                        "code": 0,
                        "msg": '',
                        "count": res.total,
                        "data": res.data
                    };
                },
                cols: [
                    [
                        {field: 'id', title: 'ID', width: 60, align: 'center', fixed: 'left'},
                        {field: 'code', title: '订单编号', width: 160, align: 'center', fixed: 'left'},
                        {field: 'customer_name', title: '客户', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.customer.name;
                        }},
                        {field: 'status_name', title: '状态', width: 100, align: 'center', fixed: 'left'},
                        {field: 'total_amount', title: '总金额', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            var total_amount = 0;
                            d.items.forEach(function (item, key) {
                                total_amount += item.quantity * item.price;
                            });
                            return total_amount.toFixed(2);
                        }},
                        {field: 'tax_name', title: '税率', width: 100, align: 'center'},
                        {field: 'currency_name', title: '币种', width: 100, align: 'center', templet: function (d) {
                            return d.currency.name;
                        }},
                        {field: 'payment_method_name', title: '付款方式', width: 100, align: 'center'},
                        {field: 'delivery_date', title: '交期', width: 120, align: 'center'},
                        {field: 'creator', title: '创建人', width: 100, align: 'center', templet: function (d) {
                            return d.user ? d.user.name : '';
                        }},
                        {field: 'detail', title: '订单明细', width: 870, align: 'center', templet: function (d) {
                            var html = '';
                            d.items.forEach(function (item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var amount = item.quantity * item.price;
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px;">' + item.goods.name + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;">' + item.sku.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.sku.stock + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.pending_delivery_quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.back_quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + amount.toFixed(2) + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', width: 160, align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center'},
                        {field: 'action', title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">商品</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">SKU</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">库存数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">待出货数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">退货数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">价格</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">金额</li>';
                        html += '</ul>';
                        $('th[data-field=detail]').append(html);
                    }

                    // 修改固定列的各行高度
                    $('.layui-table-fixed .layui-table-header thead tr').each(function () {
                        var header_height = $(this).parents('.layui-table-box').children('.layui-table-header').find('table thead tr').css('height');
                        $(this).css('height', header_height);
                    });

                    $('.layui-table-fixed .layui-table-body tbody tr').each(function () {
                        var $this = $(this)
                                ,data_index = $this.attr('data-index')
                                ,tr_height = $this.parents('.layui-table-box').children('.layui-table-body').find('table tbody tr[data-index=' + data_index + ']').css('height');
                        $(this).css('height', tr_height);
                    });

                    dropdown(res.data,function(data) {
                        var actions = [];

                        @can('order_detail')
                        actions.push({
                            title: "详情",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('sale::order.detail')}}?order_id=" + data.id, '订单详情[' + data.id + ']');
                            }
                        });
                        @endcan

                        if (Order_PENDING_REVIEW == data.status) {
                            @can('review_order')
                            actions.push({
                                title: "审核",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('sale::order.review')}}?order_id=" + data.id + "&action=review", '订单审核[' + data.id + ']');
                                }
                            });
                            @endcan
                        }

                        if (Order_AGREED == data.status || 1 == data.exchange_status) {
                            @can('order_delivery')
                            actions.push({
                                title: "出货",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('sale::deliveryOrder.form')}}?customer_id=" + data.customer_id, '添加出货单[' + data.customer_id + ']');
                                }
                            });
                            @endcan
                        }

                        if (-1 < [Order_AGREED, Order_FINISHED].indexOf(data.status) && data.returnable) {
                            @can('order_return')
                            actions.push({
                                title: "退货",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('sale::returnOrder.form')}}?order_id=" + data.id, '添加退货单[' + data.id + ']');
                                }
                            });
                            @endcan
                        }

                        if (Order_AGREED == data.status && data.cancelable) {
                            @can('cancel_order')
                            actions.push({
                                title: "取消",
                                event: function() {
                                    layer.prompt({title: '取消原因'}, function(value, index, elem){
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('sale::order.cancel')}}",
                                            data: {order_id: data.id, reason: value},
                                            success: function (data) {
                                                layer.close(load_index);
                                                if ('success' == data.status) {
                                                    layer.msg("订单已取消", {icon: 1, time: 2000}, function () {
                                                        table.render(tableOpts);
                                                    });
                                                } else {
                                                    layer.msg("订单取消失败:"+data.msg, {icon: 2, time: 2000});
                                                    return false;
                                                }
                                            },
                                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                layer.close(load_index);
                                                layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon: 2, time: 2000});
                                                return false;
                                            }
                                        });
                                    });
                                }
                            });
                            @endcan
                        }

                        if (-1 < [Order_PENDING_REVIEW, Order_REJECTED].indexOf(data.status)) {
                            @can('edit_order')
                            actions.push({
                                title: "编辑",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('sale::order.form')}}?order_id=" + data.id, '编辑订单[' + data.id + ']');
                                }
                            });
                            @endcan
                        }

                        if (data.deletable) {
                            @can('delete_order')
                            actions.push({
                                title: "删除",
                                event: function() {
                                    layer.confirm("确认要删除该订单？", {icon: 3, title:"确认"}, function (index) {
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('sale::order.delete')}}",
                                            data: {order_id: data.id},
                                            success: function (data) {
                                                layer.close(load_index);
                                                if ('success' == data.status) {
                                                    layer.msg("订单删除成功", {icon: 1, time: 2000}, function () {
                                                        table.render(tableOpts);
                                                    });
                                                } else {
                                                    layer.msg("订单删除失败:"+data.msg, {icon: 2, time: 2000});
                                                    return false;
                                                }
                                            },
                                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                layer.close(load_index);
                                                layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon: 2, time: 2000});
                                                return false;
                                            }
                                        });
                                    });
                                }
                            });
                            @endcan
                        }

                        return actions;
                    });
                }
            };

            table.render(tableOpts);

            laydate.render({
                elem: 'input[name=created_at_between]'
                ,range: true
            });

            form.on('submit(search)', function (form_data) {
                tableOpts.where = form_data.field;
                table.render(tableOpts);
            });
        });
    </script>
@endsection