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
        @if(isset($customer_id))
            <input type="hidden" name="customer_id" value="{{$customer_id}}">
        @endif
        @if(isset($delivery_order_id))
            <input type="hidden" name="delivery_order_id" value="{{$delivery_order_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">客户</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$customer->name or ''}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">出货单号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" placeholder="出货单号" lay-verify="required" lay-reqText="请输入出货单号" class="layui-input" value="{{isset($delivery_order) ? $delivery_order->code : (empty($auto_code) ? '' : $auto_code)}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">出货方式</label>
                            <div class="layui-input-block">
                                <select name="delivery_method" lay-search="" lay-filter="delivery_method" lay-verify="required" lay-reqText="请选择出货方式">
                                    <option value="">请选择出货方式</option>
                                    @foreach(\App\Modules\Sale\Models\DeliveryOrder::$delivery_methods as $method_id => $method_name)
                                        <option value="{{$method_id}}" @if(isset($delivery_order) && $delivery_order->delivery_method == $method_id) selected @endif>{{$method_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="express_field" @if(!isset($delivery_order) || 3 != $delivery_order->delivery_method) class="layui-hide" @endif>
                            @if(isset($delivery_order) && 3 == $delivery_order->delivery_method)
                                <div class="layui-form-item">
                                    <label class="layui-form-label required">快递公司</label>
                                    <div class="layui-input-block">
                                        <select name="express_id" lay-search="" lay-verify="required" lay-reqText="请选择快递公司">
                                            <option value="">请选择快递公司</option>
                                            @foreach($expresses as $express)
                                                <option value="{{$express->id}}" @if($delivery_order->express_id == $express->id) selected @endif>{{$express->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item" pane="">
                                    <label class="layui-form-label">是否代收</label>
                                    <div class="layui-input-block" style="display: flex;">
                                        <input type="checkbox" name="is_collected" lay-filter="is_collected" lay-skin="switch" lay-text="是|否" value="1" @if(1 == $delivery_order->is_collected) checked @endif>
                                        @if(1 == $delivery_order->is_collected)
                                            <input type="text" name="collected_amount" class="erp-after-switch-input" placeholder="代收金额" lay-verify="required" lay-reqText="请输入代收金额" value="{{$delivery_order->collected_amount}}">
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">地址</label>
                            <div class="layui-input-block">
                                <input type="text" name="address" lay-verify="required" lay-reqText="请输入地址" class="layui-input" value="{{$delivery_order->address or $customer->full_address}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">收货人</label>
                            <div class="layui-input-block">
                                <input type="text" name="consignee" lay-verify="required" lay-reqText="请输入收货人" class="layui-input" value="{{$delivery_order->consignee or ''}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">联系电话</label>
                            <div class="layui-input-block">
                                <input type="text" name="consignee_phone" lay-verify="required" lay-reqText="请输入联系电话" class="layui-input" value="{{$delivery_order->consignee_phone or ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <textarea name="note" class="layui-textarea">{{$delivery_order->note or ''}}</textarea>
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
                        <th width="150" class="required">订单</th>
                        <th class="required">Item</th>
                        <th class="required">品名</th>
                        <th width="50">单位</th>
                        <th width="150" class="required">数量</th>
                        <th width="100">单价</th>
                        <th width="100">总价</th>
                        <th width="60">操作</th>
                    </tr>
                    </thead>
                    <tbody class="items">
                        @if(isset($delivery_order))
                            <?php
                                $index = 1;
                            ?>
                            @foreach($delivery_order->items as $item)
                                <?php
                                    $order_item = $item->orderItem;
                                ?>
                                <tr data-flag="{{$item->id}}">
                                    <td erp-col="index">{{$index++}}</td>
                                    <td erp-col="order">
                                        <select name="items[{{$item->id}}][order_id]" lay-filter="order" lay-search="" lay-verify="required" lay-reqText="请选择订单">
                                            <option value="">请选择订单</option>
                                            @foreach($orders as $order)
                                                <option value="{{$order->id}}" @if($item->order_id == $order->id) selected @endif>{{$order->code}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td erp-col="item">
                                        <select name="items[{{$item->id}}][item_id]" lay-filter="item" lay-verify="required" lay-reqText="请选择Item">
                                            <option value="">请选择Item</option>
                                            @foreach($orders[$item->order_id]->items as $oi)
                                                <option value="{{$oi->id}}" @if($item->order_item_id == $oi->id) selected @endif>{{$oi->title}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td erp-col="title">
                                        <input type="text" name="items[{{$item->id}}][title]" placeholder="品名" lay-verify="required" lay-reqText="请输入品名" class="layui-input" value="{{$item->title or ''}}">
                                    </td>
                                    <td erp-col="unit">{{$order_item->unit or ''}}</td>
                                    <td erp-col="quantity">
                                        <input type="text" name="items[{{$item->id}}][quantity]" lay-filter="quantity" placeholder="待出货数量:{{$order_item->pending_delivery_quantity}}" lay-verify="required" lay-reqText="请输入数量" class="layui-input" value="{{$item->quantity}}">
                                    </td>
                                    <td erp-col="price">{{$order_item->price or ''}}</td>
                                    <td erp-col="amount">{{$order_item->price * $item->quantity}}</td>
                                    <td><button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tr class="erp-total-row">
                        <td erp-col="index">总计</td>
                        <td erp-col="order"></td>
                        <td erp-col="item"></td>
                        <td erp-col="title"></td>
                        <td erp-col="unit"></td>
                        <td erp-col="quantity"></td>
                        <td erp-col="price"></td>
                        <td erp-col="amount">{{isset($delivery_order) ? $delivery_order->total_amount : ''}}</td>
                        <td></td>
                    </tr>
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
                    // 检查Item选择框
                    ,checkItemSelect = function () {
                // 判断所有的item选择框的option是否要disabled掉
                $('select[lay-filter=item]').each(function (_, select) {
                    var otherSelectedItemIds = [];
                    $('select[lay-filter=item]').not(this).each(function (_, other) {
                        var otherSelectedItemId = $(other).val();
                        if (otherSelectedItemId) {
                            otherSelectedItemIds.push(parseInt(otherSelectedItemId));
                        }
                    });

                    $(select).find('option').each(function () {
                        if (this.value && -1 < otherSelectedItemIds.indexOf(parseInt(this.value))) {
                            $(this).prop('disabled', true);
                        }else {
                            $(this).prop('disabled', false);
                        }
                    });
                });
                form.render('select', 'delivery_order');
            }
                    // 监听订单选择框
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
                        listenSelectItem();
                    }else {
                        $td.siblings('td[erp-col=item]').html('');
                    }
                    $td.siblings('td[erp-col=title]').find('input').val('');
                    $td.siblings('td[erp-col=unit]').html('');
                    $td.siblings('td[erp-col=price]').html('');
                    $td.siblings('td[erp-col=quantity]').find('input').val('');
                    $td.siblings('td[erp-col=amount]').html('');
                    form.render('select', 'delivery_order');
                    checkItemSelect();
                });
            }
                    // 监听订单Item选择框
                    ,listenSelectItem = function () {
                form.on('select(item)', function(data){
                    var $td = $(data.elem).parent('td')
                            ,$titleInput = $td.siblings('td[erp-col=title]').find('input')
                            ,$quantityInput = $td.siblings('td[erp-col=quantity]').find('input')
                            ,$unitTd = $td.siblings('td[erp-col=unit]')
                            ,$priceTd = $td.siblings('td[erp-col=price]')
                            ,selectedOrderId = $td.siblings('td[erp-col=order]').find('select').val()
                            ,items = orders[selectedOrderId]['pis'];

                    $quantityInput.val('');
                    if (data.value) {
                        $titleInput.val(items[data.value]['title']);
                        $unitTd.html(items[data.value]['unit']);
                        $priceTd.html(items[data.value]['price']);
                        $quantityInput.attr('placeholder', '待出货数量:' + items[data.value]['pending_delivery_quantity']);
                    }else {
                        $titleInput.val('');
                        $unitTd.html('');
                        $priceTd.html('');
                        $quantityInput.attr('placeholder', '数量');
                    }
                    $td.siblings('td[erp-col=amount]').html('');
                    checkItemSelect();
                });
            }
                    // 监听是否代收开关
                    ,listenIsCollected = function () {
                form.on('switch(is_collected)', function (data) {
                    var $is_collected = $(data.elem);
                    if (data.elem.checked) {
                        var html = '<input type="text" name="collected_amount" class="erp-after-switch-input" placeholder="代收金额" lay-verify="required" lay-reqText="请输入代收金额">';
                        $is_collected.parent().append(html);
                    }else {
                        $is_collected.siblings('input[name=collected_amount]').remove();
                    }
                })
            }
                    // 监听数量输入框
                    ,listenQuantityInput = function () {
                $('input[ lay-filter=quantity]').on('keyup', function () {
                    var $tr = $(this).parents('tr')
                            ,quantity = this.value
                            ,price = $tr.find('td[erp-col=price]').html();
                    if (new RegExp(/^\d{1,}$/).test(quantity) && price && !isNaN(price)) {
                        var amount = parseInt(quantity) * parseFloat(price);
                        $tr.find('td[erp-col=amount]').html(amount);
                    }else {
                        $tr.find('td[erp-col=amount]').html('');
                    }

                    // 计算汇总金额
                    var total_amount = 0;
                    $(this).parents('tbody').find('td[erp-col=amount]').each(function (_, amountTd) {
                        var amount = parseFloat($(amountTd).html());
                        if (!isNaN(amount)) {
                            total_amount += amount;
                        }
                    });
                    $('.erp-total-row').find('td[erp-col=amount]').html(total_amount);
                });
            };

            $('button[lay-event=addItem]').on('click', function () {
                var $body = $('#detailTable').find('tbody.items')
                        ,html = ''
                        ,flag = Date.now();
                html += '<tr data-flag="' + flag + '">';
                // 序号
                html += '<td erp-col="index">';
                html += $body.children('tr').length + 1;
                html += '</td>';
                // 选择订单
                html += '<td erp-col="order">';
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
                html += '<td erp-col="unit">';
                html += '</td>';
                // 数量
                html += '<td erp-col="quantity">';
                html += '<input type="text" name="items[' + flag + '][quantity]" lay-filter="quantity" placeholder="数量" lay-verify="required" lay-reqText="请输入数量" class="layui-input">';
                html += '</td>';
                // 单价
                html += '<td erp-col="price">';
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
                listenQuantityInput();
            });
            listenIsCollected();
            listenSelectOrder();
            listenSelectItem();
            checkItemSelect();
            listenQuantityInput();

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

            // 提交出货单
            form.on('submit(delivery_order)', function (form_data) {
                var item_exists = false;
                $.each(form_data.field, function (key, val) {
                    if (new RegExp(/^items\[[\d]+\]\[[\d\D]+\]$/).test(key)) {
                        item_exists = true;
                        return false;
                    }
                });

                if (!item_exists) {
                    layer.msg("请添加出货明细再提交", {icon: 5, shift: 6});
                    return false;
                }

                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::deliveryOrder.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("出货单保存成功", {icon: 1, time: 2000}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg("出货单保存失败:"+data.msg, {icon: 2, time: 2000});
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
        });
    </script>
@endsection