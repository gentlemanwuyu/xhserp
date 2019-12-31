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
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-href="{{route('sale::customer.form')}}">添加客户</a>
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
                    ,tableIns = table.render({
                elem: '#list',
                url: "{{route('sale::customer.paginate')}}",
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
                        {field: 'payment_method_name', title: '付款方式', width: 100, align: 'center'},
                        {field: 'monthly_day', title: '额度', width: 100, align: 'center', templet: function (d) {
                            return 2 == d.payment_method ? d.credit : '';
                        }},
                        {field: 'monthly_day', title: '月结天数', width: 100, align: 'center', templet: function (d) {
                            return 3 == d.payment_method ? d.monthly_day : '';
                        }},
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
                        {field: 'action', title: '操作', width: 100, align: 'center', toolbar: "#action"}
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

                    dropdown(res.data,function(data) {
                        var actions = [];
                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('sale::customer.form')}}?customer_id=" + data.id, '编辑客户[' + data.id + ']');
                            }
                        });

                        actions.push({
                            title: "删除",
                            event: function() {
                                layer.confirm("确认要删除该客户？", {icon: 3, title:"确认"}, function (index) {
                                    layer.close(index);
                                    var load_index = layer.load();
                                    $.ajax({
                                        method: "post",
                                        url: "{{route('sale::customer.delete')}}",
                                        data: {customer_id: data.id},
                                        success: function (data) {
                                            layer.close(load_index);
                                            if ('success' == data.status) {
                                                layer.msg("客户删除成功", {icon:1});
                                                tableIns.reload();
                                            } else {
                                                layer.msg("客户删除失败:"+data.msg, {icon:2});
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
            });
        });
    </script>
@endsection