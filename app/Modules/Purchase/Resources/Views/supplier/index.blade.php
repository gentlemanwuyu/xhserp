@extends('layouts.default')
@section('css')
    <style>
        th[data-field=contacts] .layui-table-cell{height: 38px;padding-top: 5px; padding-bottom: 5px;}
        th[data-field=contacts], td[data-field=contacts]{padding: 0!important;}
        td[data-field=contacts] .layui-table-cell{padding: 0!important;}
        td[data-field=contacts] .layui-table-cell{height: auto;}
    </style>
@endsection
@section('content')
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('purchase::supplier.form')}}">添加供应商</a>
    <table id="list" class="layui-table"  lay-filter="list">

    </table>
    <script type="text/html" id="action">
        <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete">删除</a>
    </script>
@endsection
@section('scripts')
    <script>
        layui.use(['table'], function () {
            var table = layui.table
                    ,tableIns = table.render({
                elem: '#list',
                url: "{{route('purchase::supplier.paginate')}}",
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
                        {field: 'id', title: 'ID', width: '5%', align: 'center'},
                        {field: 'name', title: '名称', align: 'center'},
                        {field: 'code', title: '编号', align: 'center'},
                        {field: 'company', title: '公司', width: 250, align: 'center'},
                        {field: 'contacts', title: '联系人', width: 400, align: 'center', templet: function (d) {
                            var html = '';
                            d.contacts.forEach(function (contact, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 100px;">' + contact.name + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;">' + contact.position + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;">' + contact.phone + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', align: 'center'},
                        {field: 'action', title: '操作', width: '10%', align: 'center', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改联系人列表表头
                    if (0 == $('th[data-field=contacts] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 100px; text-align: center;">名称</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">职位</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">电话</li>';
                        html += '</ul>';
                        $('th[data-field=contacts]').append(html);
                    }

                    // 修改固定列的各行高度
                    $('.layui-table-fixed .layui-table-header thead tr').each(function () {
                        var header_height = $(this).parents('.layui-table-box').children('.layui-table-header').find('table thead tr').css('height');
                        $(this).css('height', header_height);
                    });

                    $('.layui-table-fixed .layui-table-body tbody tr').each(function () {
                        var $this = $(this)
                                ,data_index = $this.attr('data-index')
                                ,tr_height = $this.parents('.layui-table-box').children('.layui-table-body').find('table tbody tr[data-index=' + data_index + ']').css('height');
                        $(this).css('height', tr_height);
                    });
                }
            });

            table.on('tool(list)', function(obj){
                var data = obj.data;

                if ('edit' == obj.event) {
                    parent.layui.index.openTabsPage("{{route('purchase::supplier.form')}}?supplier_id=" + data.id, '编辑供应商[' + data.id + ']');
                }else if ('delete' == obj.event) {
                    layer.confirm("确认要删除该产品？", {icon: 3, title:"确认"}, function (index) {
                        layer.close(index);
                        var load_index = layer.load();
                        $.ajax({
                            method: "post",
                            url: "{{route('product::product.delete')}}",
                            data: {product_id: data.id},
                            success: function (data) {
                                layer.close(load_index);
                                if ('success' == data.status) {
                                    layer.msg("产品删除成功", {icon:1});
                                    tableIns.reload();
                                } else {
                                    layer.msg("产品删除失败:"+data.msg, {icon:2});
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