@extends('layouts.default')
@section('content')
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>基本信息</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>产品编号</td>
                            <td>{{$product->code}}</td>
                        </tr>
                        <tr>
                            <td>产品名称</td>
                            <td>{{$product->name}}</td>
                        </tr>
                        <tr>
                            <td>分类</td>
                            <td>{{$product->category->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>产品描述</td>
                            <td>{{$product->desc}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>SKU列表</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>SKU编号</th>
                    <th>规格</th>
                    <th>型号</th>
                    <th>重量</th>
                    <th>成本价</th>
                    <th>库存数量</th>
                    <th>最高库存</th>
                    <th>最低库存</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($product->skus as $product_sku)
                    <?php $inventory = $product_sku->inventory; ?>
                    <tr>
                        <td>{{$product_sku->code}}</td>
                        <td>{{$product_sku->size}}</td>
                        <td>{{$product_sku->model}}</td>
                        <td>{{$product_sku->weight}}</td>
                        <td>{{$product_sku->cost_price}}</td>
                        <td>{{$inventory->stock or '-'}}</td>
                        <td>{{$inventory->highest_stock or '-'}}</td>
                        <td>{{$inventory->lowest_stock or '-'}}</td>
                        <td>
                            <button type="button" class="layui-btn layui-btn-xs" onclick="openLog({{json_encode($product_sku->inventoryLogs)}})">查看日志</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>采购记录</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <form class="layui-form" lay-filter="search">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs2">
                        <input type="text" name="purchase_order_code" placeholder="订单编号" class="layui-input">
                    </div>
                    <div class="layui-col-xs2">
                        <select name="supplier_id" lay-search="">
                            <option value="">供应商</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="layui-col-xs2">
                        <input type="text" name="created_at_between" placeholder="创建时间" class="layui-input" autocomplete="off">
                    </div>
                    <div class="layui-col-xs4">
                        <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
            <table id="list" class="layui-table"  lay-filter="list">

            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var openLog = function (logs) {
            var html = '', index = 1;
            html += '<div class="layui-row" style="padding: 15px;">';
            html += '<div class="layui-col-xs12">';
            html += '<h3 style="text-align: center;">SKU库存变动日志</h3>';
            html += '<table id="log" lay-filter="log" style="margin: 0;">';
            html += '<thead>';
            html += '<tr>';
            html += '<th lay-data="{field:\'index\', type:\'numbers\', width: 60}">序号</th>';
            html += '<th lay-data="{field:\'message\', width: 100}">信息</th>';
            html += '<th lay-data="{field:\'content\'}">内容</th>';
            html += '<th lay-data="{field:\'user_name\', width: 100}">操作人</th>';
            html += '<th lay-data="{field:\'created_at\', width: 160}">时间</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            logs.forEach(function (log) {
                html += '<tr>';
                html += '<td>' + (index++) + '</td>';
                html += '<td>' + log.message + '</td>';
                html += '<td>';
                var content = JSON.parse(log.content), contents = [];
                if (content.stock) {
                    var stock_html = '库存:';
                    if (content.stock.old) {
                        stock_html += content.stock.old + ' -> ' + content.stock.new;
                    }else {
                        stock_html += content.stock;
                    }
                    contents.push(stock_html);
                }
                if (content.highest_stock) {
                    var highest_stock_html = '最高库存:';
                    if (content.highest_stock.old) {
                        highest_stock_html += content.highest_stock.old + ' -> ' + content.highest_stock.new;
                    }else {
                        highest_stock_html += content.highest_stock;
                    }
                    contents.push(highest_stock_html);
                }
                if (content.lowest_stock) {
                    var lowest_stock_html = '最低库存:';
                    if (content.lowest_stock.old) {
                        lowest_stock_html += content.lowest_stock.old + ' -> ' + content.lowest_stock.new;
                    }else {
                        lowest_stock_html += content.lowest_stock;
                    }
                    contents.push(lowest_stock_html);
                }
                html += contents.join('');
                html += '</td>';
                html += '<td>' + log.user.name + '</td>';
                html += '<td>' + log.created_at + '</td>';
                html += '</tr>';
            });
            html += '</tbody>';
            html += '</table>';
            html += '</div>';
            html += '</div>';


            layer.open({
                type: 1,
                title: null,
                fix: false,
                move: '.layui-layer-content h3',
                area: '800px',
                content: html,
                success: function () {
                    layui.use(['table'], function () {
                        layui.table.init('log', {page:true});
                    })
                }
            });
        };

        layui.use(['table', 'laydate', 'form'], function () {
            var table = layui.table
                    ,laydate = layui.laydate
                    ,form = layui.form
                    ,tableOpts = {
                elem: '#list',
                url: "{{route('product::product.order_paginate')}}?product_id={{$product_id}}",
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
                        {field: 'sku_code', title: 'SKU编号', width: 160, align: 'center', fixed: 'left', templet: function (d) {
                            return d.sku.code;
                        }},
                        {field: 'purchase_order_code', title: '订单编号', width: 160, align: 'center', fixed: 'left', templet: function (d) {
                            return d.purchase_order.code;
                        }},
                        {field: 'purchase_order_status', title: '订单状态', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.purchase_order.status_name;
                        }},
                        {field: 'supplier_name', title: '供应商', width: 120, align: 'center', fixed: 'left', templet: function (d) {
                            return d.purchase_order.supplier.name;
                        }},
                        {field: 'title', title: '品名', align: 'center', fixed: 'left'},
                        {field: 'unit', title: '单位', width: 60, align: 'center', fixed: 'left'},
                        {field: 'quantity', title: '数量', width: 100, align: 'center', fixed: 'left'},
                        {field: 'price', title: '单价', width: 100, align: 'center', fixed: 'left'},
                        {field: 'amount', title: '金额', width: 120, align: 'center', templet: function (d) {
                            return d.price * d.quantity;
                        }},
                        {field: 'created_at', title: '创建时间', width: 160, align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center'}
                    ]
                ]
            }
                    ,tableIns = table.render(tableOpts);

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