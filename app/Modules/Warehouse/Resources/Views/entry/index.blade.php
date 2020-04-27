@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="sku_code" placeholder="SKU编号" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <div id="category_ids_select"></div>
            </div>
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
        var categories = <?= json_encode($categories); ?>;
        layui.extend({
            dropdown: '/assets/layui-table-dropdown/dropdown'
        }).use(['table', 'dropdown', 'form'], function () {
            var table = layui.table
                    ,dropdown = layui.dropdown
                    ,form = layui.form
                    ,tableOpts = {
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
                            d.pois.forEach(function (order_item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var delivery_date = null == order_item.delivery_date ? '' : order_item.delivery_date;
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px;">' + order_item.purchase_order.supplier.name + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 200px;">' + order_item.purchase_order.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + order_item.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + order_item.pending_entry_quantity + '</li>';
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
                        @can('entry')
                        actions.push({
                            title: "入库",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('warehouse::entry.form')}}?sku_id=" + data.id, 'SKU入库[' + data.code + ']');
                            }
                        });
                        @endcan

                        return actions;
                    });
                }
            }
                    ,tableIns = table.render(tableOpts);

            // 分类下拉树
            xmSelect.render({
                el: '#category_ids_select',
                name: 'category_ids',
                tips: '分类',
                filterable: true,
                searchTips: '搜索...',
                theme:{
                    color: '#5FB878'
                },
                prop: {
                    name: 'name',
                    value: 'id'
                },
                tree: {
                    show: true,
                    showLine: false,
                    strict: false
                },
                height: 'auto',
                data: categories
            });

            form.on('submit(search)', function (form_data) {
                tableOpts.where = form_data.field;
                table.render(tableOpts);
            });
        });
    </script>
@endsection