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
                            <td>客户</td>
                            <td>{{$delivery_order->customer->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>出货单号</td>
                            <td>{{$delivery_order->code or ''}}</td>
                        </tr>
                        <tr>
                            <td>出货方式</td>
                            <td>{{$delivery_order->delivery_method_name}}</td>
                        </tr>
                        @if(3 == $delivery_order->delivery_method)
                            <tr>
                                <td>快递公司</td>
                                <td>{{$delivery_order->express->name or ''}}</td>
                            </tr>
                            <tr>
                                <td>是否代收</td>
                                <td>{{$delivery_order->is_collected_name}}</td>
                            </tr>
                            @if(1 == $delivery_order->is_collected)
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
                    <th>单价</th>
                    <th>总价</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($delivery_order->items as $item)
                    <tr>
                        <td>{{$index++}}</td>
                        <td>{{$item->order->code or ''}}</td>
                        <td>{{$item->orderItem->title or ''}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->orderItem->unit}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->orderItem->price}}</td>
                        <td>{{$item->orderItem->price * $item->quantity}}</td>
                    </tr>    
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection