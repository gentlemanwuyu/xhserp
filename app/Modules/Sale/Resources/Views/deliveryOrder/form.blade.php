@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        #detailTable tbody td{padding: 0;}
        #detailTable tbody tr{height: 40px;}
        #detailTable .layui-input{border: 0;}

    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="delivery_order">
        @if(isset($order_id))
            <input type="hidden" name="order_id" value="{{$order_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">出货单号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入出货单号" class="layui-input" value="{{$order->code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">物流方式</label>
                            <div class="layui-input-block">
                                <select name="delivery_method" lay-search="" lay-filter="delivery_method" lay-verify="required" lay-reqText="请选择物流方式">
                                    <option value="">请选择物流方式</option>
                                    <option value="1">客户自取</option>
                                    <option value="2">送货</option>
                                    <option value="3">快递物流</option>
                                </select>
                            </div>
                        </div>
                        <div id="express_field" class="layui-hide">
                            <div class="layui-form-item">
                                <label class="layui-form-label required">快递公司</label>
                                <div class="layui-input-block">
                                    <select name="express_id" lay-search="" lay-verify="required" lay-reqText="请选择快递公司">
                                        <option value="">请选择快递公司</option>
                                        @foreach($expresses as $express)
                                            <option value="{{$express->id}}">{{$express->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item layui-hide" pane="">
                                <label class="layui-form-label">是否代收</label>
                                <div class="layui-input-block" style="display: flex;">
                                    <input type="checkbox" name="is_collected" lay-skin="switch" lay-text="是|否" value="1">
                                    <input type="text" name="collected_amount" placeholder="代收金额" class="erp-after-switch-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">地址</label>
                            <div class="layui-input-block">
                                <input type="text" name="address" lay-verify="required" lay-reqText="请输入地址" class="layui-input" value="{{$customer->full_address}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">收货人</label>
                            <div class="layui-input-block">
                                <input type="text" name="consignee" lay-verify="required" lay-reqText="请输入收货人" class="layui-input" value="">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">联系电话</label>
                            <div class="layui-input-block">
                                <input type="text" name="consignee_phone" lay-verify="required" lay-reqText="请输入联系电话" class="layui-input" value="">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <textarea name="note" class="layui-textarea"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>出货明细</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table" id="detailTable">
                    <thead>
                    <tr>
                        <th width="50">序号</th>
                        <th width="150">订单</th>
                        <th>Item</th>
                        <th>品名</th>
                        <th width="50">单位</th>
                        <th width="100">数量</th>
                        <th width="100">单价</th>
                        <th width="100">总价</th>
                        <th width="60">操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="layui-card-body">
                <button type="button" class="layui-btn layui-btn-normal" lay-event="addItem">增行</button>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="delivery_order">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var orders = <?= json_encode($orders); ?>
                ,contacts = <?= json_encode($customer->contacts); ?>
                ,expresses = <?= json_encode($expresses); ?>;
        layui.extend({
            autocomplete: '/assets/layui-autocomplete/autocomplete'
        }).use(['form', 'autocomplete'], function () {
            var form = layui.form
                    ,autocomplete = layui.autocomplete
                    // 监听商品选择框
                    ,listenSelectOrder = function () {
                form.on('select(order)', function(data){
                    var $td = $(data.elem).parent('td')
                            ,flag = $td.parent('tr').attr('data-flag');
                    if (data.value) {
                        var html = '';
                        html += '<select name="items[' + flag + '][item_id]" lay-filter="item" lay-verify="required" lay-reqText="请选择Item">';
                        html += '<option value="">请选择Item</option>';
                        $.each(orders[data.value]['pis'], function (_, item) {
                            html += '<option value="' + item.id + '">' + item.title + '</option>';
                        });
                        html += '</select>';
                        $td.siblings('td[erp-col=item]').html(html);
                        listenSelectItem(orders[data.value]['pis']);
                    }else {
                        $td.siblings('td[erp-col=item]').html('');
                    }
                    form.render('select', 'delivery_order');
                });
            }
                    ,listenSelectItem = function (items) {
                form.on('select(item)', function(data){
                    var $td = $(data.elem).parent('td')
                            ,$titleInput = $td.siblings('td[erp-col=title]').find('input');
                    if (data.value) {
                        $titleInput.val(items[data.value]['title']);
                    }else {
                        $titleInput.val('');
                    }
                });
            }
                    ,listenIsCollected = function () {
                form.on('switch(is_collected)', function (data) {
                    var $collected_amount = $('input[name=collected_amount]');
                    if (data.elem.checked) {
                        $collected_amount.removeClass('layui-hide').val('');
                    }else {
                        $collected_amount.addClass('layui-hide').val('');
                    }
                })
            };

            $('button[lay-event=addItem]').on('click', function () {
                var $body = $('#detailTable').find('tbody')
                        ,html = ''
                        ,flag = Date.now();
                html += '<tr data-flag="' + flag + '">';
                // 序号
                html += '<td erp-col="index">';
                html += $body.children('tr').length + 1;
                html += '</td>';
                // 选择订单
                html += '<td>';
                html += '<select name="items[' + flag + '][order_id]" lay-filter="order" lay-search="" lay-verify="required" lay-reqText="请选择订单">';
                html += '<option value="">请选择订单</option>';
                $.each(orders, function (_, order) {
                    html += '<option value="' + order.id + '">' + order.code + '</option>';
                });
                html += '</select>';
                html += '</td>';
                // 选择Item
                html += '<td erp-col="item">';
                html += '</td>';
                // 标题
                html += '<td erp-col="title">';
                html += '<input type="text" name="items[' + flag + '][title]" placeholder="品名" lay-verify="required" lay-reqText="请输入品名" class="layui-input">';
                html += '</td>';
                // 单位
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][unit]" placeholder="单位" lay-verify="required" lay-reqText="请输入单位" class="layui-input">';
                html += '</td>';
                // 数量
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][quantity]" lay-filter="quantity" placeholder="数量" lay-verify="required" lay-reqText="请输入数量" class="layui-input">';
                html += '</td>';
                // 单价
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][price]" lay-filter="price" placeholder="单价" lay-verify="required" lay-reqText="请输入单价" class="layui-input">';
                html += '</td>';
                // 总价
                html += '<td erp-col="amount">';
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>';
                html += '</td>';
                html += '</tr>';

                $body.append(html);
                form.render();
                listenSelectOrder();
            });

            // 监听物流方式
            form.on('select(delivery_method)', function (data) {
                var $express_field = $('#express_field');
                if (3 == data.value) {
                    var html = '';
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label required">快递公司</label>';
                    html += '<div class="layui-input-block">';
                    html += '<select name="express_id" lay-search="" lay-verify="required" lay-reqText="请选择快递公司">';
                    html += '<option value="">请选择快递公司</option>';
                    $.each(expresses, function (_, express) {
                        html += '<option value="' + express.id + '">' + express.name + '</option>';
                    });
                    html += '</select>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="layui-form-item" pane="">';
                    html += '<label class="layui-form-label">是否代收</label>';
                    html += '<div class="layui-input-block" style="display: flex;">';
                    html += '<input type="checkbox" name="is_collected" lay-filter="is_collected" lay-skin="switch" lay-text="是|否" value="1">';
                    html += '<input type="text" name="collected_amount" placeholder="代收金额" class="erp-after-switch-input layui-hide">';
                    html += '</div>';
                    html += '</div>';
                    $express_field.removeClass('layui-hide').html(html);
                    form.render();
                    listenIsCollected();
                }else {
                    $express_field.addClass('layui-hide').html('');
                }
            });

            // 收货人autocomplete效果
            autocomplete.init({
                elem:"input[name=consignee]",
                delay:200,
                callback:{
                    data:function(val,render){
                        var data = [];
                        $.each(contacts, function (_, contact) {
                            data.push({
                                title: contact.name + (contact.position ? '(' + contact.position + ')' : '')
                                ,value: contact.name
                                ,id: contact.id
                                ,phone: contact.phone
                            });
                        });
                        render(data);
                    }
                    ,selected:function(data){
                        $('input[name=consignee_phone]').val(data.phone);
                    }
                    ,changed: function () {
                        $('input[name=consignee_phone]').val('');
                    }
                }
            });
        });
    </script>
@endsection