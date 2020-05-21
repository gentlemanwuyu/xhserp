@extends('layouts.default')
@section('css')
    <style>
        .document{
            width: 210mm;
            height: 140mm;
            background-color: #fff;
            margin: auto;
            padding: 15px;
        }
        h1, h2, p{text-align: center;}
        h1{padding-top: 5px; padding-bottom: 5px;}
        h2{padding-top: 5px; padding-bottom: 5px;}
        p{padding-top: 3px; padding-bottom: 3px;}
    </style>
@endsection
@section('content')
    <?php
        $delivery_order_items = $delivery_order->items;
        $customer = $delivery_order->customer;
    ?>
    <div class="layui-row document">
        <div class="layui-col-xs12" style="position: relative;">
            <span style="position: absolute;right: 0;top: 0;">1/1</span>
            <span style="position: absolute;left: 0;top: 0;">{{$delivery_order->code}}</span>
            <h1>深圳市欣汉生科技有限公司</h1>
            <p>SHENZHEN HANSHENG ELECTR0NIC DEVICE STUFF CO.,LTD</p>
            <p>ADD:深圳市龙岗区龙岗街道龙胜路8号香玉儿工业园10栋3楼 URL:http://www.hspcb168.com</p>
            <p>TEL:0755-84841960 84828209 84826609   FAX:0755-84828709</p>
            <h2>
                送货单
            </h2>
            <div class="layui-row">
                <div class="layui-col-xs4">客户名称:{{$customer->name or ''}}</div>
                <div class="layui-col-xs4">订单编号：MXK2015060320</div>
                <div class="layui-col-xs4">日期:{{\Carbon\Carbon::now()->format('Y/m/d')}}</div>
            </div>
            <div class="layui-row" style="margin-top: 3px; margin-bottom: 3px;">
                <div class="layui-col-xs12" style="position: relative;padding-right: 3em;">
                    <table class="layui-table" style="margin: 0;">
                        <tr>
                            <td width="40">序号</td>
                            <td colspan="3">物料名称及规格</td>
                            <td width="40">单位</td>
                            <td width="60">数量</td>
                            <td width="60">单价</td>
                            <td width="80">金额(17%)</td>
                        </tr>
                        @foreach($delivery_order_items as $index => $doi)
                            <?php $order_item = $doi->orderItem; ?>
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td colspan="3">{{$doi->title or ''}}</td>
                                <td>{{$order_item->unit or ''}}</td>
                                <td>{{$doi->quantity or ''}}</td>
                                <td>{{$order_item->price or ''}}</td>
                                <td>{{price_format($doi->quantity * $order_item->price)}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7">合计人民币(大写){{amount_to_cn(price_format($delivery_order->total_amount))}}</td>
                            <td>{{price_format($delivery_order->total_amount)}}</td>
                        </tr>
                        <tr>
                            <td colspan="8">付款方式：{{$customer->payment_method_name or ''}}</td>
                        </tr>
                    </table>
                    <div style="position: absolute;width: 3em;top: 0;bottom: 0;right: 0; text-align: justify; writing-mode: vertical-rl;text-orientation: mixed; font-size: 0.3em;">
                        第一联:回单（白）第二联:客户（红）第三联:存根（蓝）第四联:仓库（绿）第五联:财务（黄）
                    </div>
                </div>
            </div>
            <div class="layui-row">
                <div class="layui-col-xs6">送货单位(盖章)</div>
                <div class="layui-col-xs6">收货单位(盖章)</div>
            </div>
            <div class="layui-row">
                <div class="layui-col-xs6">及经手人:{{$delivery_order->user->name or ''}}</div>
                <div class="layui-col-xs6">及经手人:</div>
            </div>
        </div>
    </div>
@endsection