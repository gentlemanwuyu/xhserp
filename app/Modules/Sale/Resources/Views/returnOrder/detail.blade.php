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
                            <td>退货单号</td>
                            <td>{{$return_order->code}}</td>
                        </tr>
                        <tr>
                            <td>退货方式</td>
                            <td>{{$return_order->method_name}}</td>
                        </tr>
                        <tr>
                            <td>退货原因</td>
                            <td>{{$return_order->reason}}</td>
                        </tr>
                        <tr>
                            <td>创建人</td>
                            <td>{{$return_order->user->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>状态</td>
                            <td>{{$return_order->status_name}}</td>
                        </tr>
                        @if(\App\Modules\Sale\Models\ReturnOrder::ENTRIED == $return_order->status)
                            <tr>
                                <td>处理意见</td>
                                <td>{{$return_order->handleLog->content or ''}}</td>
                            </tr>
                            <tr>
                                <td>处理人</td>
                                <td>{{$return_order->handleLog->user->name or ''}}</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <?php
                    $order = $return_order->order;
                    $customer = $order->customer;
                ?>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>客户</td>
                            <td><a lay-href="{{route('sale::customer.detail', ['customer_id' => $customer->id])}}" lay-text="客户详情[{{$customer->id}}]">{{$customer->name or ''}}</a></td>
                        </tr>
                        <tr>
                            <td>负责人</td>
                            <td>{{$customer->manager->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>订单编号</td>
                            <td>
                                <a lay-href="{{route('sale::order.detail', ['order_id' => $order->id])}}" lay-text="订单详情[{{$order->id}}]">{{$order->code or ''}}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>下单时间</td>
                            <td>{{$order->created_at or ''}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>退货明细</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>订单Item</th>
                    <th>订单数量</th>
                    <th>已出货数量</th>
                    <th>退货数量</th>
                    @if(\App\Modules\Sale\Models\ReturnOrder::ENTRIED == $return_order->status)
                        <th>入库数量</th>
                    @endif
                    <th>单位</th>
                    <th>价格</th>
                    <th>总价</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($return_order->items as $item)
                    <?php
                        $orderItem = $item->orderItem;
                    ?>
                    <tr>
                        <td>{{$index++}}</td>
                        <td>{{$orderItem->title or ''}}</td>
                        <td>{{$orderItem->quantity or ''}}</td>
                        <td>{{$orderItem->deliveried_quantity or ''}}</td>
                        <td>{{$item->quantity}}</td>
                        @if(\App\Modules\Sale\Models\ReturnOrder::ENTRIED == $return_order->status)
                            <td>{{$item->entry_quantity}}</td>
                        @endif
                        <td>{{$orderItem->unit or ''}}</td>
                        <td>{{$orderItem->price or ''}}</td>
                        <td>{{$item->quantity * $orderItem->price}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if(!$return_order->logs->isEmpty())
        <div class="erp-detail">
            <div class="erp-detail-title">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>退货单日志</legend>
                </fieldset>
            </div>
            <div class="erp-detail-content">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>操作</th>
                        <th>内容</th>
                        <th>操作人</th>
                        <th>时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $index = 1; ?>
                    @foreach($return_order->logs as $log)
                        <?php

                        ?>
                        <tr>
                            <td>{{$index++}}</td>
                            <td>{{$log->action_name}}</td>
                            <td>{{$log->content}}</td>
                            <td>{{$log->user->name or ''}}</td>
                            <td>{{$log->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    @if(isset($action) && 'review' == $action)
        <div class="layui-row">
            <form class="layui-form">
                <button type="button" class="layui-btn layui-btn-normal" erp-action="agree">同意</button>
                <button type="button" class="layui-btn layui-btn-danger" erp-action="reject">驳回</button>
            </form>
        </div>
    @endif
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            $('button[erp-action=agree]').on('click', function () {
                layer.confirm("确认要通过该退货单吗？", {icon: 3, title:"确认"}, function (index) {
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('sale::returnOrder.agree')}}",
                        data: {return_order_id: "{{$return_order_id or ''}}"},
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("退货单已通过", {icon: 1, time: 2000}, function(){
                                    parent.layui.admin.closeThisTabs();
                                });
                            } else {
                                layer.msg("退货单审核失败:" + data.msg, {icon: 2, time: 2000});
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

            $('button[erp-action=reject]').on('click', function () {
                layer.prompt({title: '驳回原因'}, function(value, index, elem){
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('sale::returnOrder.reject')}}",
                        data: {return_order_id: "{{$return_order_id or ''}}", 'reason': value},
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("退货单已驳回", {icon: 1, time: 2000}, function(){
                                    parent.layui.admin.closeThisTabs();
                                });
                            } else {
                                layer.msg("退货单审核失败:" + data.msg, {icon: 2, time: 2000});
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
        });
    </script>
@endsection