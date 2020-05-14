@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
    </style>
@endsection
@section('content')
    <div class="layui-card">
        <div class="layui-card-header">
            <h3>付款/退货信息</h3>
        </div>
        <div class="layui-card-body">
            <form class="layui-form layui-form-pane">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">供应商名称</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">
                                    @if(\Auth::user()->hasPermissionTo('supplier_detail'))
                                        <a lay-href="{{route('purchase::supplier.detail', ['supplier_id' => $supplier->id])}}" lay-text="供应商详情[{{$supplier->id}}]">{{$supplier->name}}</a>
                                    @else
                                        {{$supplier->name}}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">可抵扣金额</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{price_format($supplier->total_remained_amount + $supplier->back_amount)}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">结余金额</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{price_format($supplier->total_remained_amount)}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">退货金额</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{price_format($supplier->back_amount)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(!$payments->isEmpty())
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>付款明细</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table" style="margin: 0;">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>付款时间</th>
                        <th>付款方式</th>
                        <th>币种</th>
                        <th>付款金额</th>
                        <th>剩余金额</th>
                        <th>剩余金额(CNY)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{$payment->id}}</td>
                            <td>{{$payment->created_at}}</td>
                            <td>{{$payment->method_name}}</td>
                            <td>{{$payment->currency->code or ''}}</td>
                            <td>{{$payment->amount}}</td>
                            <td>{{$payment->remained_amount}}</td>
                            <td>{{price_format($payment->remained_amount * $payment->currency->rate)}}</td>
                        </tr>
                    @endforeach
                    <tr class="erp-total-row">
                        <td>合计</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{price_format($supplier->total_remained_amount)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <form class="layui-form layui-form-pane" lay-filter="deduction">
        <input type="hidden" name="supplier_id" value="{{$supplier_id or ''}}">
        <div class="layui-card" id="unpaidDetailCard">
            <div class="layui-card-header">
                <h3>应付款明细</h3>
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
        var supplier = <?= json_encode($supplier); ?>;
        layui.use(['form', 'table'], function () {
            var form = layui.form
                    ,table = layui.table
                    ,tableOpts = {
                elem: '#detail'
                ,data: supplier.unpaid_items
                ,page: false
                ,totalRow: true
                ,cols: [
                    [
                        {type: 'checkbox', field: 'check', width: 100, totalRowText: '合计'},
                        {field: 'purchase_order_code', title: '订单编号', align: 'center'},
                        {field: 'currency_code', title: '币种', width: 100, align: 'center'},
                        {field: 'title', title: '品名', width: 250, align: 'center'},
                        {field: 'order_quantity', title: '订单数量', width: 100, align: 'center'},
                        {field: 'entry_quantity', title: '入库数量', width: 100, align: 'center'},
                        {field: 'real_quantity', title: '真实数量', width: 100, align: 'center'},
                        {field: 'price', title: '单价', width: 100, align: 'center'},
                        {field: 'cny_price', title: '单价(CNY)', width: 150, align: 'center'},
                        {field: 'cny_amount', title: '应付金额(CNY)', width: 150, align: 'center', totalRow: true},
                        {field: 'entry_at', title: '入库时间', width: 150, align: 'center', templet: function (d) {
                            return d.entry_id ? moment(d.entry_at).format('YYYY-MM-DD') : '';
                        }}
                    ]
                ]
            };

            table.render(tableOpts);

            table.on('checkbox(detail)', function (obj) {
                var checkStatus = table.checkStatus('detail')
                        ,checkedAmount = 0
                        ,total_remained_amount = supplier.total_remained_amount
                        ,back_amount = supplier.back_amount;
                checkStatus.data.forEach(function (item) {
                    checkedAmount += parseFloat(item.price) * parseInt(item.real_quantity) * parseFloat(item.rate);
                });

                if (checkedAmount > total_remained_amount + back_amount) {
                    layer.msg("选中的明细金额不可大于付款金额", {icon: 5, shift: 6});
                    return false;
                }
            });

            // 提交抵扣明细
            form.on('submit(deduction)', function (form_data) {
                var data = form_data.field;

                var checkStatus = table.checkStatus('detail'), checked_entry_ids = array_column(checkStatus.data, 'entry_id');

                if (0 >= checked_entry_ids.length) {
                    layer.msg("请至少勾选一个应付款明细", {icon: 5, shift: 6});
                    return false;
                }

                data.checked_entry_ids = checked_entry_ids;

                layer.confirm("抵扣应付款提交后不可修改，确认要提交吗？", {icon: 3, title:"确认"}, function (index) {
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('finance::pendingPayment.deduct')}}",
                        data: data,
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("抵扣应付款明细保存成功", {icon: 1, time: 2000}, function(){
                                    parent.layui.admin.closeThisTabs();
                                });
                            } else {
                                layer.msg("抵扣应付款明细保存失败:"+data.msg, {icon: 2, time: 2000});
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