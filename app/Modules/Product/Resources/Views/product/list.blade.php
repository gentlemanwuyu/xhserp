@extends('layouts.default')
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('product::product.form')}}">添加产品</a>
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
        }).use(['table', 'dropdown'], function () {
            var table = layui.table
                    ,dropdown = layui.dropdown
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
                            {field: 'category', title: '分类', align: 'center', templet: function (d) {
                                return d.category.name;
                            }},
                            {field: 'detail', title: 'SKU列表', width: 640, align: 'center', templet: function (d) {
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
                                    html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.cost_price + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 100px;">' + stock + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 100px;">' + highest_stock + '</li>';
                                    html += '<li class="erp-table-list-li" style="width: 100px;">' + lowest_stock + '</li>';
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
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px; text-align: center;">sku编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">重量</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">成本价</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">库存</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">最高库存</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">最低库存</li>';
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
                            title: "库存管理",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('warehouse::inventory.form')}}?product_id=" + data.id, '库存管理[' + data.id + ']');
                            }
                        });

                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('product::product.form')}}?product_id=" + data.id, '编辑产品[' + data.id + ']');
                            }
                        });

                        actions.push({
                            title: "删除",
                            event: function() {
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

                        return actions;
                    });
                }
            });
        });
    </script>
@endsection