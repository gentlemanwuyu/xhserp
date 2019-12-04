@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        #skuTable tbody td{padding: 0;}
        #skuTable .layui-input{border: 0;}
    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="product">
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
                        <th rowspan="2" class="required">SKU编号</th>
                        <th colspan="{{count($products)}}">产品列表</th>
                        <th rowspan="2" class="required">最低售价</th>
                        <th rowspan="2">建议零售价</th>
                        <th rowspan="2">操作</th>
                    </tr>
                    <tr>
                        @foreach($products as $product)
                            <th width="200" class="required">{{$product->name}}({{$product->quantity}})</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>

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
        layui.use(['form'], function () {
            var form = layui.form;
            $('button[lay-event=addSku]').on('click', function () {
                var $body = $('#skuTable').find('tbody')
                        ,html = ''
                        ,sku_flag = Date.now();
                html += '<tr>';
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][code]" placeholder="SKU编号（必填）" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input">';
                html += '</td>';
                @foreach($products as $product)
                    html += '<td>';
                    html += '<select name="" lay-verify="required" lay-reqText="请选择产品SKU">';
                    html += '<option value="">请选择产品SKU</option>';
                    @foreach($product->skus as $sku)
                        html += '<option value="{{$sku->id}}">{{$sku->code}}</option>';
                    @endforeach
                    html += '</select>';
                    html += '</td>';
                @endforeach
                html += '<td>';
                html += '<input type="text" name="skus[' + sku_flag + '][lowest_price]" placeholder="最低售价" class="layui-input">';
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
        });
    </script>
@endsection