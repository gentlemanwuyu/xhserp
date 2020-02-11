@extends('layouts.default')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="payment">
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>付款信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">供应商</label>
                            <div class="layui-input-block">
                                <select name="supplier_id" lay-filter="supplier" lay-search lay-verify="required" lay-reqText="请选择供应商">
                                    <option value="">请选择供应商</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">付款金额</label>
                            <div class="layui-input-block">
                                <input type="text" name="amount" lay-verify="required" lay-reqText="请输入付款金额" class="layui-input" value="" oninput="value=value.replace(/[^\d.]/g, '')">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">付款方式</label>
                            <div class="layui-input-block">
                                <select name="method" lay-filter="method" lay-verify="required" lay-reqText="请选择收款方式">
                                    <option value="">请选择付款方式</option>
                                    @foreach(\App\Modules\Finance\Models\Payment::$methods as $method_id => $method_name)
                                        <option value="{{$method_id}}">{{$method_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card layui-hide" id="unpaidDetailCard">
            <div class="layui-card-header">
                <h3>抵扣明细</h3>
            </div>
            <div class="layui-card-body">
                <table id="detail" lay-filter="detail"></table>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="payment">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var users = <?= json_encode($users); ?>
                ,suppliers = <?= json_encode($suppliers); ?>
                ,accounts = <?= json_encode($accounts); ?>;
        layui.use(['form', 'table'], function () {
            var form = layui.form
                    ,table = layui.table
                    ,tableOpts = {
                elem: '#detail'
                ,page: false
                ,totalRow: true
                ,cols: [
                    [
                        {type: 'checkbox', field: 'check', width: 100, totalRowText: '合计'},
                        {field: 'order_code', title: '订单编号', align: 'center'},
                        {field: 'sku_code', title: 'SKU', align: 'center'},
                        {field: 'order_quantity', title: '订单数量', align: 'center'},
                        {field: 'entry_quantity', title: '入库数量', align: 'center'},
                        {field: 'price', title: '单价', align: 'center'},
                        {field: 'amount', title: '总价', align: 'center', totalRow: true},
                        {field: 'entry_at', title: '入库时间', align: 'center', templet: function (d) {
                            return d.entry_id ? moment(d.entry_at).format('YYYY-MM-DD') : '';
                        }}
                    ]
                ]
            };

            // 供应商选择框联动
            form.on('select(supplier)', function (data) {
                $('#totalRemainedAmountDiv').remove();
                if (data.value) {
                    var supplier = suppliers[data.value], html = '';
                    html += '<div class="layui-form-item" id="totalRemainedAmountDiv">';
                    html += '<label class="layui-form-label">结余金额</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + supplier.total_remained_amount + '</span>';
                    html += '</div>';
                    html += '</div>';
                    $(data.elem).parents('.layui-form-item').after(html);

                    $('#unpaidDetailCard').removeClass('layui-hide');
                    tableOpts.data = supplier['unpaid_items'];
                    table.render(tableOpts);
                }else {
                    $('#unpaidDetailCard').addClass('layui-hide');
                }
            });

            table.on('checkbox(detail)', function (obj) {
                var checkStatus = table.checkStatus('detail')
                        ,checkedAmount = 0
                        ,$amountInput = $('input[name=amount]')
                        ,inputAmount = $amountInput.val() ? parseFloat($amountInput.val()) : 0
                        ,supplier_id = $('select[name=supplier_id]').val()
                        ,total_remained_amount = suppliers[supplier_id]['total_remained_amount'];
                checkStatus.data.forEach(function (item) {
                    checkedAmount += parseFloat(item.price) * parseInt(item.entry_quantity);
                });

                if (checkedAmount > inputAmount + total_remained_amount) {
                    layer.msg("选中的明细金额不可大于付款金额", {icon: 5, shift: 6});
                    return false;
                }
            });

            form.on('select(method)', function (data) {
                $('select[name=pay_user_id]').parents('.layui-form-item').remove();
                $('select[name=account_id]').parents('.layui-form-item').remove();
                var html = '';
                if (1 == data.value) {
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label required">付款人</label>';
                    html += '<div class="layui-input-block">';
                    html += '<select name="pay_user_id" lay-verify="required" lay-reqText="请选择付款人">';
                    html += '<option value="">请选择付款人</option>';
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

                form.render('select', 'payment');
            });

            // 提交收款单
            form.on('submit(payment)', function (form_data) {
                var data = form_data.field;

                if (0 >= parseFloat(data.amount)) {
                    layer.msg("付款金额需大于0", {icon: 5, shift: 6});
                    $('input[name=amount]').addClass('layui-form-danger').focus();
                    return false;
                }

                var checkStatus = table.checkStatus('detail'), checked_entry_ids = array_column(checkStatus.data, 'entry_id');

                data.checked_entry_ids = checked_entry_ids;

                layer.confirm("付款单提交后不可修改，确认要提交吗？", {icon: 3, title:"确认"}, function (index) {
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('finance::payment.save')}}",
                        data: data,
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("付款单保存成功", {icon: 1, time: 2000}, function(){
                                    parent.layui.admin.closeThisTabs();
                                });
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