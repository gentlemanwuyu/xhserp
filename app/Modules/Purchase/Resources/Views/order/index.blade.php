@extends('layouts.default')
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('purchase::order.form')}}">添加订单</a>
    <table id="list" class="layui-table"  lay-filter="list">

    </table>
    <script type="text/html" id="action">
        <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete">删除</a>
    </script>
@endsection