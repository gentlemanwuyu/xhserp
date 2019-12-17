@extends('layouts.default')
@section('content')
    <a class="layui-btn layui-btn-normal" erp-action="add">添加快递</a>
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
                url: "{{route('warehouse::express.paginate')}}",
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
                        {field: 'name', title: '名称', align: 'center', fixed: 'left'},
                        {field: 'creator', title: '创建人', align: 'center', fixed: 'left', templet: function (d) {
                            return d.user.name;
                        }},
                        {field: 'created_at', title: '创建时间', align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', align: 'center'},
                        {field: 'action', title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    dropdown(res.data,function(data) {
                        return [
                            {
                                title: "编辑",
                                event: function () {
                                    layer.prompt({
                                        title: '编辑快递',
                                        value: data.name
                                    }, function(value, index, elem){
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('warehouse::express.save')}}",
                                            data: {name: value, express_id: data.id},
                                            success: function (data) {
                                                layer.close(load_index);
                                                if ('success' == data.status) {
                                                    layer.msg("快递编辑成功", {icon: 1, time: 2000});
                                                    tableIns.reload();
                                                } else {
                                                    layer.msg("快递编辑失败:"+data.msg, {icon: 2, time: 2000});
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
                            },
                            {
                                title: "删除",
                                event: function() {
                                    layer.confirm("确认要删除该快递？", {icon: 3, title:"确认"}, function (index) {
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('warehouse::express.delete')}}",
                                            data: {express_id: data.id},
                                            success: function (data) {
                                                layer.close(load_index);
                                                if ('success' == data.status) {
                                                    layer.msg("快递删除成功", {icon: 1, time: 2000});
                                                    tableIns.reload();
                                                } else {
                                                    layer.msg("快递删除失败:"+data.msg, {icon: 2, time: 2000});
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
                            }
                        ];
                    });
                }
            }
                    ,tableIns = table.render(tableOpts);

            $('a[erp-action=add]').on('click', function () {
                layer.prompt({
                    title: '添加快递'
                }, function(value, index, elem){
                    layer.close(index);
                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('warehouse::express.save')}}",
                        data: {name: value},
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("快递添加成功", {icon: 1, time: 2000});
                                tableIns.reload();
                            } else {
                                layer.msg("快递添加失败:"+data.msg, {icon: 2, time: 2000});
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
            });
        });
    </script>
@endsection