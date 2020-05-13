@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="name" placeholder="角色名" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="display_name" placeholder="显示名称" class="layui-input">
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                @can('add_role')
                <a class="layui-btn layui-btn-normal" lay-href="{{route('index::role.form')}}">添加角色</a>
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
                url: "{{route('index::role.paginate')}}",
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
                    {field: 'name', title: '角色名', align: 'center'},
                    {field: 'display_name', title: '显示名称', align: 'center'},
                    {field: 'created_at', title: '创建时间', align: 'center', sort: true},
                    {field: 'updated_at', title: '最后更新时间', align: 'center', sort: true},
                    {field: 'action', title: '操作', width: 100, align: 'center', toolbar: "#action"}
                ]]
                ,done: function(res, curr, count){
                    dropdown(res.data,function(data) {
                        var actions = [];

                        @can('role_detail')
                        actions.push({
                            title: "详情",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('index::role.detail')}}?role_id=" + data.id, '角色详情[' + data.id + ']');
                            }
                        });
                        @endcan

                        @can('edit_role')
                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('index::role.form')}}?role_id=" + data.id, '编辑角色[' + data.id + ']');
                            }
                        });
                        @endcan

                        if (data.deletable) {
                            @can('delete_role')
                            actions.push({
                                title: "删除",
                                event: function() {
                                    layer.confirm("确认要删除该角色？", {icon: 3, title:"确认"}, function (index) {
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('index::role.delete')}}",
                                            data: {role_id: data.id},
                                            success: function (data) {
                                                layer.close(load_index);
                                                if ('success' == data.status) {
                                                    layer.msg("角色删除成功", {icon: 1, time: 2000}, function () {
                                                        tableIns.reload();
                                                    });
                                                } else {
                                                    layer.msg("角色删除失败:"+data.msg, {icon: 2, time: 2000});
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
                        }

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