@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="code" placeholder="商品编号" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="name" placeholder="品名" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <div id="category_ids_select"></div>
            </div>
            <div class="layui-col-xs2">
                <select name="type">
                    <option value="">类型</option>
                    @foreach(\App\Modules\Goods\Models\Goods::$types as $type_id => $type_name)
                        <option value="{{$type_id}}">{{$type_name}}</option>
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
                @can('add_goods')
                <a class="layui-btn layui-btn-normal" lay-href="{{route('goods::single.select_product')}}" lay-text="选择产品[单品]">添加单品</a>
                <a class="layui-btn layui-btn-normal" lay-href="{{route('goods::combo.select_product')}}" lay-text="选择产品[组合]">添加组合</a>
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
                url: "{{route('goods::goods.paginate')}}",
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
                        {field: 'id', title: 'ID', width: '5%', align: 'center'},
                        {field: 'code', title: '商品编号', align: 'center'},
                        {field: 'name', title: '品名', align: 'center'},
                        {field: 'category', title: '分类', align: 'center', templet: function (d) {
                            return d.category.name;
                        }},
                        {field: 'type_name', title: '类型', width: '5%', align: 'center'},
                        {field: 'detail', title: 'SKU列表', width: 500, align: 'center', templet: function (d) {
                            var html = '';
                            d.skus.forEach(function (sku, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var msrp = parseFloat(sku.msrp);
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px;">' + sku.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.lowest_price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + (msrp ? msrp : '') + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.stock + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', align: 'center'},
                        {field: 'action', title: '操作', width: 100, align: 'center', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">sku编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">最低售价</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">建议零售价</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">库存</li>';
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

                        @can('goods_detail')
                        actions.push({
                            title: "详情",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('goods::goods.detail')}}?goods_id=" + data.id, '商品详情[' + data.id + ']');
                            }
                        });
                        @endcan

                        @can('edit_goods')
                        if (1 == data.type) {
                            actions.push({
                                title: "编辑",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('goods::single.form')}}?goods_id=" + data.id, '编辑单品[' + data.id + ']');
                                }
                            });
                        }else if (2 == data.type) {
                            actions.push({
                                title: "编辑",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('goods::combo.form')}}?goods_id=" + data.id, '编辑组合[' + data.id + ']');
                                }
                            });
                        }
                        @endcan

                        @can('delete_goods')
                        actions.push({
                            title: "删除",
                            event: function() {
                                layer.confirm("确认要删除该商品？", {icon: 3, title:"确认"}, function (index) {
                                    layer.close(index);
                                    var deleteUrl;
                                    if (1 == data.type) {
                                        deleteUrl = "{{route('goods::single.delete')}}";
                                    }else if (2 == data.type) {
                                        deleteUrl = "{{route('goods::combo.delete')}}";
                                    }else {
                                        layer.msg("程序出错，请联系开发人员。", {icon: 5, shift: 6});
                                        return false;
                                    }

                                    var load_index = layer.load();
                                    $.ajax({
                                        method: "post",
                                        url: deleteUrl,
                                        data: {goods_id: data.id},
                                        success: function (data) {
                                            layer.close(load_index);
                                            if ('success' == data.status) {
                                                layer.msg("商品删除成功", {icon:1});
                                                tableIns.reload();
                                            } else {
                                                layer.msg("商品删除失败:"+data.msg, {icon:2});
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