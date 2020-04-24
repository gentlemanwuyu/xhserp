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
                            <td>订单号</td>
                            <td>{{$order->code or ''}}</td>
                        </tr>
                        <tr>
                            <td>客户</td>
                            <td>{{$order->customer->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>付款方式</td>
                            <td>{{$order->payment_method_name or ''}}</td>
                        </tr>
                        <tr>
                            <td>税率</td>
                            <td>{{$order->tax_name or ''}}</td>
                        </tr>
                        <tr>
                            <td>币种</td>
                            <td>{{$order->currency->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>交期</td>
                            <td>{{$order->delivery_date or ''}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>订单明细</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>商品</th>
                    <th>商品编号</th>
                    <th>SKU</th>
                    <th>标题</th>
                    <th>单位</th>
                    <th>数量</th>
                    <th>单价</th>
                    <th>总价</th>
                    <th>备注</th>
                    <th>最后更新时间</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{$index++}}</td>
                        <td>{{$item->goods->name or ''}}</td>
                        <td>{{$item->goods->code or ''}}</td>
                        <td>{{$item->sku->code or ''}}</td>
                        <td>{{$item->title or ''}}</td>
                        <td>{{$item->unit or ''}}</td>
                        <td>{{$item->quantity or ''}}</td>
                        <td>{{$item->price or ''}}</td>
                        <td>{{$item->quantity * $item->price}}</td>
                        <td>{{$item->note or ''}}</td>
                        <td>{{$item->updated_at or ''}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <?php $deliveryItems = $order->deliveryItems; ?>
    @if(!$deliveryItems->isEmpty())
        <div class="erp-detail">
            <div class="erp-detail-title">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>出货记录</legend>
                </fieldset>
            </div>
            <div class="erp-detail-content">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>订单Item</th>
                        <th>出货单号</th>
                        <th>出货单状态</th>
                        <th>单位</th>
                        <th>出货数量</th>
                        <th>单价</th>
                        <th>总价</th>
                        <th>出货时间</th>
                        <th>最后更新时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $index = 1; ?>
                    @foreach($deliveryItems as $deliveryItem)
                        <?php
                            $deliveryOrder = $deliveryItem->deliveryOrder;
                            $orderItem = $deliveryItem->orderItem;
                        ?>
                        <tr>
                            <td>{{$index++}}</td>
                            <td>{{$orderItem->title or ''}}</td>
                            <td>{{$deliveryOrder->code or ''}}</td>
                            <td>{{$deliveryOrder->status_name or ''}}</td>
                            <td>{{$orderItem->unit or ''}}</td>
                            <td>{{$deliveryItem->quantity or ''}}</td>
                            <td>{{$orderItem->price or ''}}</td>
                            <td>{{$orderItem->price * $deliveryItem->quantity}}</td>
                            <td>{{$deliveryOrder->finished_at or ''}}</td>
                            <td>{{$deliveryOrder->updated_at or ''}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <?php $returnOrders = $order->returnOrders; ?>
    @if(!$returnOrders->isEmpty())
        <div class="erp-detail">
            <div class="erp-detail-title">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>退货记录</legend>
                </fieldset>
            </div>
            <div class="erp-detail-content">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>退货单号</th>
                        <th>退货方式</th>
                        <th>退货原因</th>
                        <th>状态</th>
                        <th>创建人</th>
                        <th>创建时间</th>
                        <th class="erp-static-table-list" style="width: 650px;">
                            <span>退货明细</span>
                            <ul class="erp-static-table-list-ul">
                                <li class="erp-static-table-list-li erp-static-table-list-li-first" style="width: 250px; text-align: center;">订单Item</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">订单数量</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">退货数量</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">单价</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">金额</li>
                            </ul>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $index = 1; ?>
                    @foreach($returnOrders as $returnOrder)
                        <tr>
                            <td>{{$index++}}</td>
                            <td>{{$returnOrder->code or ''}}</td>
                            <td>{{$returnOrder->method_name}}</td>
                            <td>{{$returnOrder->reason}}</td>
                            <td>{{$returnOrder->status_name or ''}}</td>
                            <td>{{$returnOrder->user->name or ''}}</td>
                            <td>{{$returnOrder->created_at}}</td>
                            <td class="erp-static-table-list">
                                @foreach($returnOrder->items as $k => $returnOrderItem)
                                    <?php
                                        $orderItem = $returnOrderItem->orderItem;
                                    ?>
                                    <ul class="erp-static-table-list-ul @if(0 == $k) erp-static-table-list-ul-first @endif">
                                        <li class="erp-static-table-list-li erp-static-table-list-li-first" style="width: 250px;">{{$orderItem->title or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{$orderItem->quantity or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{$returnOrderItem->quantity or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{$orderItem->price or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{$orderItem->price * $returnOrderItem->quantity}}</li>
                                    </ul>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <div class="layui-row @if(!(isset($action) && 'review' == $action)) layui-hide @endif">
        <form class="layui-form">
            <button type="button" class="layui-btn layui-btn-normal" erp-action="agree">同意</button>
            <button type="button" class="layui-btn layui-btn-danger" erp-action="reject">驳回</button>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            $('button[erp-action=agree]').on('click', function () {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::order.agree')}}",
                    data: {order_id: "{{$order_id or ''}}"},
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("订单已通过", {icon: 1, time: 2000}, function(){
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("订单审核失败:" + data.msg, {icon: 2, time: 2000});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(load_index);
                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                        return false;
                    }
                });
            });

            $('button[erp-action=reject]').on('click', function () {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::order.reject')}}",
                    data: {order_id: "{{$order_id or ''}}"},
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("订单已驳回", {icon: 1, time: 2000}, function(){
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("订单审核失败:" + data.msg, {icon: 2, time: 2000});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(load_index);
                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                        return false;
                    }
                });
            });
        });
    </script>
@endsection