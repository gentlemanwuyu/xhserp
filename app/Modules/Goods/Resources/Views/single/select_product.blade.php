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
    <form class="layui-form" lay-filter="product">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <div id="category_ids_select"></div>
            </div>
            <div class="layui-col-xs2"><input type="text" name="code" placeholder="产品编号" autocomplete="off" class="layui-input"></div>
            <div class="layui-col-xs2"><input type="text" name="name" placeholder="品名" autocomplete="off" class="layui-input"></div>
            <div class="layui-col-xs2">
                <button type="button" class="layui-btn" lay-submit lay-filter="product">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
    <table id="list" class="layui-table"  lay-filter="list"></table>
    <script type="text/html" id="action">
        <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="select">选择</a>
    </script>
@endsection
@section('scripts')
    <script>
        var categories = <?= json_encode($categories); ?>;
        layui.use(['table', 'form'], function () {
            var table = layui.table
                    ,form = layui.form
                    ,listOpts = {
                elem: '#list',
                url: "{{route('goods::single.product_paginate')}}",
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
                        {field: 'code', title: '产品编号', align: 'center'},
                        {field: 'name', title: '品名', align: 'center'},
                        {field: 'category', title: '分类', align: 'center', templet: function (d) {
                            return d.category.name;
                        }},
                        {field: 'sku_list', title: 'SKU列表', width: 650, align: 'center', templet: function (d) {
                            var html = '';
                            d.skus.forEach(function (sku, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px;" title="' + sku.code + '">' + sku.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;" title="' + sku.size + '">' + sku.size + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.model + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.weight + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.cost_price + '</li>';
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
                    if (0 == $('th[data-field=sku_list] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">sku编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">规格</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">型号</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">重量</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">成本价</li>';
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
            }
                    ,listTable = table.render(listOpts);

            // 分类下拉树
            xmSelect.render({
                el: '#category_ids_select',
                name: 'category_ids',
                tips: '产品分类',
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

            // 产品列表搜索监听
            form.on('submit(product)', function (form_data) {
                listOpts.where = $.extend({}, listOpts.where, form_data.field);
                table.render(listOpts);
            });

            table.on('tool(list)', function(obj){
                var data = obj.data;

                if ('select' == obj.event) {
                    parent.layui.index.openTabsPage("{{route('goods::single.form')}}?product_id=" + data.id, '添加商品[单品][' + data.id + ']');
                }
            });
        });
    </script>
@endsection