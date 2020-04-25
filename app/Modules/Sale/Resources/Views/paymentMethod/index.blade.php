@extends('layouts.default')
@section('content')
    <form class="layui-form" lay-filter="search">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2">
                <select name="customer_id" lay-search="">
                    <option value="">客户</option>
                    @foreach($customers as $customer)
                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <select name="status">
                    <option value="">状态</option>
                    @foreach(\App\Modules\Sale\Models\PaymentMethodApplication::$statuses as $status_id => $status_name)
                        <option value="{{$status_id}}">{{$status_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-col-xs2">
                <select name="user_id" lay-search="">
                    <option value="">申请人</option>
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
                url: "{{route('sale::paymentMethod.paginate')}}",
                where: {status: "{{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}"},
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
                        {field: 'customer_name', title: '客户', width: 100, align: 'center', fixed: 'left', templet: function (d) {
                            return d.customer.name;
                        }},
                        {field: 'status_name', title: '状态', width: 100, align: 'center', fixed: 'left'},
                        {field: 'payment_method_name', title: '付款方式', width: 100, align: 'center', fixed: 'left'},
                        {field: 'credit', title: '额度', width: 100, align: 'center', templet: function (d) {
                            return "{{\PaymentMethod::CREDIT}}" == d.payment_method ? d.credit : '';
                        }},
                        {field: 'monthly_day', title: '月结天数', width: 100, align: 'center', templet: function (d) {
                            return "{{\PaymentMethod::MONTHLY}}" == d.payment_method ? d.monthly_day : '';
                        }},
                        {field: 'user_name', title: '申请人', width: 100, align: 'center', templet: function (d) {
                            return d.user ? d.user.name : '';
                        }},
                        {field: 'reason', title: '申请原因', align: 'center'},
                        {field: 'created_at', title: '创建时间', width: 160, align: 'center'},
                        {field: 'updated_at', title: '最后更新时间', width: 160, align: 'center'},
                        {field: 'action', title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: "#action"}
                    ]
                ]
                ,done: function(res, curr, count){
                    dropdown(res.data,function(data) {
                        var actions = [];

                        @can('payment_method_application_detail')
                            actions.push({
                            title: "详情",
                            event: function () {
                                parent.layui.index.openTabsPage("{{route('sale::paymentMethod.detail')}}?application_id=" + data.id, '付款方式申请详情[' + data.id + ']');
                            }
                        });
                        @endcan

                        if (-1 < ["{{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}", "{{\App\Modules\Sale\Models\PaymentMethodApplication::REJECTED}}"].indexOf(data.status)) {
                            @can('edit_payment_method_application')
                            actions.push({
                                title: "编辑",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('sale::paymentMethod.form')}}?application_id=" + data.id, '编辑付款方式申请[' + data.id + ']');
                                }
                            });
                            @endcan
                        }

                        if ("{{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}" == data.status) {
                            @can('review_payment_method_application')
                            actions.push({
                                title: "审核",
                                event: function () {
                                    parent.layui.index.openTabsPage("{{route('sale::paymentMethod.review')}}?action=review&application_id=" + data.id, '审核付款方式申请[' + data.id + ']');
                                }
                            });
                            @endcan
                        }

                        if (-1 < ["{{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}", "{{\App\Modules\Sale\Models\PaymentMethodApplication::REJECTED}}"].indexOf(data.status)) {
                            @can('delete_payment_method_application')
                            actions.push({
                                title: "删除",
                                event: function() {
                                    layer.confirm("确认要删除该付款方式申请？", {icon: 3, title: "确认"}, function (index) {
                                        layer.close(index);
                                        var load_index = layer.load();
                                        $.ajax({
                                            method: "post",
                                            url: "{{route('sale::paymentMethod.delete')}}",
                                            data: {application_id: data.id},
                                            success: function (data) {
                                                layer.close(load_index);
                                                if ('success' == data.status) {
                                                    layer.msg("付款方式申请删除成功", {icon: 1, time: 2000}, function () {
                                                        table.render(tableOpts);
                                                    });
                                                } else {
                                                    layer.msg("付款方式申请删除失败:"+data.msg, {icon: 2, time: 2000});
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
            };

            form.val("search", {
                "status": "{{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}"
            });

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