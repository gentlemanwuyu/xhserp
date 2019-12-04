@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
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
                        <th rowspan="2" width="5%">是否开启</th>
                        <th colspan="3">产品信息</th>
                        <th rowspan="2">SKU编号</th>
                        <th rowspan="2">最低售价</th>
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
                            <tr>
                                <td><input type="checkbox" name="switch" lay-skin="switch" lay-text="是|否" value="1"></td>
                                <td>{{$sku->code or ''}}</td>
                                <td>{{(float)$sku->weight ? $sku->weight : ''}}</td>
                                <td>{{(float)$sku->cost_price ? $sku->weight : ''}}</td>
                                <td><input type="text" name="skus[{{$sku->id}}][code]" placeholder="SKU编号（必填）" lay-verify="required" lay-reqText="请输入SKU编号" class="layui-input" value="{{$sku->code or ''}}"></td>
                                <td><input type="text" name="skus[{{$sku->id}}][lowest_price]" placeholder="最低售价(必填)" lay-verify="required" lay-reqText="请输入最低售价" class="layui-input" value=""></td>
                                <td><input type="text" name="skus[{{$sku->id}}][msrp]" placeholder="建议零售价" class="layui-input" value=""></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
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

        });
    </script>
@endsection