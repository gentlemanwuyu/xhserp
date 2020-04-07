@extends('layouts.default')
@section('content')
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>基本信息</legend>
            </fieldset>
        </div>
        <?php
            $purchaseOrder = $purchase_return_order->purchaseOrder;
        ?>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>退货单号</td>
                            <td>{{$purchase_return_order->code}}</td>
                        </tr>
                        <tr>
                            <td>退货方式</td>
                            <td>{{$purchase_return_order->method_name}}</td>
                        </tr>
                        <tr>
                            <td>退货原因</td>
                            <td>{{$purchase_return_order->reason}}</td>
                        </tr>
                        <tr>
                            <td>状态</td>
                            <td>{{$purchase_return_order->status_name}}</td>
                        </tr>
                    </table>
                </div>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>供应商</td>
                            <td>{{$purchaseOrder->supplier->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>订单编号</td>
                            <td>
                                <a lay-href="{{route('purchase::order.detail', ['order_id' => $purchaseOrder->id])}}" lay-text="采购订单详情[{{$purchaseOrder->id}}]">{{$purchaseOrder->code or ''}}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>下单时间</td>
                            <td>{{$purchaseOrder->created_at or ''}}</td>
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
                    <th>采购订单Item</th>
                    <th>订单数量</th>
                    <th>已入库数量</th>
                    <th>退货数量</th>
                    <th>实出数量</th>
                    <th>单位</th>
                    <th>价格</th>
                    <th>总价</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($purchase_return_order->items as $item)
                    <?php
                        $purchaseOrderItem = $item->purchaseOrderItem;
                    ?>
                    <tr>
                        <td>{{$index++}}</td>
                        <td>{{$purchaseOrderItem->title or ''}}</td>
                        <td>{{$purchaseOrderItem->quantity or ''}}</td>
                        <td>{{$purchaseOrderItem->entried_quantity or ''}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->egress_quantity}}</td>
                        <td>{{$purchaseOrderItem->unit or ''}}</td>
                        <td>{{$purchaseOrderItem->price or ''}}</td>
                        <td>{{$item->quantity * $purchaseOrderItem->price}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection