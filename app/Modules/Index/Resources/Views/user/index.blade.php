@extends('layouts.default')
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('index::user.form')}}">添加用户</a>
    <table id="list"  lay-filter="list"></table>
    <script type="text/html" id="action">
        <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete">删除</a>
    </script>
@endsection
@section('scripts')
    <script>
        layui.use(['table'], function(){
            var table = layui.table
                    ,tableIns = table.render({
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
                    {field: 'gender', title: '性别', width: '5%', align: 'center'},
                    {field: 'birthday', title: '生日', align: 'center'},
                    {field: 'telephone', title: '电话', align: 'center'},
                    {field: 'created_at', title: '创建时间', align: 'center', sort: true},
                    {field: 'updated_at', title: '最后更新时间', align: 'center', sort: true},
                    {field: 'action', title: '操作', width: '15%', align: 'center', toolbar: "#action"}
                ]]
            });

            table.on('tool(list)', function(obj){
                var data = obj.data;

                if ('edit' == obj.event) {
                    parent.layui.index.openTabsPage("{{route('index::user.form')}}?user_id=" + data.id, '修改用户[' + data.id + ']');
                }else if ('delete' == obj.event) {
                    layer.confirm("确认要删除该用户？", {icon: 3, title:"确认"}, function (index) {
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('index::user.delete')}}",
                            data: {user_id: data.id},
                            success: function (data) {
                                layer.close(load_index);
                                if ('success' == data.status) {
                                    layer.msg("用户删除成功", {icon:1});
                                    tableIns.reload();
                                } else {
                                    layer.msg("用户删除失败:"+data.msg, {icon:2});
                                    return false;
                                }
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                layer.close(load_index);
                                layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                                return false;
                            }
                        });
                    });
                }
            });
        });
    </script>
@endsection