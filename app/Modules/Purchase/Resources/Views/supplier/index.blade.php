@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <input type="text" name="code" placeholder="供应商编号" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="name" placeholder="供应商名称" class="layui-input">
            </div>
            <div class="layui-col-xs2">
                <select name="payment_method" lay-search="">
                    <option value="">付款方式</option>
                    @foreach(\App\Modules\Purchase\Models\Supplier::$payment_methods as $method_id => $method_name)
                        <option value="{{$method_id}}">{{$method_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="created_at_between" placeholder="创建时间" class="layui-input" autocomplete="off">
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                @can('add_supplier')
                <a class="layui-btn layui-btn-normal" lay-href="{{route('purchase::supplier.form')}}">添加供应商</a>
                @endcan
            </div>
        </div>
    </form>
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
        }).use(['table', 'dropdown', 'laydate', 'form'], function () {
            var table = layui.table
                    ,dropdown = layui.dropdown
                    ,laydate = layui.laydate
                    ,form = layui.form
                    ,tableOpts = {
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
                        {field: 'tax_name', title: '税率', width: 100, align: 'center'},
                        {field: 'currency_name', title: '币种', width: 100, align: 'center', templet: function (d) {
                            return d.currency.name;
                        }},
                        {field: 'payment_method_name', title: '付款方式', width: 100, align: 'center'},
                        {field: 'monthly_day', title: '月结天数', width: 100, align: 'center', templet: function (d) {
                            return 3 == d.payment_method ? d.monthly_day : '';
                        }},
                        {field: 'detail', title: '联系人', width: 400, align: 'center', templet: function (d) {
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
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 100px; text-align: center;">名称</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">职位</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">电话</li>';
                        html += '</ul>';
                        $('th[data-field=detail]').append(html);
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

                        @can('edit_supplier')
                        actions.push({
                            title: "编辑",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('purchase::supplier.form')}}?supplier_id=" + data.id, '编辑供应商[' + data.id + ']');
                            }
                        });
                        @endcan

                        @can('delete_supplier')
                        actions.push({
                            title: "删除",
                            event: function() {
                                layer.confirm("确认要删除该供应商？", {icon: 3, title:"确认"}, function (index) {
                                    layer.close(index);
                                    var load_index = layer.load();
                                    $.ajax({
                                        method: "post",
                                        url: "{{route('purchase::supplier.delete')}}",
                                        data: {supplier_id: data.id},
                                        success: function (data) {
                                            layer.close(load_index);
                                            if ('success' == data.status) {
                                                layer.msg("供应商删除成功", {icon:1});
                                                tableIns.reload();
                                            } else {
                                                layer.msg("供应商删除失败:"+data.msg, {icon:2});
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
                        @endcan

                        return actions;
                    });
                }
            }
                    ,tableIns = table.render(tableOpts);

            laydate.render({
                elem: 'input[name=created_at_between]'
                ,range: true
            });

            form.on('submit(search)', function (form_data) {
                tableOpts.where = form_data.field;
                table.render(tableOpts);
            });
        });
    </script>
@endsection