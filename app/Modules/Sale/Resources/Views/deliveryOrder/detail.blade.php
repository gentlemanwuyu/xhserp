@extends('layouts.default')
@section('content')
    <?php $customer = $delivery_order->customer; ?>
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
                            <td>客户</td>
                            <td>
                                @if(\Auth::user()->hasPermissionTo('customer_detail'))
                                    <a lay-href="{{route('sale::customer.detail', ['customer_id' => $customer->id])}}" lay-text="客户详情[{{$customer->id}}]">{{$customer->name}}</a>
                                @else
                                    {{$customer->name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>出货单号</td>
                            <td>{{$delivery_order->code or ''}}</td>
                        </tr>
                        <tr>
                            <td>出货方式</td>
                            <td>{{$delivery_order->delivery_method_name}}</td>
                        </tr>
                        <tr>
                            <td>状态</td>
                            <td>{{$delivery_order->status_name}}</td>
                        </tr>
                        @if(\App\Modules\Sale\Models\DeliveryOrder::EXPRESS == $delivery_order->delivery_method)
                            <tr>
                                <td>快递公司</td>
                                <td>{{$delivery_order->express->name or ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    物流单号
                                    @can('edit_delivery_order')
                                    <a href="javascript:;" erp-event="edit_track_no">[修改]</a>
                                    @endcan
                                </td>
                                <td>{{$delivery_order->track_no}}</td>
                            </tr>
                            <tr>
                                <td>是否代收</td>
                                <td>{{$delivery_order->is_collected_name}}</td>
                            </tr>
                            @if(YES == $delivery_order->is_collected)
                                <tr>
                                    <td>代收金额</td>
                                    <td>{{$delivery_order->collected_amount}}</td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>地址</td>
                            <td>{{$delivery_order->address}}</td>
                        </tr>
                        <tr>
                            <td>收货人</td>
                            <td>{{$delivery_order->consignee}}</td>
                        </tr>
                        <tr>
                            <td>联系电话</td>
                            <td>{{$delivery_order->consignee_phone}}</td>
                        </tr>
                        <tr>
                            <td>备注</td>
                            <td>{{$delivery_order->note}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>出货明细</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>订单</th>
                    <th>Item</th>
                    <th>品名</th>
                    <th>单位</th>
                    <th>数量</th>
                    @if(!isset($source) || 'warehouse' != $source)
                        <th>单价</th>
                        <th>总价</th>
                        <th>付款方式</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($delivery_order->items as $item)
                    <?php $order = $item->order; ?>
                    <tr>
                        <td>{{$index++}}</td>
                        <td>{{$order->code or ''}}</td>
                        <td>{{$item->orderItem->title or ''}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->orderItem->unit}}</td>
                        <td>{{$item->quantity}}</td>
                        @if(!isset($source) || 'warehouse' != $source)
                            <td>{{$item->orderItem->price}}</td>
                            <td>{{$item->orderItem->price * $item->quantity}}</td>
                            <td>{{$order->payment_method_name}}</td>
                        @endif
                    </tr>    
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($source) && 'warehouse' == $source && \App\Modules\Sale\Models\DeliveryOrder::PENDING_REVIEW == $delivery_order->status)
        <div class="layui-row">
            <form class="layui-form">
                <button type="button" class="layui-btn layui-btn-normal" erp-action="finish">完成</button>
            </form>
        </div>
    @endif
@endsection
@section('scripts')
    <script>
        var delivery_order = <?= json_encode($delivery_order); ?>;
        layui.use(['form'], function () {
            var form = layui.form;

            $('button[erp-action=finish]').on('click', function () {
                if ("{{\App\Modules\Sale\Models\DeliveryOrder::EXPRESS}}" == delivery_order.delivery_method) {
                    layer.prompt({
                        title: '物流单号',
                        value: delivery_order.track_no
                    }, function(value, index, elem){
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('warehouse::egress.finish')}}",
                            data: {delivery_order_id: delivery_order.id, track_no: value},
                            success: function (res) {
                                layer.close(load_index);
                                if ('success' == res.status) {
                                    layer.msg("完成出货", {icon: 1, time: 2000}, function(){
                                        parent.layui.admin.closeThisTabs();
                                    });
                                } else {
                                    layer.msg("完成出货失败:" + res.msg, {icon: 2, time: 2000});
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
                }else {
                    layer.confirm("确认要已完成出货？", {icon: 3, title:"确认"}, function (index) {
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('warehouse::egress.finish')}}",
                            data: {delivery_order_id: delivery_order.id},
                            success: function (res) {
                                layer.close(load_index);
                                if ('success' == res.status) {
                                    layer.msg("完成出货", {icon: 1, time: 2000}, function(){
                                        parent.layui.admin.closeThisTabs();
                                    });
                                } else {
                                    layer.msg("完成出货失败:" + res.msg, {icon: 2, time: 2000});
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
                }
            });

            $('*[erp-event=edit_track_no]').on('click', function () {
                layer.prompt({
                    title: '物流单号',
                    value: delivery_order.track_no
                }, function(value, index, elem){
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('sale::deliveryOrder.edit_track_no')}}",
                        data: {delivery_order_id: delivery_order.id, track_no: value},
                        success: function (res) {
                            layer.close(load_index);
                            if ('success' == res.status) {
                                layer.msg("物流单号编辑成功", {icon: 1, time: 2000}, function(){
                                    location.reload();
                                });
                            } else {
                                layer.msg("物流单号编辑失败:" + res.msg, {icon: 2, time: 2000});
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