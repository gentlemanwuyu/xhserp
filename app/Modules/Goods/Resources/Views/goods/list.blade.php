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
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('goods::single.select_product')}}" lay-text="选择产品[单品]">添加单品</a>
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('goods::combo.select_product')}}" lay-text="选择产品[组合]">添加组合</a>
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
                        {field: 'sku_list', title: 'SKU列表', width: 400, align: 'center', templet: function (d) {
                            var html = '';
                            d.skus.forEach(function (sku, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px;">' + sku.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.lowest_price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.msrp + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', align: 'center'},
                        {field: 'action', title: '操作', width: '15%', align: 'center', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=sku_list] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">sku编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">最低售价</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">建议零售价</li>';
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
        });
    </script>
@endsection