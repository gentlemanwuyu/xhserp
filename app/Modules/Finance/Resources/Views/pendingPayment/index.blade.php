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
                <select name="payment_method">
                    <option value="">付款方式</option>
                    @foreach(\App\Modules\Purchase\Models\Supplier::$payment_methods as $method_id => $method_name)
                        <option value="{{$method_id}}">{{$method_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
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
        }).use(['table', 'dropdown', 'form'], function () {
            var table = layui.table
                    ,dropdown = layui.dropdown
                    ,form = layui.form
                    ,tableOpts = {
                elem: '#list',
                url: "{{route('finance::pendingPayment.paginate')}}",
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
                        {field: 'code', title: '供应商编号', align: 'center', fixed: 'left'},
                        {field: 'name', title: '供应商名称', align: 'center', fixed: 'left'},
                        {field: 'total_amount', title: '应收金额', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            var total_amount = 0;
                            d.unpaid_items.forEach(function (item, key) {
                                total_amount += item.entry_quantity * item.price;
                            });
                            return total_amount;
                        }},
                        {field: 'total_remained_amount', title: '结余金额', width: 100, align: 'center'},
                        {field: 'payment_method_name', title: '付款方式', width: 100, align: 'center'},
                        {field: 'detail', title: '出货明细', width: 780, align: 'center', templet: function (d) {
                            var html = '';
                            d.unpaid_items.forEach(function (item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var amount = item.entry_quantity * item.price;
                                var entry_at = null == item.entry_at ? '' : item.entry_at;
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 150px;">' + item.order_code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 150px;">' + item.sku_code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.order_quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.entry_quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + amount.toFixed(2) + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + moment(entry_at).format('YYYY-MM-DD') + '</li>';
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
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">入库数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">价格</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">金额</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">入库日期</li>';
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
                            title: "抵扣",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('finance::pendingPayment.deduction')}}?supplier_id=" + data.id, '抵扣应付款[' + data.id + ']');
                            }
                        });

                        return actions;
                    });
                }
            };

            table.render(tableOpts);

            form.on('submit(search)', function (form_data) {
                tableOpts.where = form_data.field;
                table.render(tableOpts);
            });
        });
    </script>
@endsection