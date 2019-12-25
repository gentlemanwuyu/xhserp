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
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
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
                url: "{{route('finance::pendingCollection.paginate')}}",
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
                        {field: 'code', title: '客户编号', align: 'center', fixed: 'left'},
                        {field: 'name', title: '客户名称', align: 'center', fixed: 'left'},
                        {field: 'total_amount', title: '总金额', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            var total_amount = 0;
                            d.unpaid_items.forEach(function (item, key) {
                                total_amount += item.delivery_quantity * item.price;
                            });
                            return total_amount.toFixed(2);
                        }},
                        {field: 'payment_method_name', title: '付款方式', width: 100, align: 'center'},
                        {field: 'detail', title: '出货明细', width: 780, align: 'center', templet: function (d) {
                            var html = '';
                            d.unpaid_items.forEach(function (item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var amount = item.delivery_quantity * item.price;
                                var delivery_at = null == item.delivery_at ? '' : item.delivery_at;
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 150px;">' + item.order_code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;">' + item.sku_code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.order_quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.delivery_quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + amount.toFixed(2) + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + moment(delivery_at).format('YYYY-MM-DD') + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'action', title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 150px; text-align: center;">订单号</li>';
                        html += '<li class="erp-table-list-li" style="width: 150px; text-align: center;">SKU</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">订单数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">出货数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">价格</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">金额</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">出货日期</li>';
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
                        actions.push({
                            title: "收款",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('finance::collection.form')}}?customer_id=" + data.id, '收款单[' + data.id + ']');
                            }
                        });

                        return actions;
                    });
                }
            };

            table.render(tableOpts);

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