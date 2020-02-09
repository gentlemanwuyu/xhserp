@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="code" placeholder="出货单编号" class="layui-input">
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
                    @foreach(\App\Modules\Sale\Models\DeliveryOrder::$statuses as $status_id => $status_name)
                        <option value="{{$status_id}}">{{$status_name}}</option>
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
                <input type="text" name="created_at_between" placeholder="创建时间" class="layui-input">
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
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
                url: "{{route('sale::deliveryOrder.paginate')}}",
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
                        {field: 'code', title: '出货单编号', width: 200, align: 'center', fixed: 'left'},
                        {field: 'customer_name', title: '客户', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.customer.name;
                        }},
                        {field: 'status_name', title: '状态', width: 100, align: 'center', fixed: 'left'},
                        {field: 'delivery_method_name', title: '出货方式', width: 100, align: 'center'},
                        {field: 'creator', title: '创建人', width: 100, align: 'center', templet: function (d) {
                            return d.user ? d.user.name : '';
                        }},
                        {field: 'detail', title: '出货明细', width: 560, align: 'center', templet: function (d) {
                            var html = '';
                            d.items.forEach(function (item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px;">' + item.order.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 200px;">' + item.order_item.title + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.order_item.sku.stock + '</li>';
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
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">订单编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 200px; text-align: center;">Item</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">库存数量</li>';
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
                        actions.push({
                            title: "详情",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('sale::deliveryOrder.detail')}}?delivery_order_id=" + data.id + '&source=warehouse', '出货单详情[' + data.id + ']');
                            }
                        });

                        if (1 == data.status) {
                            actions.push({
                                title: "完成",
                                event: function () {
                                    layer.confirm("确认要已完成出货？", {icon: 3, title:"确认"}, function (index) {
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('warehouse::egress.finish')}}",
                                            data: {delivery_order_id: data.id},
                                            success: function (res) {
                                                layer.close(load_index);
                                                if ('success' == res.status) {
                                                    layer.msg("完成出货", {icon: 1, time: 2000}, function(){
                                                        table.render(tableOpts);
                                                    });
                                                } else {
                                                    layer.msg("完成出货失败:" + res.msg, {icon: 2, time: 2000});
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