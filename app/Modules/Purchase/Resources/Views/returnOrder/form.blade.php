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
    <form class="layui-form layui-form-pane" lay-filter="purchaseReturnOrder">
        @if(isset($purchase_order_id))
            <input type="hidden" name="purchase_order_id" value="{{$purchase_order_id}}">
        @elseif(isset($return_purchase_order_id))
            <input type="hidden" name="return_purchase_order_id" value="{{$return_purchase_order_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>订单信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">供应商</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$purchase_order->supplier->name}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">订单号</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$purchase_order->code}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">订单时间</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$purchase_order->created_at}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th width="50">序号</th>
                        <th width="150">商品</th>
                        <th width="150">SKU</th>
                        <th>标题</th>
                        <th width="50">单位</th>
                        <th width="100">数量</th>
                        <th width="100">单价</th>
                        <th width="100">总价</th>
                        <th width="100">已入库数量</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 1;
                    ?>
                    @foreach($purchase_order->items as $item)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$item->product->name}}</td>
                            <td>{{$item->sku->code}}</td>
                            <td>{{$item->title}}</td>
                            <td>{{$item->unit}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->price}}</td>
                            <td>{{$item->quantity * $item->price}}</td>
                            <td>{{$item->entried_quantity}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">退货单号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" placeholder="退货单号" lay-verify="required" lay-reqText="请输入退货单号" class="layui-input"  value="{{$auto_code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">退货方式</label>
                            <div class="layui-input-block">
                                <select name="method" lay-verify="required" lay-reqText="请选择退货方式">
                                    <option value="">请选择退货方式</option>
                                    @foreach(\App\Modules\Purchase\Models\PurchaseReturnOrder::$methods as $method_id => $method_name)
                                        <option value="{{$method_id}}">{{$method_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label required">退货原因</label>
                            <div class="layui-input-block">
                                <textarea name="reason" class="layui-textarea" lay-verify="required" lay-reqText="请输入退货原因"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">出货方式</label>
                            <div class="layui-input-block">
                                <select name="delivery_method" lay-verify="required" lay-reqText="请选择出货方式" lay-filter="deliveryMethod">
                                    <option value="">请选择出货方式</option>
                                    @foreach(\App\Modules\Purchase\Models\PurchaseReturnOrder::$delivery_methods as $method_id => $method_name)
                                        <option value="{{$method_id}}">{{$method_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>退货明细</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table" id="detailTable">
                    <thead>
                    <tr>
                        <th width="50">序号</th>
                        <th width="250" class="required">Item</th>
                        <th>产品</th>
                        <th width="150">SKU</th>
                        <th width="50">单位</th>
                        <th width="100">订单数量</th>
                        <th width="100">已入库数量</th>
                        <th width="100">可退数量</th>
                        <th width="100" class="required">退货数量</th>
                        <th width="100" class="required">实出数量</th>
                        <th width="60">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($purchase_return_order))
                        <?php $i = 1; ?>
                        @foreach($purchase_return_order->items as $item)
                            <?php
                                $purchaseOrderItem = $item->purchaseOrderItem;
                                $purchaseOrder = $orderItem->purchaseOrder;
                            ?>
                            <tr data-flag="{{$item->id}}">
                                <td erp-col="index">{{$i++}}</td>
                                <td erp-col="purchaseOrderItem">
                                    <select name="items[{{$item->id}}][purchase_order_item_id]" lay-filter="purchaseOrderItem" lay-verify="required" lay-reqText="请选择采购订单Item">
                                        <option value="">请选择采购订单Item</option>
                                        @foreach($purchaseOrder->items as $poi)
                                            <option value="{{$poi->id}}" @if($item->purchase_order_item_id == $poi->id) selected @endif>{{$poi->title}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td erp-col="product">{{$purchaseOrderItem->product->name}}</td>
                                <td erp-col="sku">{{$purchaseOrderItem->sku->code}}</td>
                                <td erp-col="unit">{{$purchaseOrderItem->unit}}</td>
                                <td erp-col="orderQuantity">{{$purchaseOrderItem->quantity}}</td>
                                <td erp-col="entriedQuantity">{{$purchaseOrderItem->entried_quantity}}</td>
                                <td erp-col="returnableQuantity">{{$purchaseOrderItem->returnable_quantity}}</td>
                                <td erp-col="quantity">
                                    <input type="text" name="items[{{$item->id}}][quantity]" lay-filter="quantity" placeholder="退货数量" lay-verify="required" lay-reqText="请输入退货数量" class="layui-input" oninput="value=value.replace(/[^\d]/g, '')" value="{{$item->quantity}}">
                                </td>
                                <td erp-col="egressQuantity">
                                    <input type="text" name="items[{{$item->id}}][egress_quantity]" lay-filter="egressQuantity" placeholder="实收数量" lay-verify="required" lay-reqText="请输入实收数量" class="layui-input" value="{{$item->received_quantity}}">
                                </td>
                                <td><button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="layui-card-body">
                <button type="button" class="layui-btn layui-btn-normal" lay-event="addItem">增行</button>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="purchaseReturnOrder">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var items = <?= json_encode($purchase_order->returnable_items); ?>
            ,expresses = <?= json_encode($expresses); ?>
            ,supplier = <?= json_encode($supplier); ?>;
        layui.extend({
            autocomplete: '/assets/layui-autocomplete/autocomplete'
        }).use(['form', 'autocomplete'], function () {
            var form = layui.form
                    ,autocomplete = layui.autocomplete
                    // 检查Item选择框
                    ,checkItemSelect = function () {
                // 判断所有的item选择框的option是否要disabled掉
                $('select[lay-filter=purchaseOrderItem]').each(function (_, select) {
                    var otherSelectedItemIds = [];
                    $('select[lay-filter=purchaseOrderItem]').not(this).each(function (_, other) {
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
                form.render('select', 'purchaseReturnOrder');
            }
                    // 监听Item选择框
                    ,listenSelectItem = function () {
                form.on('select(purchaseOrderItem)', function(data){
                    var $td = $(data.elem).parent('td')
                            ,flag = $td.parent('tr').attr('data-flag');
                    if (data.value) {
                        var purchaseOrderItem = items[data.value];

                        $td.siblings('td[erp-col=product]').html(purchaseOrderItem.product.name);
                        $td.siblings('td[erp-col=sku]').html(purchaseOrderItem.sku.code);
                        $td.siblings('td[erp-col=unit]').html(purchaseOrderItem.unit);
                        $td.siblings('td[erp-col=orderQuantity]').html(purchaseOrderItem.quantity);
                        $td.siblings('td[erp-col=entriedQuantity]').html(purchaseOrderItem.entried_quantity);
                        $td.siblings('td[erp-col=returnableQuantity]').html(purchaseOrderItem.returnable_quantity);

                    }else {
                        $td.siblings('td[erp-col=product]').html('');
                        $td.siblings('td[erp-col=sku]').html('');
                        $td.siblings('td[erp-col=unit]').html('');
                        $td.siblings('td[erp-col=orderQuantity]').html('');
                        $td.siblings('td[erp-col=entriedQuantity]').html('');
                        $td.siblings('td[erp-col=returnableQuantity]').html('');
                    }

                    $td.siblings('td[erp-col=quantity]').find('input[lay-filter=quantity]').val('');
                    $td.siblings('td[erp-col=egressQuantity]').find('input[lay-filter=egressQuantity]').val('');

                    form.render('select', 'purchaseReturnOrder');
                    listenReturnQuantity();
                    checkItemSelect();
                });
            }
                    ,listenReturnQuantity = function () {
                $('input[lay-filter=quantity]').on('keyup', function () {
                    var $this = $(this)
                            ,$td = $this.parent('td');
                    $this.val(this.value);
                    $td.siblings('td[erp-col=egressQuantity]').find('input[lay-filter=egressQuantity]').val(this.value);

                    var purchaseOrderItemId = $td.siblings('td[erp-col=purchaseOrderItem]').find('select[lay-filter=purchaseOrderItem]').val()
                            ,purchaseOrderItem = items[purchaseOrderItemId]
                            ,returnableQuantity = parseInt(purchaseOrderItem.returnable_quantity);
                    if (parseInt(this.value) > returnableQuantity) {
                        layer.msg("退货数量不能大于可退数量", {icon: 5, shift: 6});
                        return false;
                    }
                });
            };

            // 页面初始化绑定事件
            listenSelectItem();
            listenReturnQuantity();
            checkItemSelect();

            form.on('select(deliveryMethod)', function (data) {
                var $deliveryMethodItem = $(data.elem).parents('.layui-form-item')
                        ,$expressItem = $('select[name=express_id]').parents('.layui-form-item')
                        ,$addressItem = $('input[name=address]').parents('.layui-form-item')
                        ,$consigneeItem = $('input[name=consignee]').parents('.layui-form-item')
                        ,$consigneePhoneItem = $('input[name=consignee_phone]').parents('.layui-form-item');
                $expressItem.remove();
                $addressItem.remove();
                $consigneeItem.remove();
                $consigneePhoneItem.remove();
                if (data.value) {
                    var html = '', delivery_method = parseInt(data.value);
                    if (3 == data.value) {
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label required">快递公司</label>';
                        html += '<div class="layui-input-block">';
                        html += '<select name="express_id" lay-verify="required" lay-reqText="请选择快递公司">';
                        html += '<option value="">请选择快递公司</option>';
                        expresses.forEach(function (express) {
                            html += '<option value="' + express.id + '">' + express.name + '</option>';
                        });
                        html += '</select>';
                        html += '</div>';
                        html += '</div>';
                    }
                    if (-1 < [2, 3].indexOf(delivery_method)) {
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label required">地址</label>';
                        html += '<div class="layui-input-block">';
                        html += '<input type="text" name="address" placeholder="地址" class="layui-input"  value="' + supplier.full_address + '" lay-verify="required" lay-reqText="请填写地址">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label required">收货人</label>';
                        html += '<div class="layui-input-block">';
                        html += '<input type="text" name="consignee" placeholder="收货人" class="layui-input" lay-verify="required" lay-reqText="请填写收货人" autocomplete="off">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label required">收货人电话</label>';
                        html += '<div class="layui-input-block">';
                        html += '<input type="text" name="consignee_phone" placeholder="收货人电话" class="layui-input" lay-verify="required" lay-reqText="请填写收货人电话">';
                        html += '</div>';
                        html += '</div>';

                        $deliveryMethodItem.after(html);
                        form.render();

                        // 收货人autocomplete效果
                        autocomplete.init({
                            elem:"input[name=consignee]",
                            delay:200,
                            callback:{
                                data:function(val,render){
                                    var data = [];
                                    $.each(supplier.contacts, function (_, contact) {
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
                    }
                }
            });

            $('button[lay-event=addItem]').on('click', function () {
                var $body = $('#detailTable').find('tbody')
                        ,html = ''
                        ,flag = Date.now();
                html += '<tr data-flag="' + flag + '">';
                // 序号
                html += '<td erp-col="index">';
                html += $body.children('tr').length + 1;
                html += '</td>';
                // 选择Item
                html += '<td erp-col="purchaseOrderItem">';
                html += '<select name="items[' + flag + '][purchase_order_item_id]" lay-filter="purchaseOrderItem" lay-verify="required" lay-reqText="请选择采购订单Item">';
                html += '<option value="">请选择采购订单Item</option>';
                $.each(items, function (_, item) {
                    html += '<option value="' + item.id + '">' + item.title + '</option>';
                });
                html += '</select>';
                html += '</td>';
                // Item信息
                html += '<td erp-col="product"></td>';
                html += '<td erp-col="sku"></td>';
                html += '<td erp-col="unit"></td>';
                html += '<td erp-col="orderQuantity"></td>';
                html += '<td erp-col="entriedQuantity"></td>';
                html += '<td erp-col="returnableQuantity"></td>';
                // 退货数量
                html += '<td erp-col="quantity">';
                html += '<input type="text" name="items[' + flag + '][quantity]" lay-filter="quantity" placeholder="退货数量" lay-verify="required" lay-reqText="请输入退货数量" class="layui-input" oninput="value=value.replace(\/[\^\\\d]/g, \'\')">';
                html += '</td>';
                // 实出数量
                html += '<td erp-col="egressQuantity">';
                html += '<input type="text" name="items[' + flag + '][egress_quantity]" lay-filter="egressQuantity" placeholder="实收数量" lay-verify="required" lay-reqText="请输入实收数量" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>';
                html += '</td>';
                html += '</tr>';

                $body.append(html);
                form.render();
                listenSelectItem();
                checkItemSelect();
            });

            // 提交订单
            form.on('submit(purchaseReturnOrder)', function (form_data) {
                var item_exists = false;
                $.each(form_data.field, function (key, val) {
                    if (new RegExp(/^items\[[\d]+\]\[[\d\D]+\]$/).test(key)) {
                        item_exists = true;
                        return true;
                    }
                });

                if (!item_exists) {
                    layer.msg("请添加退货明细再提交", {icon:2});
                    return false;
                }

                layer.confirm("采购退货单提交后不可修改，确认要提交吗？", {icon: 3, title:"确认"}, function (index) {
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('purchase::returnOrder.save')}}",
                        data: form_data.field,
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("退货单保存成功", {icon: 1, time: 2000}, function () {
                                    location.reload();
                                });
                            } else {
                                layer.msg("退货单保存失败:"+data.msg, {icon: 2, time: 2000});
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
        });
    </script>
@endsection