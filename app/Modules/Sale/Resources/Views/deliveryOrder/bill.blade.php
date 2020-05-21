@extends('layouts.default')
@section('css')
    <style>
        #bills{margin: auto;}
        .bill{
            width: 210mm;
            height: 140mm;
            background-color: #fff;
            padding: 5mm;
            position: relative;
        }
        .page{
            position: absolute;
            right: 5mm;
            bottom: 5mm;
        }
        .bill_no{
            position: absolute;
            right: 5mm;
            top: 5mm;
        }
        h1, h2, p{text-align: center;}
        h1{padding-top: 5px; padding-bottom: 5px; margin-left: 80px;}
        h2{padding-top: 5px; padding-bottom: 5px;}
        p{padding-top: 3px; padding-bottom: 3px; margin-left: 80px;}
        .items td{padding: 6px 10px;}
    </style>
@endsection
@section('content')
    <?php
        $delivery_order_items = $delivery_order->items->all();
        $doi_pieces = array_piece($delivery_order_items, \BILL_A5::PER_PIECE); // 分片
        $page_number = count($doi_pieces);
        $customer = $delivery_order->customer;
    ?>
    <div class="layui-carousel" id="bills">
        <div carousel-item>
            @foreach($doi_pieces as $p_index => $dois)
            <div>
                <div class="layui-row bill">
                    <span class="page">{{$p_index + 1}}/{{$page_number or 1}}</span>
                    <span class="bill_no">NO: {{$delivery_order->code}}</span>
                    <div class="layui-col-xs12">
                        <div id="header" style="position: relative; background: url('{{asset('/assets/images/xhs-logo.png')}}') no-repeat 20px center; background-size: auto 70%;">
                            <h1>深圳市欣汉生科技有限公司</h1>
                            <p>SHENZHEN HANSHENG ELECTR0NIC DEVICE STUFF CO.,LTD</p>
                            <p>ADD:深圳市龙岗区龙岗街道龙胜路8号香玉儿工业园10栋3楼 URL:http://www.hspcb168.com</p>
                            <p>TEL:0755-84841960 84828209 84826609   FAX:0755-84828709</p>
                        </div>
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
                                <table class="layui-table items" style="margin: 0;">
                                    <tr>
                                        <td width="40">序号</td>
                                        <td colspan="3">物料名称及规格</td>
                                        <td width="40">单位</td>
                                        <td width="60">数量</td>
                                        <td width="60">单价</td>
                                        <td width="80">金额(17%)</td>
                                    </tr>
                                    @foreach($dois as $index => $doi)
                                        <?php $order_item = $doi->orderItem; ?>
                                        <tr>
                                            <td>{{$p_index * \BILL_A5::PER_PIECE + $index + 1}}</td>
                                            <td colspan="3">{{$doi->title or ''}}</td>
                                            <td>{{$order_item->unit or ''}}</td>
                                            <td>{{$doi->quantity or ''}}</td>
                                            <td>{{$order_item->price or ''}}</td>
                                            <td>{{price_format($doi->quantity * $order_item->price)}}</td>
                                        </tr>
                                    @endforeach
                                    @if(count($dois) < \BILL_A5::PER_PIECE)
                                        @for($i = count($dois) + 1; $i <= \BILL_A5::PER_PIECE; $i++)
                                            <tr>
                                                <td></td>
                                                <td colspan="3"><div style="height: 20px;"></div></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endfor
                                    @endif
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
            </div>
            @endforeach
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="layui-col-xs12" style="text-align: center">
            <button type="button" class="layui-btn layui-btn-normal" erp-action="preview">打印预览</button>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('/assets/js/LodopFuncs.js')}}"></script>
    <script>
        layui.use('carousel', function(){
            var carousel = layui.carousel;

            carousel.render({
                elem: '#bills'
                ,width: '220mm'
                ,height: '150mm'
                ,arrow: 'always'
                ,autoplay: false
                ,indicator: 'none'
            });

            $('*[erp-action=preview]').on('click', function () {
                var LODOP=getLodop();
                LODOP.PRINT_INIT("delivery_order");
                $('.bill').each(function (index, dom) {
                    if (0 == index) {
                        LODOP.ADD_PRINT_HTM(0, 0, '210mm', '140mm',dom.innerHTML);
                    }else {
                        LODOP.NewPage();
                        LODOP.ADD_PRINT_HTM(0, 0, '210mm', '140mm',dom.innerHTML);
                    }
                });
                LODOP.PREVIEW();
            });
        });
    </script>
@endsection