@extends('layouts.default')
@section('css')
    <style>
        th[data-field=detail] .layui-table-cell{height: 38px;padding-top: 5px; padding-bottom: 5px;}
        th[data-field=detail], td[data-field=detail]{padding: 0!important;}
        td[data-field=detail] .layui-table-cell{padding: 0!important;}
        td[data-field=detail] .layui-table-cell{height: auto;}
    </style>
@endsection
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('purchase::order.form')}}">添加订单</a>
    <table id="list" class="layui-table"  lay-filter="list">

    </table>
    <script type="text/html" id="action">
        <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete">删除</a>
    </script>
@endsection
@section('scripts')
    <script>
        layui.use(['table'], function () {
            var table = layui.table
                    ,tableIns = table.render({
                elem: '#list',
                url: "{{route('purchase::order.paginate')}}",
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
                        {field: 'supplier_name', title: '供应商', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.supplier.name;
                        }},
                        {field: 'payment_method_name', title: '付款方式', width: 100, align: 'center', fixed: 'left'},
                        {field: 'total_amount', title: '总金额', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            var total_amount = 0;
                            d.items.forEach(function (item, key) {
                                total_amount += item.quantity * item.price;
                            });
                            return total_amount.toFixed(2);
                        }},
                        {field: 'detail', title: '订单明细', width: 710, align: 'center', templet: function (d) {
                            var html = '';
                            d.items.forEach(function (item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var amount = item.quantity * item.price;
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px;">' + item.product.name + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;">' + item.sku.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + amount.toFixed(2) + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.delivery_date + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', align: 'center'},
                        {field: 'action', title: '操作', width: 200, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">产品</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">SKU</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">价格</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">金额</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">交期</li>';
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
                }
            });

            table.on('tool(list)', function(obj){
                var data = obj.data;

                if ('edit' == obj.event) {
                    parent.layui.index.openTabsPage("{{route('purchase::order.form')}}?order_id=" + data.id, '编辑订单[' + data.id + ']');
                }else if ('delete' == obj.event) {
                    layer.confirm("确认要删除该订单？", {icon: 3, title:"确认"}, function (index) {
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('purchase::order.delete')}}",
                            data: {order_id: data.id},
                            success: function (data) {
                                layer.close(load_index);
                                if ('success' == data.status) {
                                    layer.msg("订单删除成功", {icon:1});
                                    tableIns.reload();
                                } else {
                                    layer.msg("订单删除失败:"+data.msg, {icon:2});
                                    return false;
                                }
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                layer.close(load_index);
                                layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                                return false;
                            }
                        });
                    });
                }
            });
        });
    </script>
@endsection