@extends('layouts.default')
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal">添加用户</a>
    <table id="list"  lay-filter="list"></table>
    <script type="text/html" id="action">
        <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>修改</a>
        <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete"><i class="layui-icon layui-icon-delete"></i>删除</a>
    </script>
@endsection
@section('scripts')
    <script>
        layui.use('table', function(){
            var table = layui.table;
            table.render({
                elem: '#list',
                url: "{{route('index::user.paginate')}}",
                page: true,
                parseData: function (res) {
                    return {
                        "code": 0,
                        "msg": '',
                        "count": res.total,
                        "data": res.data
                    };
                },
                cols: [[
                    {field: 'id', title: 'ID', width: '5%', align: 'center', sort: true, fixed: 'left'},
                    {field: 'name', title: '用户名', align: 'center'},
                    {field: 'email', title: '邮箱', align: 'center'},
                    {field: 'gender', title: '性别', align: 'center'},
                    {field: 'birthday', title: '生日', align: 'center'},
                    {field: 'telephone', title: '电话', align: 'center'},
                    {field: 'created_at', title: '创建时间', align: 'center', sort: true},
                    {field: 'updated_at', title: '最后更新时间', align: 'center', sort: true},
                    {field: 'action', title: '操作', width: '15%', align: 'center', toolbar: "#action"}
                ]]
            });
        });
    </script>
@endsection