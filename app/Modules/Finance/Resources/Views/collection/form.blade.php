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
                        {type: 'checkbox', field: 'check', width: 60, totalRowText: '合计'},
                        {field: 'order_code', title: '订单编号', align: 'center'},
                        {field: 'sku_code', title: 'SKU', align: 'center'},
                        {field: 'order_quantity', title: '订单数量', width: 100, align: 'center'},
                        {field: 'delivery_code', title: '出货单编号', align: 'center'},
                        {field: 'delivery_quantity', title: '出货数量', width: 100, align: 'center'},
                        {field: 'real_quantity', title: '真实数量', width: 100, align: 'center'},
                        {field: 'back_quantity', title: '退货数量', width: 100, align: 'center'},
                        {field: 'payable_quantity', title: '应付数量', width: 100, align: 'center', templet: function (d) {
                            return d.real_quantity - d.back_quantity;
                        }},
                        {field: 'price', title: '单价', width: 100, align: 'center'},
                        {field: 'amount', title: '应付金额', width: 100, align: 'center', totalRow: true},
                        {field: 'delivery_date', title: '出货日期', width: 150, align: 'center', templet: function (d) {
                            return moment(d.delivery_at).format('YYYY-MM-DD');
                        }}
                    ]
                ]
                ,done: function(res, curr, count){
                    var $table = $('*[lay-id=' + this.id + ']')
                            ,$realQuantityTh = $table.find('.layui-table-header th[data-field=real_quantity]')
                            ,$payableQuantityTh = $table.find('.layui-table-header th[data-field=payable_quantity]');
                    $realQuantityTh.find('span').mouseover(function (e) {
                        layer.tips('出货数量减去换货数量为真实数量', this, {tips: 1});
                    });
                    $payableQuantityTh.find('span').mouseover(function (e) {
                        layer.tips('真实数量减去退货数量为应付数量', this, {tips: 1});
                    });
                }
            };

            // 客户选择框联动
            form.on('select(customer)', function (data) {
                $('#totalRemainedAmountDiv').remove();
                if (data.value) {
                    var customer = customers[data.value], html = '';
                    html += '<div class="layui-form-item" id="totalRemainedAmountDiv">';
                    html += '<label class="layui-form-label">结余金额</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + customer.total_remained_amount + '</span>';
                    html += '</div>';
                    html += '</div>';
                    $(data.elem).parents('.layui-form-item').after(html);

                    $('#unpaidDetailCard').removeClass('layui-hide');
                    tableOpts.data = customer['unpaid_items'];
                    table.render(tableOpts);
                }else {
                    $('#unpaidDetailCard').addClass('layui-hide');
                }
            });

            // 付款方式选择框联动
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

            table.on('checkbox(detail)', function (obj) {
                var checkStatus = table.checkStatus('detail')
                        ,checkedAmount = 0
                        ,$amountInput = $('input[name=amount]')
                        ,inputAmount = $amountInput.val() ? parseFloat($amountInput.val()) : 0
                        ,customer_id = $('select[name=customer_id]').val()
                        ,total_remained_amount = customers[customer_id]['total_remained_amount'];
                checkStatus.data.forEach(function (item) {
                    checkedAmount += parseFloat(item.price) * parseInt(item.delivery_quantity);
                });

                if (checkedAmount > inputAmount + total_remained_amount) {
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