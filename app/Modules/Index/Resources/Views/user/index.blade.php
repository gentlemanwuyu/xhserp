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
            <div class="layui-col-xs2">
                <select name="status">
                    <option value="">状态</option>
                    @foreach(\App\Modules\Index\Models\User::$statuses as $status_id => $status_name)
                        <option value="{{$status_id}}">{{$status_name}}</option>
                    @endforeach
                </select>
            </div>
            @if(YES == \Auth::user()->is_admin)
                <div class="layui-col-xs2">
                    <select name="is_admin">
                        <option value="">是否管理员</option>
                        <option value="{{NO}}">否</option>
                        <option value="{{YES}}">是</option>
                    </select>
                </div>
            @endif
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                @can('add_user')
                <a class="layui-btn layui-btn-normal" lay-href="{{route('index::user.form')}}">添加用户</a>
                @endcan
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
                where: {status: User_ENABLED},
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
                    {field: 'birthday', title: '生日', width: 120, align: 'center'},
                    {field: 'telephone', title: '电话', width: 120, align: 'center'},
                    {field: 'roles', title: '角色', width: 220, align: 'center', templet: function (d) {
                        var roles_html = '';
                        d.roles.forEach(function (role) {
                            roles_html += '<span class="layui-badge layui-bg-green">' + role.display_name + '</span>';
                        });

                        return roles_html;
                    }},
                    {field: 'status_name', title: '状态', width: 100, align: 'center'},
                    @if(YES == \Auth::user()->is_admin)
                        {field: 'is_admin_name', title: '管理员', width: 80, align: 'center'},
                    @endif
                    {field: 'created_at', title: '创建时间', width: 160, align: 'center', sort: true},
                    {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center', sort: true},
                    {field: 'action', title: '操作', width: 100, align: 'center', toolbar: "#action"}
                ]]
                ,done: function(res, curr, count){
                    dropdown(res.data,function(data) {
                        var actions = [];

                        @can('user_detail')
                            actions.push({
                            title: "详情",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('index::user.detail')}}?user_id=" + data.id, '用户详情[' + data.id + ']');
                            }
                        });
                        @endcan

                        if (User_ENABLED == data.status) {
                            @can('assign_user_permission')
                            actions.push({
                                title: "分配权限",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('index::user.assign_permission')}}?user_id=" + data.id, '分配用户权限[' + data.id + ']');
                                }
                            });
                            @endcan
                            @can('disable_user')
                            actions.push({
                                title: "禁用",
                                event: function () {
                                    layer.confirm("确认要禁用该用户？", {icon: 3, title:"确认"}, function (index) {
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('index::user.disable')}}",
                                            data: {user_id: data.id},
                                            success: function (data) {
                                                layer.close(load_index);
                                                if ('success' == data.status) {
                                                    layer.msg("用户禁用成功", {icon: 1, time: 2000}, function () {
                                                        tableIns.reload();
                                                    });
                                                } else {
                                                    layer.msg("用户禁用失败:"+data.msg, {icon: 2, time: 2000});
                                                    return false;
                                                }
                                            },
                                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                layer.close(load_index);
                                                layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon: 2, time: 2000});
                                                return false;
                                            }
                                        });
                                    });
                                }
                            });
                            @endcan

                            @can('edit_user')
                            actions.push({
                                title: "编辑",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('index::user.form')}}?user_id=" + data.id, '编辑用户[' + data.id + ']');
                                }
                            });
                            @endcan
                        }

                        @if(YES == \Auth::user()->is_admin)
                        if (data.deletable) {
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
                                                    layer.msg("用户删除成功", {icon: 1, time: 2000}, function () {
                                                        tableIns.reload();
                                                    });
                                                } else {
                                                    layer.msg("用户删除失败:"+data.msg, {icon: 2, time: 2000});
                                                    return false;
                                                }
                                            },
                                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                layer.close(load_index);
                                                layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon: 2, time: 2000});
                                                return false;
                                            }
                                        });
                                    });
                                }
                            });
                        }
                        @endif

                        return actions;
                    });
                }
            }
                    ,tableIns = table.render(tableOpts);

            form.val('search', {status: User_ENABLED});

            form.on('submit(search)', function (form_data) {
                tableOpts.where = form_data.field;
                table.render(tableOpts);
            });
        });
    </script>
@endsection