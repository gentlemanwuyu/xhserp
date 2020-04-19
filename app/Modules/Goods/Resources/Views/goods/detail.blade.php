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
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>销售记录</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <form class="layui-form" lay-filter="search">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs2">
                        <input type="text" name="order_code" placeholder="订单编号" class="layui-input">
                    </div>
                    <div class="layui-col-xs2">
                        <select name="customer_id" lay-search="">
                            <option value="">客户</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="layui-col-xs2">
                        <input type="text" name="created_at_between" placeholder="创建时间" class="layui-input" autocomplete="off">
                    </div>
                    <div class="layui-col-xs4">
                        <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
            <table id="list" class="layui-table"  lay-filter="list">

            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        layui.use(['table', 'laydate', 'form'], function () {
            var table = layui.table
                    ,laydate = layui.laydate
                    ,form = layui.form
                    ,tableOpts = {
                elem: '#list',
                url: "{{route('goods::goods.order_paginate')}}?goods_id={{$goods_id}}",
                page: true,
                parseData: function (res) {
                    return {
                        "code": 0,
                        "msg": '',
                        "count": res.total,
                        "data": res.data
                    };
                },
                cols: [
                    [
                        {field: 'id', title: 'ID', width: 60, align: 'center', fixed: 'left'},
                        {field: 'sku_code', title: 'SKU编号', width: 160, align: 'center', fixed: 'left', templet: function (d) {
                            return d.sku.code;
                        }},
                        {field: 'order_code', title: '订单编号', width: 160, align: 'center', fixed: 'left', templet: function (d) {
                            return d.order.code;
                        }},
                        {field: 'order_status', title: '订单状态', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.order.status_name;
                        }},
                        {field: 'customer_name', title: '客户', width: 120, align: 'center', fixed: 'left', templet: function (d) {
                            return d.order.customer.name;
                        }},
                        {field: 'title', title: '品名', align: 'center', fixed: 'left'},
                        {field: 'unit', title: '单位', width: 60, align: 'center', fixed: 'left'},
                        {field: 'quantity', title: '数量', width: 100, align: 'center', fixed: 'left'},
                        {field: 'price', title: '单价', width: 100, align: 'center', fixed: 'left'},
                        {field: 'amount', title: '金额', width: 120, align: 'center', templet: function (d) {
                            return d.price * d.quantity;
                        }},
                        {field: 'created_at', title: '创建时间', width: 160, align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center'}
                    ]
                ]
            }
                    ,tableIns = table.render(tableOpts);

            laydate.render({
                elem: 'input[name=created_at_between]'
                ,range: true
            });

            form.on('submit(search)', function (form_data) {
                tableOpts.where = form_data.field;
                table.render(tableOpts);
            });
        });
    </script>
@endsection