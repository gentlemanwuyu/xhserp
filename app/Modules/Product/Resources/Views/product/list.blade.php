@extends('layouts.default')
@section('css')
    <style>
        th[data-field=sku_list] .layui-table-cell{height: 38px;padding-top: 5px; padding-bottom: 5px;}
        th[data-field=sku_list], td[data-field=sku_list]{padding: 0!important;}
        td[data-field=sku_list] .layui-table-cell{padding: 0!important;}
        td[data-field=sku_list] .layui-table-cell{height: auto;}
    </style>
@endsection
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('product::product.form')}}">添加产品</a>
    <table id="list" class="layui-table"  lay-filter="list">

    </table>
    <script type="text/html" id="action">
        <a class="layui-btn layui-btn-sm" lay-event="inventory">库存</a>
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
                url: "{{route('product::product.paginate')}}",
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
                            {field: 'code', title: '产品编号', width: 160, align: 'center', fixed: 'left'},
                            {field: 'name', title: '品名', width: 160, align: 'center', fixed: 'left'},
                            {field: 'category', title: '分类', width: 160, align: 'center', templet: function (d) {
                                return d.category.name;
                            }},
                            {field: 'sku_list', title: 'SKU列表', width: 560, align: 'center', templet: function (d) {
                                var html = '';
                                d.skus.forEach(function (sku, key) {
                                    if (0 == key) {
                                        html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                    }else {
                                        html += '<ul class="erp-table-list-ul">';
                                    }

                                    var stock = '-', highest_stock = '-', lowest_stock = '-';
                                    if (sku.inventory) {
                                        stock = sku.inventory.stock;
                                        highest_stock = sku.inventory.highest_stock;
                                        lowest_stock = sku.inventory.lowest_stock;
                                    }

                                    html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px;">' + sku.code + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 80px;">' + sku.weight + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 80px;">' + sku.cost_price + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 80px;">' + stock + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 80px;">' + highest_stock + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 80px;">' + lowest_stock + '</li>';
                                    html += '</ul>';
                                });
                                return html;
                            }},
                            {field: 'created_at', title: '创建时间', width: 160, align: 'center'},
                            {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center'},
                            {field: 'action', title: '操作', width: 200, align: 'center', fixed: 'right', toolbar: "#action"}
                        ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=sku_list] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px; text-align: center;">sku编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">重量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">成本价</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">库存</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">最高库存</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">最低库存</li>';
                        html += '</ul>';
                        $('th[data-field=sku_list]').append(html);
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

                if ('inventory' == obj.event) {
                    parent.layui.index.openTabsPage("{{route('warehouse::inventory.form')}}?product_id=" + data.id, '库存管理[' + data.id + ']');
                }else if ('edit' == obj.event) {
                    parent.layui.index.openTabsPage("{{route('product::product.form')}}?product_id=" + data.id, '编辑产品[' + data.id + ']');
                }else if ('delete' == obj.event) {
                    layer.confirm("确认要删除该产品？", {icon: 3, title:"确认"}, function (index) {
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('product::product.delete')}}",
                            data: {product_id: data.id},
                            success: function (data) {
                                layer.close(load_index);
                                if ('success' == data.status) {
                                    layer.msg("产品删除成功", {icon:1});
                                    tableIns.reload();
                                } else {
                                    layer.msg("产品删除失败:"+data.msg, {icon:2});
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