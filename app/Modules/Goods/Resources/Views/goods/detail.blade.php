@extends('layouts.default')
@section('content')
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>基本信息</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>商品类型</td>
                            <td>{{$goods->type_name}}</td>
                        </tr>
                        <tr>
                            <td>商品编号</td>
                            <td>{{$goods->code}}</td>
                        </tr>
                        <tr>
                            <td>商品名称</td>
                            <td>{{$goods->name}}</td>
                        </tr>
                        <tr>
                            <td>分类</td>
                            <td>{{$goods->category->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>商品描述</td>
                            <td>{{$goods->desc}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>SKU列表</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>SKU编号</th>
                    <th>最低售价</th>
                    <th>建议零售价</th>
                </tr>
                </thead>
                <tbody>
                @foreach($goods->skus as $goods_sku)
                    <tr>
                        <td>{{$goods_sku->code}}</td>
                        <td>{{$goods_sku->lowest_price}}</td>
                        <td>{{$goods_sku->msrp}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection