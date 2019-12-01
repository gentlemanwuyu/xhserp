@extends('layouts.default')
@section('css')
    <style>
        .layui-table, .layui-table th, .layui-table td {
            text-align: center;
        }
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
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>SKU编号</th>
                        <th>重量</th>
                        <th>成本价</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>贤心</td>
                        <td>2016-11-29</td>
                        <td>人生就像是一场修行</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-card-body">
                <button type="button" class="layui-btn layui-btn-normal">添加sku</button>
            </div>
        </div>
    </form>
@endsection