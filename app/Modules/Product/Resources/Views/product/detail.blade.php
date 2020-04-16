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
@endsection
@section('scripts')
    <script>
        var openLog = function (logs) {
            var html = '', index = 1;
            html += '<div class="layui-row" style="padding: 15px;">';
            html += '<div class="layui-col-xs12">';
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
                move: '.layui-layer-content',
                area: '800px',
                content: html,
                success: function () {
                    layui.use(['table'], function () {
                        layui.table.init('log', {page:true});
                    })
                }
            });
        };
    </script>
@endsection