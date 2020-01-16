@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        #detailTable tbody td{padding: 0;}
        #detailTable tbody tr{height: 40px;}
        #detailTable .layui-input{border: 0;}
    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="order">
        @if(isset($order_id))
            <input type="hidden" name="order_id" value="{{$order_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">订单号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入订单号" class="layui-input" value="{{$order->code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">客户</label>
                            <div class="layui-input-block">
                                <select name="customer_id" lay-search="" lay-filter="customer" lay-verify="required" lay-reqText="请选择客户">
                                    <option value="">请选择客户</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}" @if(isset($order->customer_id) && $customer->id == $order->customer_id) selected @endif>{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">付款方式</label>
                            <div class="layui-input-block">
                                <select name="payment_method" lay-verify="required" lay-reqText="请选择付款方式">
                                    <option value="">请选择付款方式</option>
                                    @foreach(\App\Modules\Sale\Models\Customer::$payment_methods as $method_id => $method)
                                        <option value="{{$method_id}}" @if(isset($order->payment_method) && $method_id == $order->payment_method) selected @endif>{{$method}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">税率</label>
                            <div class="layui-input-block">
                                <select name="tax" lay-verify="required" lay-reqText="请选择税率">
                                    <option value="">请选择税率</option>
                                    @foreach(\App\Modules\Sale\Models\Customer::$taxes as $tax_id => $val)
                                        <option value="{{$tax_id}}" @if(isset($order) && $tax_id == $order->tax) selected @endif>{{$val['display']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <?php $customer = isset($order) ? $order->customer : null; ?>
                        @if($customer && 2 == $customer->payment_method)
                            <div class="layui-form-item" id="remained_credit_item">
                                <label class="layui-form-label">剩余额度</label>
                                <div class="layui-input-block">
                                    <span class="erp-form-span">{{$customer->remained_credit}}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>订单明细</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table" id="detailTable">
                    <thead>
                    <tr>
                        <th width="50">序号</th>
                        <th width="150">商品</th>
                        <th width="100">商品编号</th>
                        <th width="150">SKU</th>
                        <th>标题</th>
                        <th width="50">单位</th>
                        <th width="100">数量</th>
                        <th width="100">单价</th>
                        <th width="100">总价</th>
                        <th width="100">交期</th>
                        <th>备注</th>
                        <th width="60">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($order->items))
                        <?php $index = 1; ?>
                        @foreach($order->items as $item)
                            <tr data-flag="{{$item->id}}">
                                <td erp-col="index">{{$index++}}</td>
                                <td>
                                    <select name="items[{{$item->id}}][goods_id]" lay-filter="goods" lay-search="" lay-verify="required" lay-reqText="请选择商品">
                                        <option value="">请选择商品</option>
                                        @foreach($goods as $g)
                                            <option value="{{$g->id}}" @if($item->goods_id == $g->id) selected @endif>{{$g->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td erp-col="code">{{$item->goods->code or ''}}</td>
                                <td erp-col="sku">
                                    <select name="items[{{$item->id}}][sku_id]" lay-search="" lay-verify="required" lay-reqText="请选择SKU">
                                        <option value="">请选择SKU</option>
                                        @foreach($goods[$item->goods_id]->skus as $sku)
                                            <option value="{{$sku->id}}" @if($item->sku_id == $sku->id) selected @endif>{{$sku->code}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td erp-col="title"><input type="text" name="items[{{$item->id}}][title]" placeholder="标题" lay-verify="required" lay-reqText="请输入标题" class="layui-input" value="{{$item->title or ''}}"></td>
                                <td><input type="text" name="items[{{$item->id}}][unit]" placeholder="单位" lay-verify="required" lay-reqText="请输入单位" class="layui-input" value="{{$item->unit or ''}}"></td>
                                <td><input type="text" name="items[{{$item->id}}][quantity]" lay-filter="quantity" placeholder="数量" lay-verify="required" lay-reqText="请输入数量" class="layui-input" value="{{$item->quantity or ''}}"></td>
                                <td><input type="text" name="items[{{$item->id}}][price]" lay-filter="price" placeholder="单价" lay-verify="required" lay-reqText="请输入单价" class="layui-input" value="{{$item->price or ''}}"></td>
                                <td erp-col="amount">{{number_format($item->quantity * $item->price, 2, '.', '')}}</td>
                                <td><input type="text" name="items[{{$item->id}}][delivery_date]" lay-filter="delivery_date" placeholder="交期" class="layui-input" value="{{$item->delivery_date or ''}}"></td>
                                <td><input type="text" name="items[{{$item->id}}][note]" placeholder="备注" class="layui-input" value="{{$item->note or ''}}"></td>
                                <td><button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="layui-card-body">
                <button type="button" class="layui-btn layui-btn-normal" lay-event="addItem">增行</button>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="order">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var goods = <?= json_encode($goods); ?>;
        var customers = <?= json_encode($customers); ?>;
        layui.use(['form', 'laydate'], function () {
            var form = layui.form
                    ,laydate = layui.laydate
            // 监听商品选择框
                    ,listenSelectGoods = function () {
                form.on('select(goods)', function(data){
                    var $td = $(data.elem).parent('td')
                            ,flag = $td.parent('tr').attr('data-flag');
                    if (data.value) {
                        var html = '';
                        html += '<select name="items[' + flag + '][sku_id]" lay-search="" lay-verify="required" lay-reqText="请选择SKU">';
                        html += '<option value="">请选择SKU</option>';
                        goods[data.value]['skus'].forEach(function (sku) {
                            html += '<option value="' + sku.id + '">' + sku.code + '</option>';
                        });
                        html += '</select>';
                        $td.siblings('td[erp-col=sku]').html(html);
                        $td.siblings('td[erp-col=code]').html(goods[data.value]['code']);
                        $td.siblings('td[erp-col=title]').find('input[type=text]').val(goods[data.value]['name']);
                    }else {
                        $td.siblings('td[erp-col=sku]').html('');
                        $td.siblings('td[erp-col=code]').html('');
                        $td.siblings('td[erp-col=title]').find('input[type=text]').val('');
                    }
                    form.render('select', 'order');
                });
            }
            // 监听价格数量输入框
                    ,listenPriceQuantityInput = function () {
                $('input[ lay-filter=quantity]').on('keyup', function () {
                    var $tr = $(this).parents('tr')
                            ,quantity = this.value
                            ,price = $tr.find('input[ lay-filter=price]').val();
                    if (new RegExp(/^\d{1,}$/).test(quantity) && price && !isNaN(price)) {
                        var amount = parseInt(quantity) * parseFloat(price);
                        $tr.find('td[erp-col=amount]').html(amount.toFixed(2));
                    }else {
                        $tr.find('td[erp-col=amount]').html('');
                    }
                });
                $('input[ lay-filter=price]').on('keyup', function () {
                    var $tr = $(this).parents('tr')
                            ,price = this.value
                            ,quantity = $tr.find('input[ lay-filter=quantity]').val();
                    if (new RegExp(/^\d{1,}$/).test(quantity) && price && !isNaN(price)) {
                        var amount = parseInt(quantity) * parseFloat(price);
                        $tr.find('td[erp-col=amount]').html(amount.toFixed(2));
                    }else {
                        $tr.find('td[erp-col=amount]').html('');
                    }
                });
            }
            // 绑定日期插件
                    ,bindLayDate = function () {
                $('input[lay-filter=delivery_date]').each(function () {
                    laydate.render({
                        elem: this
                        ,trigger : 'click'
                    });
                })
            };

            // 页面初始化绑定事件
            listenSelectGoods();
            listenPriceQuantityInput();
            bindLayDate();

            $('button[lay-event=addItem]').on('click', function () {
                var $body = $('#detailTable').find('tbody')
                        ,html = ''
                        ,flag = Date.now();
                html += '<tr data-flag="' + flag + '">';
                // 序号
                html += '<td erp-col="index">';
                html += $body.children('tr').length + 1;
                html += '</td>';
                // 选择商品
                html += '<td>';
                html += '<select name="items[' + flag + '][goods_id]" lay-filter="goods" lay-search="" lay-verify="required" lay-reqText="请选择商品">';
                html += '<option value="">请选择商品</option>';
                $.each(goods, function (_, g) {
                    html += '<option value="' + g.id + '">' + g.name + '</option>';
                });
                html += '</select>';
                html += '</td>';
                // 商品编号
                html += '<td erp-col="code">';
                html += '</td>';
                // 选择SKU
                html += '<td erp-col="sku">';
                html += '</td>';
                // 标题
                html += '<td erp-col="title">';
                html += '<input type="text" name="items[' + flag + '][title]" placeholder="标题" lay-verify="required" lay-reqText="请输入标题" class="layui-input">';
                html += '</td>';
                // 单位
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][unit]" placeholder="单位" lay-verify="required" lay-reqText="请输入单位" class="layui-input">';
                html += '</td>';
                // 数量
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][quantity]" lay-filter="quantity" placeholder="数量" lay-verify="required" lay-reqText="请输入数量" class="layui-input">';
                html += '</td>';
                // 单价
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][price]" lay-filter="price" placeholder="单价" lay-verify="required" lay-reqText="请输入单价" class="layui-input">';
                html += '</td>';
                // 总价
                html += '<td erp-col="amount">';
                html += '</td>';
                // 交期
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][delivery_date]" lay-filter="delivery_date" placeholder="交期" class="layui-input">';
                html += '</td>';
                // 备注
                html += '<td>';
                html += '<input type="text" name="items[' + flag + '][note]" placeholder="备注" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>';
                html += '</td>';
                html += '</tr>';

                $body.append(html);
                form.render();
                listenSelectGoods();
                listenPriceQuantityInput();
                bindLayDate();
            });

            // 监听客戶选择框
            form.on('select(customer)', function (data) {
                var payment_method = ''
                        ,tax = ''
                        ,$paymentMethodSelect = $('select[name=payment_method]')
                        ,$taxSelect = $('select[name=tax]');
                if (data.value) {
                    var customer = customers[data.value];

                    payment_method = customer['payment_method'];
                    tax = customer['tax'];
                    if (2 == customer['payment_method']) {
                        var html = '';
                        html += '<div class="layui-form-item" id="remained_credit_item">';
                        html += '<label class="layui-form-label">剩余额度</label>';
                        html += '<div class="layui-input-block">';
                        html += '<span class="erp-form-span">' + customer.remained_credit + '</span>';
                        html += '</div>';
                        html += '</div>';
                        $taxSelect.parents('.layui-form-item').after(html);
                    }else {
                        $('#remained_credit_item').remove();
                    }
                }else {
                    $('#remained_credit_item').remove();
                }

                $paymentMethodSelect.val(payment_method);
                $taxSelect.val(tax);
                form.render('select', 'order');
            });

            // 提交订单
            form.on('submit(order)', function (form_data) {
                var item_exists = false;
                $.each(form_data.field, function (key, val) {
                    if (new RegExp(/^items\[[\d]+\]\[[\d\D]+\]$/).test(key)) {
                        item_exists = true;
                        return true;
                    }
                });

                if (!item_exists) {
                    layer.msg("请添加订单明细再提交", {icon:2});
                    return false;
                }

                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::order.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("订单添加成功", {icon: 1, time: 2000}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg("订单添加失败:"+data.msg, {icon: 2, time: 2000});
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
    </script>
@endsection