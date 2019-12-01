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
                            <label class="layui-form-label required">产品编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入产品编号" class="layui-input" value="">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">产品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入产品名称" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">分类</label>
                            <div class="layui-input-block">
                                <select name="category_id" lay-filter="category" lay-search="">
                                    <option value="">请选择分类</option>
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">产品描述</label>
                            <div class="layui-input-block">
                                <textarea name="desc" class="layui-textarea"></textarea>
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
                        <th>SKU编号</th>
                        <th>重量</th>
                        <th>成本价</th>
                        <th>操作</th>
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
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;
            $('button[lay-event=addSku]').on('click', function () {
                var $body = $('#skuTable').find('tbody')
                        ,html = '';
                html += '<tr>';
                html += '<td>';
                html += '<input type="text" name="" placeholder="SKU编号" lay-verify="required" lay-reqText="" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="" placeholder="重量" lay-verify="required" lay-reqText="" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="" placeholder="成本价" lay-verify="required" lay-reqText="" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>';
                html += '</td>';
                html += '</tr>';

                $body.append(html);
            });
        });
    </script>
@endsection