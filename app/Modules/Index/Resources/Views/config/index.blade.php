@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="key" placeholder="键" class="layui-input">
            </div>
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                @if(YES == \Auth::user()->is_admin)
                <a class="layui-btn layui-btn-normal" lay-href="{{route('index::config.form')}}">添加配置</a>
                @endif
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
                url: "{{route('index::config.paginate')}}",
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
                    {field: 'key', title: '键', align: 'center'},
                    {field: 'value', title: '值', align: 'center'},
                    {field: 'user_name', title: '创建人', width: '5%', align: 'center', templet: function (d) {
                        return d.user.name;
                    }},
                    {field: 'created_at', title: '创建时间', align: 'center', sort: true},
                    {field: 'updated_at', title: '最后更新时间', align: 'center', sort: true},
                    {field: 'action', title: '操作', width: 100, align: 'center', toolbar: "#action"}
                ]]
                ,done: function(res, curr, count){
                    dropdown(res.data,function(data) {
                        var actions = [];

                        @if(YES == \Auth::user()->is_admin)
                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('index::config.form')}}?config_id=" + data.id, '编辑配置[' + data.id + ']');
                            }
                        });
                        @endif

                        @if(YES == \Auth::user()->is_admin)
                        actions.push({
                            title: "删除",
                            event: function() {
                                layer.confirm("确认要删除该配置？", {icon: 3, title:"确认"}, function (index) {
                                    layer.close(index);
                                    var load_index = layer.load();
                                    $.ajax({
                                        method: "post",
                                        url: "{{route('index::config.delete')}}",
                                        data: {config_id: data.id},
                                        success: function (data) {
                                            layer.close(load_index);
                                            if ('success' == data.status) {
                                                layer.msg("配置删除成功", {icon: 1, time: 2000}, function () {
                                                    tableIns.reload();
                                                });
                                            } else {
                                                layer.msg("配置删除失败:" + data.msg, {icon: 2, time: 2000});
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
                        @endif

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