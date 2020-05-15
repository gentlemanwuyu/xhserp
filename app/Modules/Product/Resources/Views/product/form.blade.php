@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="product">
        @if(isset($product_id))
            <input type="hidden" name="product_id" value="{{$product_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">产品编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入产品编号" class="layui-input" value="{{$product->code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">产品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入产品名称" class="layui-input" value="{{$product->name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">分类</label>
                            <div class="layui-input-block">
                                <div id="category_id_select"></div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">产品描述</label>
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
                <table class="layui-table" lay-skin="line" id="skuTable">
                    <thead>
                    <tr>
                        <th class="required">SKU编号</th>
                        <th>规格</th>
                        <th>型号</th>
                        <th>重量</th>
                        <th>成本价</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($product->skus))
                        @foreach($product->skus as $sku)
                            <tr>
                                <td><input type="text" name="skus[{{$sku->id}}][code]" placeholder="SKU编号(必填)" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input" value="{{$sku->code or ''}}"></td>
                                <td><input type="text" name="skus[{{$sku->id}}][size]" placeholder="规格" class="layui-input" value="{{$sku->size or ''}}"></td>
                                <td><input type="text" name="skus[{{$sku->id}}][model]" placeholder="型号" class="layui-input" value="{{$sku->model or ''}}"></td>
                                <td><input type="text" name="skus[{{$sku->id}}][weight]" placeholder="重量" class="layui-input" value="{{(float)$sku->weight ? $sku->weight : ''}}" oninput="value=value.replace(/[^\d.]/g, '')"></td>
                                <td><input type="text" name="skus[{{$sku->id}}][cost_price]" placeholder="成本价" class="layui-input" value="{{(float)$sku->cost_price ? $sku->cost_price : ''}}" oninput="value=value.replace(/[^\d.]/g, '')"></td>
                                <td>
                                    @if($sku->deletable)
                                        <button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="layui-card-body">
                <button type="button" class="layui-btn layui-btn-normal" lay-event="addSku">添加sku</button>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="product">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var categories = <?= json_encode($categories); ?>
                ,product = <?= isset($product) ? json_encode($product) : 'undefined'; ?>
                ,category_parent_ids = <?= isset($product) ? json_encode($product->category->parent_ids) : '[]'; ?>;
        layui.use(['form'], function () {
            var form = layui.form;

            // 分类下拉树
            xmSelect.render({
                el: '#category_id_select',
                name: 'category_id',
                layVerify: 'required',
                initValue: product && product.category_id ? [product.category_id] : '',
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

            $('button[lay-event=addSku]').on('click', function () {
                var $body = $('#skuTable').find('tbody')
                        ,html = ''
                        ,sku_flag = Date.now();
                html += '<tr>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][code]" placeholder="SKU编号(必填)" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][size]" placeholder="规格" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][model]" placeholder="型号" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][weight]" placeholder="重量" class="layui-input" oninput="value=value.replace(/[^\\d.]/g, \'\')">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][cost_price]" placeholder="成本价" class="layui-input" oninput="value=value.replace(/[^\\d.]/g, \'\')">';
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>';
                html += '</td>';
                html += '</tr>';

                $body.append(html);
            });

            form.on('submit(product)', function (form_data) {
                var sku_exists = false;
                $.each(form_data.field, function (key, val) {
                    if (new RegExp(/^skus\[[\d]+\]\[[\d\D]+\]$/).test(key)) {
                        sku_exists = true;
                        return false; // 跳出循环
                    }
                });

                if (!sku_exists) {
                    layer.msg("请至少添加一个SKU", {icon:2});
                    return false;
                }

                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('product::product.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("产品保存成功", {icon: 1, time:2000}, function () {
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("产品保存失败:"+data.msg, {icon: 2, time:2000});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(load_index);
                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon: 2, time:2000});
                        return false;
                    }
                });
            })
        });
    </script>
@endsection