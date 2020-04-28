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
                            <?php $supplier = $purchaseOrder->supplier; ?>
                            <td>供应商</td>
                            <td>
                                <a lay-href="{{route('purchase::supplier.detail', ['supplier_id' => $supplier->id])}}" lay-text="供应商详情[{{$supplier->id}}]">{{$supplier->name or ''}}</a>
                            </td>
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
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>出货方式</td>
                            <td>{{$purchase_return_order->delivery_method_name}}</td>
                        </tr>
                        @if(3 == $purchase_return_order->delivery_method)
                            <tr>
                                <td>快递公司</td>
                                <td>{{$purchase_return_order->express->name or ''}}</td>
                            </tr>
                            <tr>
                                <td>物流单号</td>
                                <td>{{$purchase_return_order->track_no}}</td>
                            </tr>
                        @endif
                        @if(in_array($purchase_return_order->delivery_method, [\App\Modules\Purchase\Models\PurchaseReturnOrder::SEND, \App\Modules\Purchase\Models\PurchaseReturnOrder::EXPRESS]))
                            <tr>
                                <td>地址</td>
                                <td>{{$purchase_return_order->address}}</td>
                            </tr>
                            <tr>
                                <td>收货人</td>
                                <td>{{$purchase_return_order->consignee}}</td>
                            </tr>
                            <tr>
                                <td>联系电话</td>
                                <td>{{$purchase_return_order->consignee_phone}}</td>
                            </tr>
                        @endif
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
                    <th>单位</th>
                    <th>订单数量</th>
                    <th>已入库数量</th>
                    <th>退货数量</th>
                    <th>实出数量</th>
                    @if(!isset($source) || 'warehouse' != $source)
                        <th>单价</th>
                        <th>总价</th>
                    @endif
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
                        <td>{{$purchaseOrderItem->unit or ''}}</td>
                        <td>{{$purchaseOrderItem->quantity or ''}}</td>
                        <td>{{$purchaseOrderItem->entried_quantity or ''}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->egress_quantity}}</td>
                        @if(!isset($source) || 'warehouse' != $source)
                            <td>{{$purchaseOrderItem->price or ''}}</td>
                            <td>{{$item->quantity * $purchaseOrderItem->price}}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($source) && 'warehouse' == $source && \App\Modules\Purchase\Models\PurchaseReturnOrder::AGREED == $purchase_return_order->status)
        <div class="layui-row">
            <form class="layui-form">
                <button type="button" class="layui-btn layui-btn-normal" erp-action="egress">出库</button>
            </form>
        </div>
    @endif
@endsection
@section('scripts')
    <script>
        var purchase_return_order = <?= json_encode($purchase_return_order); ?>;
        layui.use(['form'], function () {
            var form = layui.form;

            $('button[erp-action=egress]').on('click', function () {
                if ("{{\App\Modules\Purchase\Models\PurchaseReturnOrder::EXPRESS}}" == purchase_return_order.delivery_method) {
                    layer.prompt({
                        title: '物流单号',
                        value: purchase_return_order.track_no
                    }, function(value, index, elem){
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('warehouse::purchaseReturn.egress')}}",
                            data: {purchase_return_order_id: purchase_return_order.id, track_no: value},
                            success: function (res) {
                                layer.close(load_index);
                                if ('success' == res.status) {
                                    layer.msg("采购退货单出库成功", {icon: 1, time: 2000}, function(){
                                        parent.layui.admin.closeThisTabs();
                                    });
                                } else {
                                    layer.msg("采购退货单出库失败:" + res.msg, {icon: 2, time: 2000});
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
                    layer.confirm("确认要出库吗？", {icon: 3, title:"确认"}, function (index) {
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('warehouse::purchaseReturn.egress')}}",
                            data: {purchase_return_order_id: purchase_return_order.id},
                            success: function (res) {
                                layer.close(load_index);
                                if ('success' == res.status) {
                                    layer.msg("采购退货单出库成功", {icon: 1, time: 2000}, function(){
                                        parent.layui.admin.closeThisTabs();
                                    });
                                } else {
                                    layer.msg("采购退货单出库失败:" + res.msg, {icon: 2, time: 2000});
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
        });
    </script>
@endsection