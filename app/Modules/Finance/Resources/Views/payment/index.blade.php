@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <select name="customer_id" lay-search="">
                    <option value="">供应商</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <select name="creator_id" lay-search="">
                    <option value="">创建人</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <input type="text" name="created_at_between" placeholder="创建时间" class="layui-input">
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs4">
                <button type="button" class="layui-btn" lay-submit lay-filter="search">搜索</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                <a class="layui-btn layui-btn-normal" lay-href="{{route('finance::payment.form')}}">添加付款单</a>
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
                url: "{{route('finance::payment.paginate')}}",
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
                        {field: 'supplier_name', title: '供应商', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.supplier.name;
                        }},
                        {field: 'amount', title: '金额', width: 100, align: 'center', fixed: 'left'},
                        {field: 'remained_amount', title: '结余金额', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return parseFloat(d.remained_amount) ? d.remained_amount : '';
                        }},
                        {field: 'creator', title: '创建人', align: 'center', templet: function (d) {
                            return d.user ? d.user.name : '';
                        }},
                        {field: 'detail', title: '抵扣明细', width: 710, align: 'center', templet: function (d) {
                            var html = '';
                            d.deductions.forEach(function (item, key) {
                                if (0 == key) {
                                    html += '<ul class="erp-table-list-ul erp-table-list-ul-first">';
                                }else {
                                    html += '<ul class="erp-table-list-ul">';
                                }

                                var entry_amount = item.sku_entry.quantity * item.sku_entry.purchase_order_item.price;
                                html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 150px;">' + item.sku_entry.purchase_order_item.purchase_order.code + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.sku_entry.purchase_order_item.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.sku_entry.quantity + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 80px;">' + item.sku_entry.purchase_order_item.price + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + entry_amount.toFixed(2) + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + item.amount + '</li>';
                                html += '<li class="erp-table-list-li" style="width: 100px;">' + moment(item.sku_entry.created_at).format('YYYY-MM-DD') + '</li>';
                                html += '</ul>';
                            });
                            return html;
                        }},
                        {field: 'created_at', title: '创建时间', width: 160, align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center'},
                        {field: 'action', title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    // 修改SKU列表表头
                    if (0 == $('th[data-field=detail] ul').length) {
                        var html = '';
                        html += '<ul class="erp-table-list-ul">';
                        html += '<li class="erp-table-list-li erp-table-list-li-first" style="width: 150px; text-align: center;">订单编号</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">订单数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">入库数量</li>';
                        html += '<li class="erp-table-list-li" style="width: 80px; text-align: center;">价格</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">入库金额</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">抵扣金额</li>';
                        html += '<li class="erp-table-list-li" style="width: 100px; text-align: center;">入库时间</li>';
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
                        // 未完成抵扣的付款单才可以修改
                        if (0 == data.is_finished) {
                            actions.push({
                                title: "编辑",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('finance::collection.form')}}?collection_id=" + data.id, '编辑收款单[' + data.id + ']');
                                }
                            });
                        }

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