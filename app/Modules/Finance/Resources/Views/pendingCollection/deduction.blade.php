@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
    </style>
@endsection
@section('content')
    <div class="layui-card">
        <div class="layui-card-header">
            <h3>收款/退货信息</h3>
        </div>
        <div class="layui-card-body">
            <form class="layui-form layui-form-pane">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">客户名称</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">
                                    @if(\Auth::user()->hasPermissionTo('customer_detail'))
                                        <a lay-href="{{route('sale::customer.detail', ['customer_id' => $customer->id])}}" lay-text="客户详情[{{$customer->id}}]">{{$customer->name}}</a>
                                    @else
                                        {{$customer->name}}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">可抵扣金额</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{price_format($customer->total_remained_amount + $customer->back_amount)}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">结余金额</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{price_format($customer->total_remained_amount)}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">退货金额</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{price_format($customer->back_amount)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(!$collections->isEmpty())
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>收款明细</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table" style="margin: 0;">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>收款时间</th>
                        <th>收款方式</th>
                        <th>币种</th>
                        <th>收款金额</th>
                        <th>剩余金额</th>
                        <th>剩余金额(CNY)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($collections as $collection)
                        <tr>
                            <td>{{$collection->id}}</td>
                            <td>{{$collection->created_at}}</td>
                            <td>{{$collection->method_name}}</td>
                            <td>{{$collection->currency->code or ''}}</td>
                            <td>{{$collection->amount}}</td>
                            <td>{{$collection->remained_amount}}</td>
                            <td>{{price_format($collection->remained_amount * $collection->currency->rate)}}</td>
                        </tr>
                    @endforeach
                    <tr class="erp-total-row">
                        <td>合计</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{price_format($customer->total_remained_amount)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <form class="layui-form layui-form-pane" lay-filter="deduction">
        <input type="hidden" name="customer_id" value="{{$customer_id or ''}}">
        <div class="layui-card" id="unpaidDetailCard">
            <div class="layui-card-header">
                <h3>应收款明细</h3>
            </div>
            <div class="layui-card-body">
                <table id="detail" lay-filter="detail"></table>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="deduction">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var customer = <?= json_encode($customer); ?>;
        layui.use(['form', 'table'], function () {
            var form = layui.form
                    ,table = layui.table
                    ,tableOpts = {
                elem: '#detail'
                ,data: customer.unpaid_items
                ,page: false
                ,totalRow: true
                ,cols: [
                    [
                        {type: 'checkbox', field: 'check', width: 100, totalRowText: '合计'},
                        {field: 'order_code', title: '订单编号', align: 'center'},
                        {field: 'currency_code', title: '币种', width: 100, align: 'center'},
                        {field: 'title', title: '品名', width: 250, align: 'center'},
                        {field: 'order_quantity', title: '订单数量', width: 100, align: 'center'},
                        {field: 'delivery_code', title: '出货单编号', align: 'center'},
                        {field: 'delivery_quantity', title: '出货数量', width: 100, align: 'center'},
                        {field: 'real_quantity', title: '真实数量', width: 100, align: 'center'},
                        {field: 'price', title: '单价', width: 100, align: 'center'},
                        {field: 'cny_price', title: '单价(CNY)', width: 150, align: 'center'},
                        {field: 'cny_amount', title: '应收金额(CNY)', width: 150, align: 'center', totalRow: true},
                        {field: 'delivery_date', title: '出货日期', width: 150, align: 'center', templet: function (d) {
                            return moment(d.delivery_at).format('YYYY-MM-DD');
                        }}
                    ]
                ]
            };

            table.render(tableOpts);

            table.on('checkbox(detail)', function (obj) {
                var checkStatus = table.checkStatus('detail')
                        ,checkedAmount = 0
                        ,total_remained_amount = customer.total_remained_amount
                        ,back_amount = customer.back_amount;
                checkStatus.data.forEach(function (item) {
                    checkedAmount += parseFloat(item.price) * parseInt(item.real_quantity) * parseFloat(item.rate);
                });

                if (checkedAmount > total_remained_amount + back_amount) {
                    layer.msg("选中的明细金额不可大于可抵扣金额", {icon: 5, shift: 6});
                    return false;
                }
            });

            // 提交抵扣明细
            form.on('submit(deduction)', function (form_data) {
                var data = form_data.field;

                var checkStatus = table.checkStatus('detail'), doi_ids = array_column(checkStatus.data, 'delivery_order_item_id');

                if (0 >= doi_ids.length) {
                    layer.msg("请至少勾选一个应收款明细", {icon: 5, shift: 6});
                    return false;
                }

                data.checked_doi_ids = doi_ids;

                layer.confirm("抵扣应收款提交后不可修改，确认要提交吗？", {icon: 3, title:"确认"}, function (index) {
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('finance::pendingCollection.deduct')}}",
                        data: data,
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("抵扣应收款明细保存成功", {icon: 1, time: 2000}, function(){
                                    parent.layui.admin.closeThisTabs();
                                });
                            } else {
                                layer.msg("抵扣应收款明细保存失败:"+data.msg, {icon: 2, time: 2000});
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