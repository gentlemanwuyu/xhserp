@extends('layouts.default')
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('goods::single.select_product')}}" lay-text="选择产品[单品]">添加单品</a>
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('goods::combo.select_product')}}" lay-text="选择产品[组合]">添加组合</a>
@endsection