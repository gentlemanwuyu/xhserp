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
        @if(isset($product_id))
            <input type="hidden" name="product_id" value="{{$product_id}}">
        @endif
        @if(isset($goods_id))
            <input type="hidden" name="goods_id" value="{{$goods_id}}">
        @endif
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
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入商品编号" class="layui-input" value="{{isset($goods) ? $goods->code : (isset($product) ? $product->code : '')}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">商品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入商品名称" class="layui-input" value="{{isset($goods) ? $goods->name : (isset($product) ? $product->name : '')}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">分类</label>
                            <div class="layui-input-block">
                                <div id="category_id_select"></div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">商品描述</label>
                            <div class="layui-input-block">
                                <textarea name="desc" class="layui-textarea">{{isset($goods) ? $goods->desc : (isset($product) ? $product->desc : '')}}</textarea>
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
                            <input type="checkbox" lay-skin="switch" lay-text="是|否" switch-index="all" lay-filter="enableSku" @if(isset($goods) && $goods->all_enabled) checked @endif>
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
                            <?php
                                $single_sku = $sku->single_sku;
                            ?>
                            <tr @if(!$single_sku) class="layui-disabled" @endif row-index="{{$sku->id}}">
                                <td><input type="checkbox" name="" lay-skin="switch" lay-text="是|否" switch-index="{{$sku->id}}" lay-filter="enableSku" @if($single_sku) checked @endif @if($single_sku && !$single_sku->deletable) disabled @endif></td>
                                <td>{{$sku->code or ''}}</td>
                                <td>{{(float)$sku->weight ? $sku->weight : ''}}</td>
                                <td>{{(float)$sku->cost_price ? $sku->weight : ''}}</td>
                                <td goods-field="code">
                                    @if($single_sku)
                                        <input type="text" name="skus[{{$sku->id}}][code]" placeholder="SKU编号" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input" value="{{$single_sku->code}}">
                                    @endif
                                </td>
                                <td goods-field="lowest_price">
                                    @if($single_sku)
                                        <input type="text" name="skus[{{$sku->id}}][lowest_price]" placeholder="最低售价" lay-verify="required" lay-reqText="请输入最低售价" class="layui-input" value="{{$single_sku->lowest_price}}" oninput="value=value.replace(/[^\d.]/g, '')">
                                    @endif
                                </td>
                                <td goods-field="msrp">
                                    @if($single_sku)
                                        <input type="text" name="skus[{{$sku->id}}][msrp]" placeholder="建议零售价" class="layui-input" value="{{(float)$single_sku->msrp ?: ''}}" oninput="value=value.replace(/[^\d.]/g, '')">
                                    @endif
                                </td>
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
        var categories = <?= json_encode($categories); ?>
                ,goods = <?= isset($goods) ? json_encode($goods) : 'undefined'; ?>
                ,category_parent_ids = <?= isset($goods) ? json_encode($goods->category->parent_ids) : '[]'; ?>
                ,productSkus = <?= json_encode($product->indexSkus); ?>;

        layui.use(['form'], function () {
            var form = layui.form
                    ,enableSku = function (sku_id, enable) {
                var $tr = $('tr[row-index=' + sku_id + ']');
                var productSku = productSkus[sku_id];
                if (enable) {
                    $tr.removeClass('layui-disabled');
                    $tr.find('input[lay-filter=enableSku]').prop('checked', true);
                    $tr.children('td[goods-field=code]').html('<input type="text" name="skus[' + productSku.id + '][code]" placeholder="SKU编号" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input" value="' + productSku.code + '">');
                    $tr.children('td[goods-field=lowest_price]').html('<input type="text" name="skus[' + productSku.id + '][lowest_price]" placeholder="最低售价" lay-verify="required" lay-reqText="请输入最低售价" class="layui-input" oninput="value=value.replace(/[^\\d.]/g, \'\')">');
                    $tr.children('td[goods-field=msrp]').html('<input type="text" name="skus[' + productSku.id + '][msrp]" placeholder="建议零售价" class="layui-input" oninput="value=value.replace(/[^\\d.]/g, \'\')">');
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

            // 分类下拉树
            xmSelect.render({
                el: '#category_id_select',
                name: 'category_id',
                layVerify: 'required',
                initValue: goods && goods.category_id ? [goods.category_id] : '',
                tips: '请选择分类',
                model: {icon: 'hidden', label: {type: 'text'}},
                radio: true,
                clickClose: true,
                filterable: true,
                searchTips: '搜索...',
                theme:{
                    color: '#5FB878'
                },
                prop: {
                    name: 'name',
                    value: 'id'
                },
                tree: {
                    show: true,
                    showLine: false,
                    strict: false,
                    expandedKeys: category_parent_ids
                },
                height: 'auto',
                data: categories
            });

            // 监听开关
            form.on('switch(enableSku)', function(data){
                var switchIndex = $(data.elem).attr('switch-index');

                if ('all' == switchIndex) {
                    $.each(productSkus, function (sku_id, product_sku) {
                        if (!product_sku.single_sku || product_sku.single_sku.deletable) {
                            enableSku(sku_id, data.elem.checked);
                        }
                    });
                }else {
                    enableSku(switchIndex, data.elem.checked);
                    inspectAll();
                }
            });

            // 表单提交
            form.on('submit(single)', function (form_data) {
                var sku_exists = false;
                $.each(form_data.field, function (key, val) {
                    if (new RegExp(/^skus\[[\d]+\]\[[\d\D]+\]$/).test(key)) {
                        sku_exists = true;
                        return false; // 跳出循环
                    }
                });

                if (!sku_exists) {
                    layer.msg("请至少开启一个SKU", {icon: 5, shift: 6});
                    return false;
                }

                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('goods::single.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("商品保存成功", {icon: 1, time: 2000}, function(){
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("商品保存失败:"+data.msg, {icon:2});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(load_index);
                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                        return false;
                    }
                });
            });
        });
    </script>
@endsection