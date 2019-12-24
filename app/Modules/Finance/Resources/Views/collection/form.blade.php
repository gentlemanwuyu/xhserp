@extends('layouts.default')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="collection">
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>收款信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label">客户</label>
                            <div class="layui-input-block">
                                <span class="erp-form-span">{{$customer->name}}</span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">收款金额</label>
                            <div class="layui-input-block">
                                <input type="text" name="amount" lay-verify="required" lay-reqText="请输入收款金额" class="layui-input" value="" oninput="value=value.replace(/[^\d.]/g, '')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>收款明细</h3>
            </div>
            <div class="layui-card-body">
                <table id="detail" lay-filter="detail"></table>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="collection">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form', 'table'], function () {
            var form = layui.form
                    ,table = layui.table
                    ,tableIns = table.render({
                elem: '#detail'
                ,data: <?= json_encode($customer->unpaid_items); ?>
                ,page: false
                ,totalRow: true
                ,cols: [
                    [
                        {type: 'checkbox', field: 'check', width: 100, totalRowText: '合计'},
                        {field: 'order_code', title: '订单编号', align: 'center'},
                        {field: 'sku_code', title: 'SKU', align: 'center'},
                        {field: 'order_quantity', title: '订单数量', align: 'center'},
                        {field: 'delivery_code', title: '出货单编号', align: 'center'},
                        {field: 'delivery_quantity', title: '出货数量', align: 'center'},
                        {field: 'price', title: '单价', align: 'center'},
                        {field: 'amount', title: '总价', align: 'center', totalRow: true}
                    ]
                ]
                ,done: function(res, curr, count){

                }
            });

            table.on('checkbox(detail)', function (obj) {
                var checkStatus = table.checkStatus('detail')
                        ,checkedAmount = 0
                        ,$amountInput = $('input[name=amount]')
                        ,inputAmount = $amountInput.val() ? parseFloat($amountInput.val()) : 0;
                checkStatus.data.forEach(function (item) {
                    checkedAmount += parseFloat(item.price) * parseInt(item.delivery_quantity);
                });

                if (checkedAmount > inputAmount) {
                    layer.msg("选中的明细金额不可大于收款金额", {icon: 5, shift: 6});
                    return false;
                }
            });

            // 提交收款单
            form.on('submit(collection)', function (form_data) {
                var checkStatus = table.checkStatus('detail');
            });
        });
    </script>
@endsection