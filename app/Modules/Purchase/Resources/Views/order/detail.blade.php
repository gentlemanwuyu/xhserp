@extends('layouts.default')
@section('content')
    <?php
        $purchase_order_currency = $order->currency;
        $supplier = $order->supplier;
    ?>
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
                            <td>供应商</td>
                            <td>
                                @if(\Auth::user()->hasPermissionTo('supplier_detail'))
                                    <a lay-href="{{route('purchase::supplier.detail', ['supplier_id' => $supplier->id])}}" lay-text="供应商详情[{{$supplier->id}}]">{{$supplier->name}}</a>
                                @else
                                    {{$supplier->name}}
                                @endif
                            </td>
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
                            <td>{{$purchase_order_currency->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>订单状态</td>
                            <td>{{$order->status_name or ''}}</td>
                        </tr>
                    </table>
                </div>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>下单时间</td>
                            <td>{{$order->created_at or ''}}</td>
                        </tr>
                        <tr>
                            <td>交期</td>
                            <td>{{$order->delivery_date or ''}}</td>
                        </tr>
                        <tr>
                            <td>创建人</td>
                            <td>{{$order->user->name or ''}}</td>
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
                    <th width="60">序号</th>
                    <th>产品编号</th>
                    <th>产品名称</th>
                    <th>SKU编号</th>
                    <th>标题</th>
                    <th width="60">单位</th>
                    <th width="60">数量</th>
                    <th width="80">待入库数量</th>
                    <th width="60">已退数量</th>
                    <th width="60">单价</th>
                    <th width="80">金额</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{$index++}}</td>
                        <td>{{$item->product->code or ''}}</td>
                        <td>{{$item->product->name or ''}}</td>
                        <td>{{$item->sku->code or ''}}</td>
                        <td>{{$item->title or ''}}</td>
                        <td>{{$item->unit or ''}}</td>
                        <td>{{$item->quantity or ''}}</td>
                        <td>{{$item->pending_entry_quantity or ''}}</td>
                        <td>{{$item->back_quantity or ''}}</td>
                        <td>{{price_format($item->price)}}</td>
                        <td>{{price_format($item->price * $item->quantity)}}</td>
                        <td>{{$item->note or ''}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <?php $entryItems = $order->entryItems; ?>
    @if(!$entryItems->isEmpty())
        <div class="erp-detail">
            <div class="erp-detail-title">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>入库记录</legend>
                </fieldset>
            </div>
            <div class="erp-detail-content">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th width="60">序号</th>
                        <th>Item</th>
                        <th width="100">入库时间</th>
                        <th width="100">操作人</th>
                        <th width="60">单位</th>
                        <th width="100">入库数量</th>
                        <th width="100">真实数量</th>
                        <th width="60">单价</th>
                        <th width="80">金额</th>
                        <th width="80">是否已付款</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $index = 1; ?>
                    @foreach($entryItems as $entry)
                        <?php
                            $purchaseOrderItem = $entry->purchaseOrderItem;
                        ?>
                        <tr>
                            <td>{{$index++}}</td>
                            <td>{{$purchaseOrderItem->title or ''}}</td>
                            <td>{{\Carbon\Carbon::parse($entry->created_at)->toDateString()}}</td>
                            <td>{{$entry->user->name or ''}}</td>
                            <td>{{$purchaseOrderItem->unit or ''}}</td>
                            <td>{{$entry->quantity}}</td>
                            <td>{{$entry->real_quantity}}</td>
                            <td>{{price_format($purchaseOrderItem->price)}}</td>
                            <td>{{price_format($purchaseOrderItem->price * $entry->real_quantity)}}</td>
                            <td>{{$entry->is_paid_name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <?php $purchaseReturnOrders = $order->purchaseReturnOrders; ?>
    @if(!$purchaseReturnOrders->isEmpty())
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
                        <th width="60">序号</th>
                        <th>退货单号</th>
                        <th width="60">退货方式</th>
                        <th>退货原因</th>
                        <th width="60">状态</th>
                        <th width="100">创建人</th>
                        <th width="160">创建时间</th>
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
                    @foreach($purchaseReturnOrders as $purchaseReturnOrder)
                        <tr>
                            <td>{{$index++}}</td>
                            <td>{{$purchaseReturnOrder->code or ''}}</td>
                            <td>{{$purchaseReturnOrder->method_name}}</td>
                            <td>{{$purchaseReturnOrder->reason}}</td>
                            <td>{{$purchaseReturnOrder->status_name or ''}}</td>
                            <td>{{$purchaseReturnOrder->user->name or ''}}</td>
                            <td>{{$purchaseReturnOrder->created_at}}</td>
                            <td class="erp-static-table-list">
                                @foreach($purchaseReturnOrder->items as $k => $purchaseReturnOrderItem)
                                    <?php
                                        $purchaseOrderItem = $purchaseReturnOrderItem->purchaseOrderItem;
                                    ?>
                                    <ul class="erp-static-table-list-ul @if(0 == $k) erp-static-table-list-ul-first @endif">
                                        <li class="erp-static-table-list-li erp-static-table-list-li-first" style="width: 250px;">{{$purchaseOrderItem->title or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{$purchaseOrderItem->quantity or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{$purchaseReturnOrderItem->quantity or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{$purchaseOrderItem->price or ''}}</li>
                                        <li class="erp-static-table-list-li" style="width: 100px;">{{price_format($purchaseOrderItem->price * $purchaseReturnOrderItem->quantity)}}</li>
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
    <?php $purchaseOrderLogs = $order->logs; ?>
    @if(!$purchaseOrderLogs->isEmpty())
        <div class="erp-detail">
            <div class="erp-detail-title">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>订单日志</legend>
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
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $index = 1; ?>
                    @foreach($purchaseOrderLogs as $log)
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
                    url: "{{route('purchase::order.agree')}}",
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
                layer.prompt({title: '驳回原因'}, function(value, index, elem){
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('purchase::order.reject')}}",
                        data: {order_id: "{{$order_id or ''}}", reason: value},
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
        });
    </script>
@endsection