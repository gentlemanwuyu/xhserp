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
                            <td>状态</td>
                            <td>{{$return_order->status_name}}</td>
                        </tr>
                    </table>
                </div>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>客户</td>
                            <td>{{$return_order->order->customer->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>负责人</td>
                            <td>{{$return_order->order->customer->manager->name or ''}}</td>
                        </tr>
                    </table>
                </div>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>订单编号</td>
                            <td>
                                <a lay-href="{{route('sale::order.detail', ['order_id' => $return_order->order->id])}}" lay-text="订单详情[{{$return_order->order->id}}]">{{$return_order->order->code or ''}}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>下单时间</td>
                            <td>{{$return_order->order->created_at or ''}}</td>
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
                    <th>单位</th>
                    <th>单价</th>
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
                        <td>{{$item->quantity or ''}}</td>
                        <td>{{$orderItem->unit or ''}}</td>
                        <td>{{$orderItem->price or ''}}</td>
                        <td>{{$item->quantity * $orderItem->price}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
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