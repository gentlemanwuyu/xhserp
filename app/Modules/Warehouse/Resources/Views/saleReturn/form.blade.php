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
    <?php
        $order = $return_order->order;
        $customer = $order->customer;
    ?>
    <form class="layui-form layui-form-pane" lay-filter="returnOrder">
        <input type="hidden" name="return_order_id" value="{{$return_order_id}}">
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>退货单信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">客户</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">
                                    @if(\Auth::user()->hasPermissionTo('customer_detail'))
                                        <a lay-href="{{route('sale::customer.detail', ['customer_id' => $customer->id])}}" lay-text="客户详情[{{$customer->id}}]">{{$customer->name}}</a>
                                    @else
                                        {{$customer->name}}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">订单号</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">
                                    @if(\Auth::user()->hasPermissionTo('order_detail'))
                                        <a lay-href="{{route('sale::order.detail', ['order_id' => $order->id])}}" lay-text="订单详情[{{$order->id}}]">{{$order->code}}</a>
                                    @else
                                        {{$order->code}}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">订单时间</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$order->created_at}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">退货单号</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$return_order->code}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">退货时间</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$return_order->created_at}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">退货原因</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$return_order->reason}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label required">处理意见</label>
                            <div class="layui-input-block">
                                <textarea name="handle_suggestion" class="layui-textarea" lay-verify="required" lay-reqText="请输入处理意见"></textarea>
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
                        <th width="250">Item</th>
                        <th>商品</th>
                        <th width="150">SKU</th>
                        <th width="50">单位</th>
                        <th width="100">订单数量</th>
                        <th width="100">已出货数量</th>
                        <th width="100">退货数量</th>
                        <th width="100">实收数量</th>
                        <th width="150">
                            <span class="required">入库数量</span>
                            <a class="layui-btn layui-btn-xs" erp-event="input_all" style="margin-left: 5px;">全部</a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach($return_order->items as $item)
                        <?php
                            $orderItem = $item->orderItem;
                            $o = $orderItem->order;
                        ?>
                        <tr data-flag="{{$item->id}}">
                            <td erp-col="index">{{$i++}}</td>
                            <td erp-col="orderItem">{{$orderItem->title or ''}}</td>
                            <td erp-col="goods">{{$orderItem->goods->name}}</td>
                            <td erp-col="sku">{{$orderItem->sku->code}}</td>
                            <td erp-col="unit">{{$orderItem->unit}}</td>
                            <td erp-col="orderQuantity">{{$orderItem->quantity}}</td>
                            <td erp-col="deliveriedQuantity">{{$orderItem->deliveried_quantity}}</td>
                            <td erp-col="quantity">{{$item->quantity}}</td>
                            <td erp-col="receivedQuantity">{{$item->received_quantity}}</td>
                            <td erp-col="entryQuantity">
                                <input type="text" name="items[{{$item->id}}][entry_quantity]" lay-filter="entryQuantity" placeholder="入库数量" lay-verify="required" lay-reqText="请输入入库数量" class="layui-input" oninput="value=value.replace(/[^\d]/g, '')">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="returnOrder">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var returnOrderItems = <?= json_encode($return_order->indexItems); ?>;
        layui.use(['form'], function () {
            var form = layui.form
                    // 监听入库数量框
                    ,listenEntryQuantity = function () {
                $('input[lay-filter=entryQuantity]').on('keyup', function () {
                    var returnOrderItemId = $(this).parents('tr').attr('data-flag')
                            ,returnOrderItem = returnOrderItems[returnOrderItemId]
                            ,receivedQuantity = parseInt(returnOrderItem.received_quantity);
                    if (parseInt(this.value) > receivedQuantity) {
                        layer.msg("入库数量不能大于实收数量", {icon: 5, shift: 6});
                        return false;
                    }
                });
            };

            // 页面初始化绑定事件
            listenEntryQuantity();

            $('*[erp-event=input_all]').on('click', function () {
                $(this).parents('.layui-table').find('tbody tr').each(function () {
                    var $tr = $(this)
                            ,receivedQuantity = $tr.find('td[erp-col=receivedQuantity]').html();

                    $tr.find('td[erp-col=entryQuantity] input').val(receivedQuantity);
                });
            });

            // 提交订单
            form.on('submit(returnOrder)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('warehouse::saleReturn.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("退货处理成功", {icon: 1, time: 2000}, function () {
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("退货处理失败:"+data.msg, {icon: 2, time: 2000});
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