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
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入订单号" class="layui-input" value="">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">供应商</label>
                            <div class="layui-input-block">
                                <select name="supplier_id" lay-search="">
                                    <option value="">请选择供应商</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">付款方式</label>
                            <div class="layui-input-block">
                                <select name="payment_method">
                                    <option value="">请选择付款方式</option>
                                    @foreach(\App\Modules\Purchase\Models\Supplier::$payment_methods as $method_id => $method)
                                        <option value="{{$method_id}}">{{$method}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                        <th width="150">产品</th>
                        <th width="100">产品编号</th>
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
        var products = <?= json_encode($products); ?>;
        layui.use(['form', 'laydate'], function () {
            var form = layui.form
                    ,laydate = layui.laydate
                    // 监听产品选择框
                    ,listenSelectProduct = function () {
                form.on('select(product)', function(data){
                    var $td = $(data.elem).parent('td')
                            ,flag = $td.parent('tr').attr('data-flag');
                    if (data.value) {
                        var html = '';
                        html += '<select name="items[' + flag + '][sku_id]" lay-search="">';
                        html += '<option value="">请选择SKU</option>';
                        products[data.value]['skus'].forEach(function (sku) {
                            html += '<option value="' + sku.id + '">' + sku.code + '</option>';
                        });
                        html += '</select>';
                        $td.siblings('td[erp-col=sku]').html(html);
                        $td.siblings('td[erp-col=product_code]').html(products[data.value]['code']);
                    }else {
                        $td.siblings('td[erp-col=sku]').html('');
                        $td.siblings('td[erp-col=product_code]').html('');
                    }
                    form.render('select', 'order');
                });
            }
                    // 监听价格数量输入框
                    ,listenPriceQuantityInput = function () {
                $('input[ lay-filter=quantity]').on('keyup', function () {
                    var quantity = this.value
                            ,price = $('input[ lay-filter=price]').val();
                    if (new RegExp(/^\d{1,}$/).test(quantity) && price && !isNaN(price)) {
                        var amount = parseInt(quantity) * parseFloat(price);
                        $('td[erp-col=amount]').html(amount.toFixed(2));
                    }else {
                        $('td[erp-col=amount]').html('');
                    }
                });
                $('input[ lay-filter=price]').on('keyup', function () {
                    var price = this.value
                            ,quantity = $('input[ lay-filter=quantity]').val();
                    if (new RegExp(/^\d{1,}$/).test(quantity) && price && !isNaN(price)) {
                        var amount = parseInt(quantity) * parseFloat(price);
                        $('td[erp-col=amount]').html(amount.toFixed(2));
                    }else {
                        $('td[erp-col=amount]').html('');
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
            $('button[lay-event=addItem]').on('click', function () {
                var $body = $('#detailTable').find('tbody')
                        ,html = ''
                        ,flag = Date.now();
                html += '<tr data-flag="' + flag + '">';
                // 序号
                html += '<td erp-col="index">';
                html += $body.children('tr').length + 1;
                html += '</td>';
                // 选择产品
                html += '<td>';
                html += '<select name="items[' + flag + '][product_id]" lay-filter="product" lay-search="">';
                html += '<option value="">请选择产品</option>';
                $.each(products, function (_, product) {
                    html += '<option value="' + product.id + '">' + product.name + '</option>';
                });
                html += '</select>';
                html += '</td>';
                // 产品编号
                html += '<td erp-col="product_code">';
                html += '</td>';
                // 选择SKU
                html += '<td erp-col="sku">';
                html += '</td>';
                // 标题
                html += '<td>';
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
                listenSelectProduct();
                listenPriceQuantityInput();
                bindLayDate();
            });
        });
    </script>
@endsection