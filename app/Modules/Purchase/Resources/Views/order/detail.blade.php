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
                            <td>供应商</td>
                            <td>{{$order->supplier->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>付款方式</td>
                            <td>{{$order->payment_method_name or ''}}</td>
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
                    <th>产品</th>
                    <th>产品编号</th>
                    <th>SKU</th>
                    <th>标题</th>
                    <th>单位</th>
                    <th>数量</th>
                    <th>单价</th>
                    <th>总价</th>
                    <th>交期</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{$index++}}</td>
                        <td>{{$item->product->name or ''}}</td>
                        <td>{{$item->product->code or ''}}</td>
                        <td>{{$item->sku->code or ''}}</td>
                        <td>{{$item->title or ''}}</td>
                        <td>{{$item->unit or ''}}</td>
                        <td>{{$item->quantity or ''}}</td>
                        <td>{{$item->price or ''}}</td>
                        <td>{{$item->quantity * $item->price}}</td>
                        <td>{{$item->delivery_date or ''}}</td>
                        <td>{{$item->note or ''}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection