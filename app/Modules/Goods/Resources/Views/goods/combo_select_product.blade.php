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
        <table id="list" class="layui-table" lay-filter="list"></table>
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
            <table class="layui-table" id="selected" lay-filter="selected" style="margin: 15px 0;">
                <thead>
                <tr>
                    <th><input type="checkbox" name="layTableCheckbox" lay-skin="primary" lay-filter="layTableAllChoose"></th>
                    <th width="30">ID</th>
                    <th width="100">产品编号</th>
                    <th width="100">品名</th>
                    <th>数量</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="checkbox" name="layTableCheckbox" lay-skin="primary"></td>
                    <td>20</td>
                    <td>xhsllj011lm</td>
                    <td>流量计011螺母</td>
                    <td style="padding: 0;"><input type="text" name="title" lay-verify="required" autocomplete="off" class="layui-input"></td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="layTableCheckbox" lay-skin="primary"></td>
                    <td>20</td>
                    <td>xhsllj011lm</td>
                    <td>流量计011螺母</td>
                    <td style="padding: 0;"><input type="text" name="title" lay-verify="required" autocomplete="off" class="layui-input"></td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="layTableCheckbox" lay-skin="primary"></td>
                    <td>20</td>
                    <td>xhsllj011lm</td>
                    <td>流量计011螺母</td>
                    <td style="padding: 0;"><input type="text" name="title" lay-verify="required" autocomplete="off" class="layui-input"></td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>

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
            });

            table.on('tool(list)', function(obj){
                var data = obj.data;

                if ('select' == obj.event) {
                    parent.layui.index.openTabsPage("{{route('goods::goods.single_form')}}?product_id=" + data.id, '添加商品[单品][' + data.id + ']');
                }
            });

        });
    </script>
@endsection