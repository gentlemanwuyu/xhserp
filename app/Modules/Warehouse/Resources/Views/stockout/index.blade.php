@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="code" placeholder="产品编号" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <div id="category_ids_select"></div>
            </div>
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <div class="layui-row layui-col-space15">

        </div>
    </form>
    <table id="list" class="layui-table"  lay-filter="list">

    </table>
@endsection
@section('scripts')
    <script>
        var categories = <?= json_encode($categories); ?>;
        layui.use(['table', 'form'], function () {
            var table = layui.table
                    ,form = layui.form
                    ,tableOpts = {
                elem: '#list',
                url: "{{route('warehouse::stockout.paginate')}}",
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
                        {field: 'code', title: '产品编号', align: 'center', fixed: 'left'},
                        {field: 'name', title: '品名', align: 'center', fixed: 'left'},
                        {field: 'category', title: '分类', align: 'center', templet: function (d) {
                            return d.category.name;
                        }},
                        {field: 'detail', title: 'SKU列表', width: 560, align: 'center', templet: function (d) {
                            var html = '';
                            d.stockout_skus.forEach(function (sku, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px;">' + sku.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + sku.weight + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + sku.cost_price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + sku.stock + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + sku.lowest_stock + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + sku.highest_stock + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', width: 160, align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center'}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 160px; text-align: center;">sku编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">重量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">成本价</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">库存</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">最低库存</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">最高库存</li>';
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