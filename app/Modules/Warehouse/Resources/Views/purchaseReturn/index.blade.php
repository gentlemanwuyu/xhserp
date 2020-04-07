@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="code" placeholder="退货单编号" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <select name="supplier_id" lay-search="">
                    <option value="">供应商</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
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
                url: "{{route('purchase::returnOrder.paginate')}}",
                where: {status: 1},
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
                        {field: 'code', title: '退货单编号', width: 160, align: 'center', fixed: 'left'},
                        {field: 'supplier_name', title: '供应商', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.purchase_order.supplier.name;
                        }},
                        {field: 'purchase_order_code', title: '采购订单编号', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.purchase_order.code;
                        }},
                        {field: 'method_name', title: '退货方式', width: 100, align: 'center', fixed: 'left'},
                        {field: 'reason', title: '退货原因', width: 150, align: 'center'},
                        {field: 'creator', title: '创建人', width: 100, align: 'center', templet: function (d) {
                            return d.user ? d.user.name : '';
                        }},
                        {field: 'detail', title: '退货明细', width: 550, align: 'center', templet: function (d) {
                            var html = '';

                            d.items.forEach(function (item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 250px;">' + item.purchase_order_item.title + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.purchase_order_item.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.purchase_order_item.entried_quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.quantity + '</li>';
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
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 250px; text-align: center;">采购订单Item</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">订单数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">已入库数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">退货数量</li>';
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
                            title: "出库",
                            event: function () {
                                layer.confirm("确认要出库吗？", {icon: 3, title:"确认"}, function (index) {
                                    layer.close(index);
                                    var load_index = layer.load();
                                    $.ajax({
                                        method: "post",
                                        url: "{{route('warehouse::purchaseReturn.egress')}}",
                                        data: {purchase_return_order_id: data.id},
                                        success: function (data) {
                                            layer.close(load_index);
                                            if ('success' == data.status) {
                                                layer.msg("采购退货单出库成功", {icon: 1, time: 2000}, function () {
                                                    location.reload();
                                                });
                                            } else {
                                                layer.msg("采购退货单出库失败:"+data.msg, {icon: 2, time: 2000});
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
                tableOpts.where = $.extend({}, tableOpts.where, form_data.field);
                table.render(tableOpts);
            });
        });
    </script>
@endsection