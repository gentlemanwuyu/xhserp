@extends('layouts.default')
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('goods::goods.select_product', ['type' => 1])}}" lay-text="选择产品[单品]">添加单品</a>
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('goods::goods.select_product', ['type' => 2])}}" lay-text="选择产品[组合]">添加组合</a>
@endsection