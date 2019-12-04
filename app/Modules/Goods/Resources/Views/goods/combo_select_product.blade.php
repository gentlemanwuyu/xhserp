@extends('layouts.default')
@section('css')
    <style>
        th[data-field=sku_list] .layui-table-cell{height: 38px;padding-top: 5px; padding-bottom: 5px;}
        th[data-field=sku_list], td[data-field=sku_list]{padding: 0!important;}
        td[data-field=sku_list] .layui-table-cell{padding: 0!important;}
        td[data-field=sku_list] .layui-table-cell{height: auto;}
        .layui-table th, .layui-table tr{text-align: center;}
        table[lay-filter=selected] .layui-form-checkbox[lay-skin=primary]{padding-left: 0}
    </style>
@endsection
@section('content')
    <div class="layui-col-xs6">
        <form class="layui-form">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-xs3">
                    <select name="category_id" lay-filter="category" lay-search="" lay-verify="required" lay-reqText="请选择分类">
                        <option value="">产品分类</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-col-xs3"><input type="text" name="title" placeholder="产品编号" lay-verify="required" autocomplete="off" class="layui-input"></div>
                <div class="layui-col-xs3"><input type="text" name="title" placeholder="品名" lay-verify="required" autocomplete="off" class="layui-input"></div>
                <div class="layui-col-xs3">
                    <button type="button" class="layui-btn" lay-submit lay-filter="product">搜索</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
        <table id="list" lay-filter="list"></table>
    </div>
    <div class="layui-col-xs2" style="text-align: center;">
        <div class="layui-transfer-active" style="margin-top: 150px;">
            <button type="button" class="layui-btn layui-btn-sm layui-btn-primary layui-btn-disabled" data-index="0"><i class="layui-icon layui-icon-next"></i></button>
            <button type="button" class="layui-btn layui-btn-sm layui-btn-primary layui-btn-disabled" data-index="1"><i class="layui-icon layui-icon-prev"></i></button>
        </div>
    </div>
    <div class="layui-col-xs4">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>已选择产品</legend>
        </fieldset>
        <form class="layui-form">
            <table id="selected" lay-filter="selected"></table>
            <button type="button" class="layui-btn layui-btn-normal" lay-submit lay-filter="product">确定</button>
        </form>
    </div>

@endsection
@section('scripts')
    <script>
        layui.use(['table'], function () {
            var table = layui.table
                    ,listOpts = {
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
                        {type: 'checkbox', width: 60, fixed: 'left'},
                        {field: 'id', title: 'ID', width: 60, align: 'center', fixed: 'left'},
                        {field: 'code', title: '产品编号', width: 150, align: 'center', fixed: 'left'},
                        {field: 'name', title: '品名', width: 200, align: 'center', fixed: 'left'},
                        {field: 'category', title: '分类', width: 200, align: 'center', templet: function (d) {
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
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.weight + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + sku.cost_price + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', width: 200, align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', width: 200, align: 'center'}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=sku_list] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 200px; text-align: center;">sku编号</li>';
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
                    ,listTable = table.render(listOpts)
                    ,selectedOpts = {
                elem: '#selected'
                ,cols: [
                    [
                        {type: 'checkbox', width: 60},
                        {field: 'id', title: 'ID', width: 60, align: 'center'},
                        {field: 'code', title: '产品编号', width: 150, align: 'center'},
                        {field: 'name', title: '品名', width: 200, align: 'center'},
                        {field: 'quantity', title: '数量', align: 'center', edit: 'text'}
                    ]
                ]
                ,page: false
                ,data: []
            }
                    ,selectTable = table.render(selectedOpts);

            table.on('checkbox(list)', function (obj) {
                var checkStatus = table.checkStatus('list');
                if (checkStatus.data.length > 0) {
                    $('.layui-transfer-active').find('button[data-index=0]').removeClass('layui-btn-disabled');
                }else {
                    $('.layui-transfer-active').find('button[data-index=0]').addClass('layui-btn-disabled');
                }
            });

            table.on('checkbox(selected)', function (obj) {
                var checkStatus = table.checkStatus('selected');
                if (checkStatus.data.length > 0) {
                    $('.layui-transfer-active').find('button[data-index=1]').removeClass('layui-btn-disabled');
                }else {
                    $('.layui-transfer-active').find('button[data-index=1]').addClass('layui-btn-disabled');
                }
            });

            $('.layui-transfer-active button[data-index=0]').on('click', function () {
                if (!$(this).hasClass('layui-btn-disabled')) {
                    var checkStatus = table.checkStatus('list');
                    selectedOpts.data = table.cache['selected'].concat(checkStatus.data);
                    selectTable.reload(selectedOpts);
                }
            });
        });
    </script>
@endsection