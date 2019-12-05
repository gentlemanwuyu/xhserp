@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        #skuTable tbody td{padding: 0;}
        #skuTable tbody tr{height: 40px;}
        #skuTable .layui-input{border: 0;}
        #skuTable .layui-form-switch{margin-left: 0;}
    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="single">
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">商品编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入商品编号" class="layui-input" value="{{$product->code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">商品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入商品名称" class="layui-input" value="{{$product->name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">分类</label>
                            <div class="layui-input-block">
                                <select name="category_id" lay-filter="category" lay-search="" lay-verify="required" lay-reqText="请选择分类">
                                    <option value="">请选择分类</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">商品描述</label>
                            <div class="layui-input-block">
                                <textarea name="desc" class="layui-textarea">{{$product->desc or ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>SKU列表</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table" id="skuTable">
                    <thead>
                    <tr>
                        <th rowspan="2" width="8%">
                            是否开启
                            <input type="checkbox" name="" lay-skin="switch" lay-text="是|否" switch-index="all" lay-filter="enableSku">
                        </th>
                        <th colspan="3">产品信息</th>
                        <th rowspan="2" class="required">SKU编号</th>
                        <th rowspan="2" class="required">最低售价</th>
                        <th rowspan="2">建议零售价</th>
                    </tr>
                    <tr>
                        <th>SKU编号</th>
                        <th>重量</th>
                        <th>成本价</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($product->skus))
                        @foreach($product->skus as $sku)
                            <tr class="layui-disabled" row-index="{{$sku->id}}">
                                <td><input type="checkbox" name="skus[{{$sku->id}}][enabled]" lay-skin="switch" lay-text="是|否" value="1" switch-index="{{$sku->id}}" lay-filter="enableSku"></td>
                                <td>{{$sku->code or ''}}</td>
                                <td>{{(float)$sku->weight ? $sku->weight : ''}}</td>
                                <td>{{(float)$sku->cost_price ? $sku->weight : ''}}</td>
                                <td goods-field="code"></td>
                                <td goods-field="lowest_price"></td>
                                <td goods-field="msrp"></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="single">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var productSkus = <?= json_encode(array_column($product->skus->toArray(), null,'id')); ?>;

        layui.use(['form'], function () {
            var form = layui.form
                    ,enableSku = function (sku_id, enable) {
                var $tr = $('tr[row-index=' + sku_id + ']');
                var productSku = productSkus[sku_id];
                if (enable) {
                    $tr.removeClass('layui-disabled');
                    $tr.find('input[lay-filter=enableSku]').prop('checked', true);
                    $tr.children('td[goods-field=code]').html('<input type="text" name="skus[' + productSku.id + '][code]" placeholder="SKU编号" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input" value="' + productSku.code + '">');
                    $tr.children('td[goods-field=lowest_price]').html('<input type="text" name="skus[' + productSku.id + '][lowest_price]" placeholder="最低售价" lay-verify="required" lay-reqText="请输入最低售价" class="layui-input">');
                    $tr.children('td[goods-field=msrp]').html('<input type="text" name="skus[' + productSku.id + '][msrp]" placeholder="建议零售价" class="layui-input">');
                }else {
                    $tr.addClass('layui-disabled');
                    $tr.find('input[lay-filter=enableSku]').prop('checked', false);
                    $tr.children('td[goods-field=code]').html('');
                    $tr.children('td[goods-field=lowest_price]').html('');
                    $tr.children('td[goods-field=msrp]').html('');
                }

                form.render('checkbox', 'single');
            }
                    ,inspectAll = function () {
                var checked = 0, $headerSwitch = $('#skuTable thead input[lay-filter=enableSku]'), $switches = $('#skuTable tbody input[lay-filter=enableSku]');
                if (0 == $switches.length) {
                    $headerSwitch.prop('checked', false);
                }else {
                    $switches.each(function (key, elem) {
                        if ($(elem).prop('checked')) {
                            checked += 1;
                        }
                    });
                    if (checked == $switches.length) {
                        $headerSwitch.prop('checked', true);
                    }else {
                        $headerSwitch.prop('checked', false);
                    }
                }

                form.render('checkbox', 'single');
            };

            // 监听开关
            form.on('switch(enableSku)', function(data){
                var switchIndex = $(data.elem).attr('switch-index');

                if ('all' == switchIndex) {
                    $.each(productSkus, function (sku_id, _) {
                        enableSku(sku_id, data.elem.checked);
                    });
                }else {
                    enableSku(switchIndex, data.elem.checked);
                    inspectAll();
                }
            });

            // 表单提交
            form.on('submit(single)', function (form_data) {

            });
        });
    </script>
@endsection