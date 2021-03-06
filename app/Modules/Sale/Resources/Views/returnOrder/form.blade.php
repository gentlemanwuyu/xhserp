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
    <form class="layui-form layui-form-pane" lay-filter="returnOrder">
        @if(isset($order_id))
            <input type="hidden" name="order_id" value="{{$order_id}}">
        @elseif(isset($return_order_id))
            <input type="hidden" name="return_order_id" value="{{$return_order_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>订单信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <?php $customer = $order->customer; ?>
                            <label class="layui-form-label">客户</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span"><a lay-href="{{route('sale::customer.detail', ['customer_id' => $customer->id])}}" lay-text="客户详情[{{$customer->id}}]">{{$customer->name or ''}}</a></span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">订单号</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span"><a lay-href="{{route('sale::order.detail', ['order_id' => $order->id])}}" lay-text="订单详情[{{$order->id}}]">{{$order->code}}</a></span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">订单时间</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$order->created_at}}</span>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <input type="text" name="code" placeholder="退货单号" lay-verify="required" lay-reqText="请输入退货单号" class="layui-input"  value="{{isset($return_order) ? $return_order->code : (empty($auto_code) ? '' : $auto_code)}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">退货方式</label>
                            <div class="layui-input-block">
                                <select name="method" lay-verify="required" lay-reqText="请选择退货方式">
                                    <option value="">请选择退货方式</option>
                                    @foreach(\App\Modules\Sale\Models\ReturnOrder::$methods as $method_id => $method_name)
                                        <option value="{{$method_id}}" @if(isset($return_order) && $return_order->method == $method_id) selected @endif>{{$method_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label required">退货原因</label>
                            <div class="layui-input-block">
                                <textarea name="reason" class="layui-textarea" lay-verify="required" lay-reqText="请输入退货原因">{{$return_order->reason or ''}}</textarea>
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
                        <th>商品</th>
                        <th width="150">SKU</th>
                        <th width="50">单位</th>
                        <th width="100">订单数量</th>
                        <th width="100">已出货数量</th>
                        <th width="100">可退数量</th>
                        <th width="100" class="required">退货数量</th>
                        <th width="100" class="required">实收数量</th>
                        <th width="60">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(isset($return_order))
                            <?php $i = 1; ?>
                            @foreach($return_order->items as $item)
                                <?php
                                    $orderItem = $item->orderItem;
                                    $o = $orderItem->order;
                                ?>
                                <tr data-flag="{{$item->id}}">
                                    <td erp-col="index">{{$i++}}</td>
                                    <td erp-col="orderItem">
                                        <select name="items[{{$item->id}}][order_item_id]" lay-filter="orderItem" lay-verify="required" lay-reqText="请选择订单Item">
                                            <option value="">请选择订单Item</option>
                                            @foreach($o->items as $oi)
                                                <option value="{{$oi->id}}" @if($item->order_item_id == $oi->id) selected @endif>{{$oi->title}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td erp-col="goods">{{$orderItem->goods->name}}</td>
                                    <td erp-col="sku">{{$orderItem->sku->code}}</td>
                                    <td erp-col="unit">{{$orderItem->unit}}</td>
                                    <td erp-col="orderQuantity">{{$orderItem->quantity}}</td>
                                    <td erp-col="deliveriedQuantity">{{$orderItem->deliveried_quantity}}</td>
                                    <td erp-col="returnableQuantity">{{$orderItem->returnable_quantity}}</td>
                                    <td erp-col="quantity">
                                        <input type="text" name="items[{{$item->id}}][quantity]" lay-filter="quantity" placeholder="退货数量" lay-verify="required" lay-reqText="请输入退货数量" class="layui-input" oninput="value=value.replace(/[^\d]/g, '')" value="{{$item->quantity}}">
                                    </td>
                                    <td erp-col="receivedQuantity">
                                        <input type="text" name="items[{{$item->id}}][received_quantity]" lay-filter="receivedQuantity" placeholder="实收数量" lay-verify="required" lay-reqText="请输入实收数量" class="layui-input" value="{{$item->received_quantity}}">
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
        <button type="button" class="layui-btn" lay-submit lay-filter="returnOrder">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var items = <?= json_encode($order->returnable_items); ?>;
        layui.use(['form'], function () {
            var form = layui.form
                    // 检查Item选择框
                    ,checkItemSelect = function () {
                // 判断所有的item选择框的option是否要disabled掉
                $('select[lay-filter=orderItem]').each(function (_, select) {
                    var otherSelectedItemIds = [];
                    $('select[lay-filter=orderItem]').not(this).each(function (_, other) {
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
                form.render('select', 'returnOrder');
            }
                    // 监听Item选择框
                    ,listenSelectItem = function () {
                form.on('select(orderItem)', function(data){
                    var $td = $(data.elem).parent('td')
                            ,flag = $td.parent('tr').attr('data-flag');
                    if (data.value) {
                        var orderItem = items[data.value];

                        $td.siblings('td[erp-col=goods]').html(orderItem.goods.name);
                        $td.siblings('td[erp-col=sku]').html(orderItem.sku.code);
                        $td.siblings('td[erp-col=unit]').html(orderItem.unit);
                        $td.siblings('td[erp-col=orderQuantity]').html(orderItem.quantity);
                        $td.siblings('td[erp-col=deliveriedQuantity]').html(orderItem.deliveried_quantity);
                        $td.siblings('td[erp-col=returnableQuantity]').html(orderItem.returnable_quantity);

                    }else {
                        $td.siblings('td[erp-col=goods]').html('');
                        $td.siblings('td[erp-col=sku]').html('');
                        $td.siblings('td[erp-col=unit]').html('');
                        $td.siblings('td[erp-col=orderQuantity]').html('');
                        $td.siblings('td[erp-col=deliveriedQuantity]').html('');
                        $td.siblings('td[erp-col=returnableQuantity]').html('');
                    }

                    $td.siblings('td[erp-col=quantity]').find('input[lay-filter=quantity]').val('');
                    $td.siblings('td[erp-col=receivedQuantity]').find('input[lay-filter=receivedQuantity]').val('');

                    form.render('select', 'returnOrder');
                    listenReturnQuantity();
                    checkItemSelect();
                });
            }
                    ,listenReturnQuantity = function () {
                $('input[lay-filter=quantity]').on('keyup', function () {
                    var $this = $(this)
                            ,$td = $this.parent('td');
                    $this.val(this.value);
                    $td.siblings('td[erp-col=receivedQuantity]').find('input[lay-filter=receivedQuantity]').val(this.value);

                    var orderItemId = $td.siblings('td[erp-col=orderItem]').find('select[lay-filter=orderItem]').val()
                            ,orderItem = items[orderItemId]
                            ,returnableQuantity = parseInt(orderItem.returnable_quantity);
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
                html += '<td erp-col="orderItem">';
                html += '<select name="items[' + flag + '][order_item_id]" lay-filter="orderItem" lay-verify="required" lay-reqText="请选择订单Item">';
                html += '<option value="">请选择订单Item</option>';
                $.each(items, function (_, item) {
                    html += '<option value="' + item.id + '">' + item.title + '</option>';
                });
                html += '</select>';
                html += '</td>';
                // Item信息
                html += '<td erp-col="goods"></td>';
                html += '<td erp-col="sku"></td>';
                html += '<td erp-col="unit"></td>';
                html += '<td erp-col="orderQuantity"></td>';
                html += '<td erp-col="deliveriedQuantity"></td>';
                html += '<td erp-col="returnableQuantity"></td>';
                // 退货数量
                html += '<td erp-col="quantity">';
                html += '<input type="text" name="items[' + flag + '][quantity]" lay-filter="quantity" placeholder="退货数量" lay-verify="required" lay-reqText="请输入退货数量" class="layui-input" oninput="value=value.replace(\/[\^\\\d]/g, \'\')">';
                html += '</td>';
                // 实收数量
                html += '<td erp-col="receivedQuantity">';
                html += '<input type="text" name="items[' + flag + '][received_quantity]" lay-filter="receivedQuantity" placeholder="实收数量" lay-verify="required" lay-reqText="请输入实收数量" class="layui-input">';
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
            form.on('submit(returnOrder)', function (form_data) {
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

                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::returnOrder.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("退货单保存成功", {icon: 1, time: 2000}, function () {
                                parent.layui.admin.closeThisTabs();
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
    </script>
@endsection