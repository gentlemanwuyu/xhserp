@extends('layouts.default')
@section('css')
    <style>
        th[data-field=detail] .layui-table-cell{height: 38px;padding-top: 5px; padding-bottom: 5px;}
        th[data-field=detail], td[data-field=detail]{padding: 0!important;}
        td[data-field=detail] .layui-table-cell{padding: 0!important;}
        td[data-field=detail] .layui-table-cell{height: auto;}
    </style>
@endsection
@section('content')
    <a class="layui-btn layui-btn-normal" lay-href="{{route('finance::account.form')}}">添加账户</a>
    <table id="list" class="layui-table"  lay-filter="list">

    </table>
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
        }).use(['table', 'dropdown'], function () {
            var table = layui.table
                    ,dropdown = layui.dropdown
                    ,tableOpts = {
                elem: '#list',
                url: "{{route('finance::account.paginate')}}",
                page: true,
                parseData: function (res) {
                    return {
                        "code": 0,
                        "msg": '',
                        "count": res.total,
                        "data": res.data
                    };
                },
                cols: [
                    [
                        {field: 'id', title: 'ID', width: 60, align: 'center', fixed: 'left'},
                        {field: 'name', title: '账户名称', align: 'center'},
                        {field: 'bank', title: '银行', align: 'center'},
                        {field: 'branch', title: '分支行', align: 'center'},
                        {field: 'payee', title: '收款人', align: 'center'},
                        {field: 'account', title: '账号', align: 'center'},
                        {field: 'action', title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    dropdown(res.data,function(data) {
                        var actions = [];
                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('finance::account.form')}}?account_id=" + data.id, '编辑账户[' + data.id + ']');
                            }
                        });

                        actions.push({
                            title: "删除",
                            event: function() {
                                layer.confirm("确认要删除该账户？", {icon: 3, title:"确认"}, function (index) {
                                    layer.close(index);
                                    var load_index = layer.load();
                                    $.ajax({
                                        method: "post",
                                        url: "{{route('finance::account.delete')}}",
                                        data: {account_id: data.id},
                                        success: function (data) {
                                            layer.close(load_index);
                                            if ('success' == data.status) {
                                                layer.msg("账户删除成功", {icon: 1, time: 2000});
                                                tableIns.reload(tableOpts);
                                            } else {
                                                layer.msg("账户删除失败:"+data.msg, {icon: 2, time: 2000});
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

                        return actions;
                    });
                }
            }
                    ,tableIns = table.render(tableOpts);
        });
    </script>
@endsection