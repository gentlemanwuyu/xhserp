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
                        <div class="layui-form-item">
                            <label class="layui-form-label required">收款方式</label>
                            <div class="layui-input-block">
                                <select name="method" lay-filter="method" lay-verify="required" lay-reqText="请选择收款方式">
                                    <option value="">请选择收款方式</option>
                                    <option value="1">现金</option>
                                    <option value="2">汇款</option>
                                    <option value="3">支票/汇票</option>
                                </select>
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
        var users = <?= json_encode($users); ?>, accounts = <?= json_encode($accounts); ?>;
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

            form.on('select(method)', function (data) {
                $('select[name=collect_user_id]').parents('.layui-form-item').remove();
                $('select[name=account_id]').parents('.layui-form-item').remove();
                var html = '';
                if (1 == data.value) {
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label required">收款人</label>';
                    html += '<div class="layui-input-block">';
                    html += '<select name="collect_user_id" lay-verify="required" lay-reqText="请选择收款人">';
                    html += '<option value="">请选择收款人</option>';
                    users.forEach(function (user) {
                        html += '<option value="' + user.id + '">' + user.name + '</option>';
                    });
                    html += '</select>';
                    html += '</div>';
                }else if (2 == data.value) {
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label required">汇款账户</label>';
                    html += '<div class="layui-input-block">';
                    html += '<select name="account_id" lay-verify="required" lay-reqText="请选择汇款账户">';
                    html += '<option value="">请选择汇款账户</option>';
                    accounts.forEach(function (account) {
                        html += '<option value="' + account.id + '">' + account.name + '</option>';
                    });
                    html += '</select>';
                    html += '</div>';
                }

                $(data.elem).parents('.layui-form-item').after(html);

                form.render('select', 'collection');
            });

            // 提交收款单
            form.on('submit(collection)', function (form_data) {
                var data = form_data.field;

                if (0 >= parseFloat(data.amount)) {
                    layer.msg("收款金额需大于0", {icon: 5, shift: 6});
                    $('input[name=amount]').addClass('layui-form-danger').focus();
                    return false;
                }

                var checkStatus = table.checkStatus('detail'), doi_ids = array_column(checkStatus.data, 'delivery_order_item_id');

                data.checked_doi_ids = doi_ids;
                data.customer_id = "{{$customer_id}}";

                layer.confirm("收款单提交后不可修改，确认要提交吗？", {icon: 3, title:"确认"}, function (index) {
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('finance::collection.save')}}",
                        data: data,
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("付款单保存成功", {icon: 1, time: 2000});
                                table.render(tableOpts);
                            } else {
                                layer.msg("付款单保存失败:"+data.msg, {icon: 2, time: 2000});
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