@extends('layouts.default')
@section('content')
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
                url: "{{route('warehouse::entry.paginate')}}",
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
                        {field: 'code', title: 'SKU编号', width: 160, align: 'center', fixed: 'left'},
                        {field: 'product_name', title: '品名', width: 160, align: 'center', fixed: 'left', templet: function (d) {
                            return d.product.name;
                        }},
                        {field: 'category', title: '分类', width: 160, align: 'center', templet: function (d) {
                            return d.product.category.name;
                        }},
                        {field: 'stock', title: '库存', width: 100, align: 'center', templet: function (d) {
                            return d.inventory.stock;
                        }},
                        {field: 'highest_stock', title: '最高库存', width: 100, align: 'center', templet: function (d) {
                            return d.inventory.highest_stock;
                        }},
                        {field: 'lowest_stock', title: '最低库存', width: 100, align: 'center', templet: function (d) {
                            return d.inventory.lowest_stock;
                        }},
                        {field: 'detail', title: '订单明细', width: 720, align: 'center', templet: function (d) {
                            var html = '';
                            d.purchase_order_items.forEach(function (order_item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var delivery_date = null == order_item.delivery_date ? '' : order_item.delivery_date;
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px;">' + order_item.order.supplier.name + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 200px;">' + order_item.order.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + order_item.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + (order_item.quantity - order_item.entried_quantity) + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + moment(order_item.created_at).format('YYYY-MM-DD') + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + delivery_date + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'action', title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px; text-align: center;">供应商</li>';
                        html += '<li class="erp-table-list-li" style="width: 200px; text-align: center;">订单编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">待入库</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">下单时间</li>';
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

                    dropdown(res.data,function(data) {
                        var actions = [];
                        actions.push({
                            title: "入库",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('warehouse::entry.form')}}?sku_id=" + data.id, 'SKU入库[' + data.code + ']');
                            }
                        });

                        return actions;
                    });
                }
            });
        });
    </script>
@endsection