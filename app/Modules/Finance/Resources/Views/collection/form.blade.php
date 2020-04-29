@extends('layouts.default')
@section('css')
    <style>
        #backOrderCard .layui-table th, #backOrderCard .layui-table td{text-align: center;}
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
                            <label class="layui-form-label required">客户</label>
                            <div class="layui-input-block">
                                <select name="customer_id" lay-filter="customer" lay-search lay-verify="required" lay-reqText="请选择客户">
                                    <option value="">请选择客户</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
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
                                    @foreach(\App\Modules\Finance\Models\Collection::$methods as $method_id => $method_name)
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
                        <th>订单号</th>
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
                                <li class="erp-static-table-list-li" style="width: 150px; text-align: center;">单价(CNY)</li>
                                <li class="erp-static-table-list-li" style="width: 150px; text-align: center;">金额(CNY)</li>
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
        <button type="button" class="layui-btn" lay-submit lay-filter="collection">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var users = <?= json_encode($users); ?>
                ,customers = <?= json_encode($customers); ?>
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
                        {field: 'order_code', title: '订单编号', align: 'center'},
                        {field: 'currency_code', title: '币种', width: 60, align: 'center'},
                        {field: 'title', title: '品名', align: 'center'},
                        {field: 'order_quantity', title: '订单数量', width: 100, align: 'center'},
                        {field: 'delivery_code', title: '出货单编号', align: 'center'},
                        {field: 'delivery_quantity', title: '出货数量', width: 100, align: 'center'},
                        {field: 'real_quantity', title: '真实数量', width: 100, align: 'center'},
                        {field: 'price', title: '单价', width: 100, align: 'center'},
                        {field: 'amount', title: '应付金额', width: 100, align: 'center'},
                        {field: 'cny_price', title: '单价(CNY)', width: 100, align: 'center'},
                        {field: 'cny_amount', title: '金额(CNY)', width: 100, align: 'center', totalRow: true},
                        {field: 'delivery_date', title: '出货日期', width: 150, align: 'center', templet: function (d) {
                            return moment(d.delivery_at).format('YYYY-MM-DD');
                        }}
                    ]
                ]
                ,done: function(res, curr, count){
                    var $table = $('*[lay-id=' + this.id + ']')
                            ,$realQuantityTh = $table.find('.layui-table-header th[data-field=real_quantity]');
                    $realQuantityTh.find('span').mouseover(function (e) {
                        layer.tips('出货数量减去换货数量为真实数量', this, {tips: 1});
                    });
                }
            };

            // 客户选择框联动
            form.on('select(customer)', function (data) {
                $('#totalRemainedAmountDiv').remove();
                $('#backAmountDiv').remove();
                if (data.value) {
                    var customer = customers[data.value];
                    var html = '';
                    html += '<div class="layui-form-item" id="totalRemainedAmountDiv">';
                    html += '<label class="layui-form-label">结余金额</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + customer.total_remained_amount.toFixed(2) + '</span>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="layui-form-item" id="backAmountDiv">';
                    html += '<label class="layui-form-label">退货金额</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + customer.back_amount.toFixed(2) + '</span>';
                    html += '</div>';
                    html += '</div>';
                    $(data.elem).parents('.layui-form-item').after(html);

                    $('#unpaidDetailCard').removeClass('layui-hide');
                    tableOpts.data = customer['unpaid_items'];
                    table.render(tableOpts);

                    var $backOrderCard = $('#backOrderCard')
                            ,$backOrderTable = $backOrderCard.find('.layui-table');
                    if (0 < customer.back_orders.length) {
                        var index = 1, html = '';
                        customer.back_orders.forEach(function (return_order) {
                            html += '<tr>';
                            html += '<td>' + (index++) + '</td>';
                            html += '<td>' + return_order.code + '</td>';
                            html += '<td>' + return_order.order.code + '</td>';
                            html += '<td>' + return_order.order.currency.code + '</td>';
                            html += '<td>' + return_order.reason + '</td>';
                            html += '<td>' + return_order.undeducted_amount + '</td>';
                            html += '<td class="erp-static-table-list">';
                            return_order.items.forEach(function (return_order_item, key) {
                                var cny_price = return_order_item.order_item.price * return_order.order.currency.rate
                                        ,cny_amount = cny_price * return_order_item.quantity;
                                html += '<ul class="erp-static-table-list-ul';
                                if (0 == key) {
                                    html += ' erp-static-table-list-ul-first';
                                }
                                html += '">';
                                html += '<li class="erp-static-table-list-li erp-static-table-list-li-first" style="width: 250px;">' + return_order_item.order_item.title + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + return_order_item.order_item.quantity + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + return_order_item.quantity + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 100px;">' + return_order_item.order_item.price + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 150px;">' + cny_price.toFixed(2) + '</li>';
                                html += '<li class="erp-static-table-list-li" style="width: 150px;">' + cny_amount.toFixed(2) + '</li>';
                                html += '</ul>';
                            });
                            html += '</td>';
                            html += '<td>' + return_order.user.name + '</td>';
                            html += '<td>' + return_order.created_at + '</td>';
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

            // 付款方式选择框联动
            form.on('select(method)', function (data) {
                $('select[name=collect_user_id]').parents('.layui-form-item').remove();
                $('select[name=account_id]').parents('.layui-form-item').remove();
                var html = '';
                if ("{{\Payment::CASH}}" == data.value) {
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
                }else if ("{{\Payment::REMITTANCE}}" == data.value) {
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

            table.on('checkbox(detail)', function (obj) {
                var checkStatus = table.checkStatus('detail')
                        ,checkedAmount = 0
                        ,currency_code = $('select[name=currency_code]').val()
                        ,currency = currencies[currency_code]
                        ,$amountInput = $('input[name=amount]')
                        ,inputAmount = $amountInput.val() ? parseFloat($amountInput.val()) : 0
                        ,cnyInputAmount = currency ? inputAmount * currency['rate'] : 0
                        ,customer_id = $('select[name=customer_id]').val()
                        ,total_remained_amount = customers[customer_id]['total_remained_amount']
                        ,back_amount = customers[customer_id]['back_amount'];
                checkStatus.data.forEach(function (item) {
                    checkedAmount += parseFloat(item.price) * parseInt(item.real_quantity) * parseFloat(item.rate);
                });

                if (checkedAmount > cnyInputAmount + total_remained_amount + back_amount) {
                    layer.msg("选中的明细金额不可大于收款金额", {icon: 5, shift: 6});
                    return false;
                }
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
                                layer.msg("收款单保存成功", {icon: 1, time: 2000}, function(){
                                    parent.layui.admin.closeThisTabs();
                                });
                            } else {
                                layer.msg("收款单保存失败:"+data.msg, {icon: 2, time: 2000});
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