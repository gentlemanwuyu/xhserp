@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="code" placeholder="产品编号" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="name" placeholder="品名" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <div id="category_ids_select"></div>
            </div>
            <div class="layui-col-xs2">
                <select name="inventory_init">
                    <option value="">期初库存设置</option>
                    <option value="1">是</option>
                    <option value="2">否</option>
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
                @can('add_product')
                <a class="layui-btn layui-btn-normal" lay-href="{{route('product::product.form')}}">添加产品</a>
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
        var categories = <?= json_encode($categories); ?>;
        layui.extend({
            dropdown: '/assets/layui-table-dropdown/dropdown'
        }).use(['table', 'dropdown', 'laydate', 'form'], function () {
            var table = layui.table
                    ,dropdown = layui.dropdown
                    ,laydate = layui.laydate
                    ,form = layui.form
                    ,tableOpts = {
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
                        {field: 'name', title: '品名', width: 200, align: 'center', fixed: 'left'},
                        {field: 'category', title: '分类', width: 120, align: 'center', templet: function (d) {
                            return d.category.name;
                        }},
                        {field: 'detail', title: 'SKU列表', width: 930, align: 'center', templet: function (d) {
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

                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px;" title="' + sku.code + '">' + sku.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;" title="' + sku.size + '">' + sku.size + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.model + '</li>';
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
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">sku编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">规格</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">型号</li>';
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
                        @can('product_detail')
                        actions.push({
                            title: "详情",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('product::product.detail')}}?product_id=" + data.id, '产品详情[' + data.id + ']');
                            }
                        });
                        @endcan

                        @can('set_inventory')
                        actions.push({
                            title: "库存管理",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('warehouse::inventory.form')}}?product_id=" + data.id, '库存管理[' + data.id + ']');
                            }
                        });
                        @endcan

                        @can('edit_product')
                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('product::product.form')}}?product_id=" + data.id, '编辑产品[' + data.id + ']');
                            }
                        });
                        @endcan

                        if (data.deletable) {
                            @can('delete_product')
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
                                                    layer.msg("产品删除成功", {icon: 1, time: 2000}, function () {
                                                        tableIns.reload();
                                                    });
                                                } else {
                                                    layer.msg("产品删除失败:"+data.msg, {icon: 2, time: 2000});
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