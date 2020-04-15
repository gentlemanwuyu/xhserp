@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        #skuTable tbody td{padding: 0;}
        #skuTable tbody tr{height: 40px;}
        #skuTable .layui-input{border: 0;}
    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="combo">
        @if(isset($goods_id))
            <input type="hidden" name="goods_id" value="{{$goods_id}}">
        @else
            @foreach($products as $product)
                <input type="hidden" name="product_ids[{{$product->id}}]" value="{{$product->quantity}}">
            @endforeach
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
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入商品编号" class="layui-input" value="{{$goods->code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">商品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入商品名称" class="layui-input" value="{{$goods->name or ''}}">
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
                                <textarea name="desc" class="layui-textarea">{{$goods->desc or ''}}</textarea>
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
                        <th rowspan="2" class="required">SKU编号</th>
                        <th colspan="{{count($products)}}">产品列表</th>
                        <th rowspan="2" class="required">最低售价</th>
                        <th rowspan="2">建议零售价</th>
                        <th rowspan="2">操作</th>
                    </tr>
                    <tr>
                        @foreach($products as $product)
                            <th width="150" class="required">{{$product->name}}({{$product->quantity}})</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($goods))
                        @foreach($goods->skus as $sku)
                            <tr>
                                <td><input type="text" name="skus[{{$sku->id}}][code]" placeholder="SKU编号" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input" value="{{$sku->code}}"></td>
                                @foreach($products as $product)
                                    <td>
                                        <select name="skus[{{$sku->id}}][parts][{{$product->id}}]" lay-verify="required" lay-reqText="请选择产品SKU">
                                            <option value="">请选择产品SKU</option>
                                            @foreach($product->skus as $product_sku)
                                                <option value="{{$product_sku->id}}" @if($sku->getProductSkuId($product->id) == $product_sku->id) selected @endif>{{$product_sku->code}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endforeach
                                <td><input type="text" name="skus[{{$sku->id}}][lowest_price]" placeholder="最低售价" lay-verify="required" lay-reqText="请输入最低售价" class="layui-input" value="{{$sku->lowest_price}}"></td>
                                <td><input type="text" name="skus[{{$sku->id}}][msrp]" placeholder="建议零售价" class="layui-input" value="{{(float)$sku->msrp ? $sku->msrp : ''}}"></td>
                                <td><button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button></td>
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
        <button type="button" class="layui-btn" lay-submit lay-filter="combo">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var categories = <?= json_encode($categories); ?>
                ,goods = <?= isset($goods) ? json_encode($goods) : 'undefined'; ?>
                ,category_parent_ids = <?= isset($goods) ? json_encode($goods->category->parent_ids) : '[]'; ?>;
        layui.use(['form'], function () {
            var form = layui.form;
            $('button[lay-event=addSku]').on('click', function () {
                var $body = $('#skuTable').find('tbody')
                        ,html = ''
                        ,sku_flag = Date.now();
                html += '<tr>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][code]" placeholder="SKU编号" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input">';
                html += '</td>';
                @foreach($products as $product)
                    html += '<td>';
                    html += '<select name="skus[' + sku_flag + '][parts][{{$product->id}}]" lay-verify="required" lay-reqText="请选择产品SKU">';
                    html += '<option value="">请选择产品SKU</option>';
                    @foreach($product->skus as $sku)
                        html += '<option value="{{$sku->id}}">{{$sku->code}}</option>';
                    @endforeach
                    html += '</select>';
                    html += '</td>';
                @endforeach
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][lowest_price]" placeholder="最低售价" lay-verify="required" lay-reqText="请输入最低售价" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][msrp]" placeholder="建议零售价" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>';
                html += '</td>';
                html += '</tr>';

                $body.append(html);
                form.render();
            });

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

            form.on('submit(combo)', function (form_data) {
                var sku_exists = false;
                $.each(form_data.field, function (key, val) {
                    if (new RegExp(/^skus\[[\d]+\]\[[\d\D]+\]$/).test(key)) {
                        sku_exists = true;
                        return false; // 跳出循环
                    }
                });

                if (!sku_exists) {
                    layer.msg("请至少添加一个SKU", {icon: 5, shift: 6});
                    return false;
                }

                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('goods::combo.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("商品添加成功", {icon: 1, time: 2000}, function(){
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("商品添加失败:"+data.msg, {icon:2});
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