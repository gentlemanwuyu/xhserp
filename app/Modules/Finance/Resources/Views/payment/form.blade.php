@extends('layouts.default')
@section('css')
    <style>
        #backOrderCard .layui-table th, #backOrderCard .layui-table td{text-align: center;}
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
                            <label class="layui-form-label required">币种</label>
                            <div class="layui-input-block">
                                <select name="currency_code" lay-search lay-verify="required" lay-reqText="请选择币种">
                                    <option value="">请选择币种</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency['code']}}">{{$currency['code']}}({{$currency['name']}})</option>
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
        <div class="layui-card layui-hide" id="backOrderCard">
            <div class="layui-card-header">
                <h3>退货单明细</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>退货单号</th>
                        <th>采购单号</th>
                        <th>币种</th>
                        <th>退货原因</th>
                        <th>未抵扣金额</th>
                        <th class="erp-static-table-list" style="width: 650px;">
                            <span>退货明细</span>
                            <ul class="erp-static-table-list-ul">
                                <li class="erp-static-table-list-li erp-static-table-list-li-first" style="width: 250px; text-align: center;">品名</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">订单数量</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">退货数量</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">单价</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">单价(CNY)</li>
                                <li class="erp-static-table-list-li" style="width: 100px; text-align: center;">金额(CNY)</li>
                            </ul>
                        </th>
                        <th>创建人</th>
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
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
                ,accounts = <?= json_encode($accounts); ?>
                ,currencies = <?= json_encode($currencies); ?>;
        layui.use(['form', 'table'], function () {
            var form = layui.form
                    ,table = layui.table
                    ,tableOpts = {
                elem: '#detail'
                ,page: false
                ,totalRow: true
                ,cols: [
                    [
                        {type: 'checkbox', field: 'check', width: 60, totalRowText: '合计'},
                        {field: 'purchase_order_code', title: '采购订单编号', width: 200, align: 'center'},
                        {field: 'title', title: '品名', align: 'center'},
                        {field: 'order_quantity', title: '订单数量', width: 100, align: 'center'},
                        {field: 'entry_quantity', title: '入库数量', width: 100, align: 'center'},
                        {field: 'real_quantity', title: '真实数量', width: 100, align: 'center'},
                        {field: 'price', title: '单价', width: 100, align: 'center'},
                        {field: 'amount', title: '应付金额', width: 100, align: 'center'},
                        {field: 'cny_price', title: '单价(CNY)', width: 100, align: 'center'},
                        {field: 'cny_amount', title: '金额(CNY)', width: 100, align: 'center', totalRow: true},
                        {field: 'entried_at', title: '入库时间', width: 150, align: 'center', templet: function (d) {
                            return d.entry_id ? moment(d.entried_at).format('YYYY-MM-DD') : '';
                        }}
                    ]
                ]
            };

            // 供应商选择框联动
            form.on('select(supplier)', function (data) {
                $('#totalRemainedAmountDiv').remove();
                $('#backAmountDiv').remove();
                if (data.value) {
                    var supplier = suppliers[data.value], html = '';
                    html += '<div class="layui-form-item" id="totalRemainedAmountDiv">';
                    html += '<label class="layui-form-label">结余金额</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + supplier.total_remained_amount.toFixed(2) + '</span>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="layui-form-item" id="backAmountDiv">';
                    html += '<label class="layui-form-label">退货金额</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + supplier.back_amount.toFixed(2) + '</span>';
                    html += '</div>';
                    html += '</div>';
                    $(data.elem).parents('.layui-form-item').after(html);

                    $('#unpaidDetailCard').removeClass('layui-hide');
                    tableOpts.data = supplier['unpaid_items'];
                    table.render(tableOpts);

                    var $backOrderCard = $('#backOrderCard')
                            ,$backOrderTable = $backOrderCard.find('.layui-table');
                    if (0 < supplier.back_orders.length) {
                        var index = 1, html = '';
                        supplier.back_orders.forEach(function (purchase_return_order) {
                            html += '<tr>';
                            html += '<td>' + (index++) + '</td>';
                            html += '<td>' + purchase_return_order.code + '</td>';
                            html += '<td>' + purchase_return_order.purchase_order.code + '</td>';
                            html += '<td>' + purchase_return_order.purchase_order.currency.code + '</td>';
                            html += '<td>' + purchase_return_order.reason + '</td>';
                            html += '<td>' + purchase_return_order.undeducted_amount.toFixed(2) + '</td>';
                            html += '<td class="erp-static-table-list">';
                            purchase_return_order.items.forEach(function (purchase_return_order_item, key) {
                                var cny_price = purchase_return_order_item.purchase_order_item.price * purchase_return_order.purchase_order.currency.rate
                                        ,cny_amount = cny_price * purchase_return_order_item.quantity;
                                html += '<ul class="erp-static-table-list-ul';
                                if (0 == key) {
                                    html += ' erp-static-table-list-ul-first';
                                }
                                html += '">';
                                html += '<li class="erp-static-table-list-li erp-static-table-list-li-first" style="width: 250px;">' + purchase_return_order_item.purchase_order_item.title + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + purchase_return_order_item.purchase_order_item.quantity + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + purchase_return_order_item.quantity + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + purchase_return_order_item.purchase_order_item.price + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + cny_price.toFixed(2) + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + cny_amount.toFixed(2) + '</li>';
                                html += '</ul>';
                            });
                            html += '</td>';
                            html += '<td>' + purchase_return_order.user.name + '</td>';
                            html += '<td>' + purchase_return_order.created_at + '</td>';
                            html += '</tr>';
                        });
                        $backOrderTable.find('tbody').html(html);
                        $backOrderCard.removeClass('layui-hide');
                    }else {
                        $backOrderCard.addClass('layui-hide');
                    }
                }else {
                    $('#unpaidDetailCard').addClass('layui-hide');
                    $('#backOrderCard').addClass('layui-hide');
                }
            });

            table.on('checkbox(detail)', function (obj) {
                var checkStatus = table.checkStatus('detail')
                        ,checkedAmount = 0
                        ,currency_code = $('select[name=currency_code]').val()
                        ,currency = currencies[currency_code]
                        ,$amountInput = $('input[name=amount]')
                        ,inputAmount = $amountInput.val() ? parseFloat($amountInput.val()) : 0
                        ,cnyInputAmount = currency ? inputAmount * currency['rate'] : 0
                        ,supplier_id = $('select[name=supplier_id]').val()
                        ,total_remained_amount = suppliers[supplier_id]['total_remained_amount']
                        ,back_amount = suppliers[supplier_id]['back_amount'];
                checkStatus.data.forEach(function (item) {
                    checkedAmount += parseFloat(item.price) * parseInt(item.real_quantity) * parseFloat(item.rate);
                });

                if (checkedAmount > cnyInputAmount + total_remained_amount + back_amount) {
                    layer.msg("选中的明细金额不可大于付款金额", {icon: 5, shift: 6});
                    return false;
                }
            });

            form.on('select(method)', function (data) {
                $('select[name=pay_user_id]').parents('.layui-form-item').remove();
                $('select[name=account_id]').parents('.layui-form-item').remove();
                var html = '';
                if (Payment_CASH == data.value) {
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
                }else if (Payment_REMITTANCE == data.value) {
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