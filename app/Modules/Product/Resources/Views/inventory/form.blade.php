@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        .layui-table td{padding: 0;}
        .layui-table .layui-input{border: 0; text-align: center;}
    </style>
@endsection
@section('content')
    <div class="layui-row">
        <div class="layui-col-xs6">
            <form class="layui-form" lay-filter="inventory">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th width="150">SKU编号</th>
                        <th>库存数量</th>
                        <th>最高库存</th>
                        <th>最低库存</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($product->skus as $sku)
                        <tr>
                            <td>{{$sku->code}}</td>
                            <td><input type="text" name="stock" class="layui-input" placeholder="库存数量" value=""></td>
                            <td><input type="text" name="highest_stock" class="layui-input" placeholder="最高库存" value=""></td>
                            <td><input type="text" name="lowest_stock" class="layui-input" placeholder="最低库存" value=""></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button type="button" class="layui-btn" lay-submit lay-filter="inventory">提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </form>
        </div>
    </div>
@endsection