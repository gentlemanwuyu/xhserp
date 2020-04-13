@extends('layouts.default')
@section('css')
    <style>
        .layui-badge{font-size: 14px;}
        .layui-badge+.layui-badge{margin-left: 5px;}
    </style>
@endsection
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="email" placeholder="邮箱" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="name" placeholder="用户名" class="layui-input">
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                <a class="layui-btn layui-btn-normal" lay-href="{{route('index::user.form')}}">添加用户</a>
            </div>
        </div>
    </form>
    <table id="list"  lay-filter="list"></table>
    <script type="text/html" id="action">
        <div class="urp-dropdown urp-dropdown-table" title="操作">
            <i class="layui-icon layui-icon-more-vertical urp-dropdown-btn"></i>
        </div>
    </script>
@endsection
@section('scripts')
    <script>
        layui.extend({
            dropdown: '/assets/layui-table-dropdown/dropdown'
        }).use(['table', 'dropdown', 'form'], function(){
            var table = layui.table
                    ,dropdown = layui.dropdown
                    ,form = layui.form
                    ,tableOpts = {
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
                    {field: 'id', title: 'ID', width: 60, align: 'center', sort: true, fixed: 'left'},
                    {field: 'name', title: '用户名', align: 'center'},
                    {field: 'email', title: '邮箱', width: 200, align: 'center'},
                    {field: 'gender', title: '性别', width: 60, align: 'center'},
                    {field: 'birthday', title: '生日', align: 'center'},
                    {field: 'telephone', title: '电话', align: 'center'},
                    {field: 'roles', title: '角色', width: 220, align: 'center', templet: function (d) {
                        var roles_html = '';
                        d.roles.forEach(function (role) {
                            roles_html += '<span class="layui-badge layui-bg-green">' + role.display_name + '</span>';
                        });

                        return roles_html;
                    }},
                    {field: 'created_at', title: '创建时间', align: 'center', sort: true},
                    {field: 'updated_at', title: '最后更新时间', align: 'center', sort: true},
                    {field: 'action', title: '操作', width: 100, align: 'center', toolbar: "#action"}
                ]]
                ,done: function(res, curr, count){
                    dropdown(res.data,function(data) {
                        var actions = [];
                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('index::user.form')}}?user_id=" + data.id, '编辑用户[' + data.id + ']');
                            }
                        });

                        actions.push({
                            title: "删除",
                            event: function() {
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

                        return actions;
                    });
                }
            }
                    ,tableIns = table.render(tableOpts);

            form.on('submit(search)', function (form_data) {
                tableOpts.where = form_data.field;
                table.render(tableOpts);
            });
        });
    </script>
@endsection