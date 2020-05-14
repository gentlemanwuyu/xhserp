@extends('layouts.default')
@section('content')
    <?php
        $customer = $application->customer;
        $user = $application->user;
    ?>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>基本信息</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>客户</td>
                            <td>
                                @if(\Auth::user()->hasPermissionTo('customer_detail'))
                                    <a lay-href="{{route('sale::customer.detail', ['customer_id' => $customer->id])}}" lay-text="客户详情[{{$customer->id}}]">{{$customer->name}}</a>
                                @else
                                    {{$customer->name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>当前付款方式</td>
                            <td>{{$customer->payment_method_name or ''}}</td>
                        </tr>
                        <tr>
                            <td>申请付款方式</td>
                            <td>{{$application->payment_method_name or ''}}</td>
                        </tr>
                        @if(\PaymentMethod::CREDIT == $application->payment_method)
                            <tr>
                                <td>额度</td>
                                <td>{{$application->credit or ''}}</td>
                            </tr>
                        @endif
                        @if(\PaymentMethod::MONTHLY == $application->payment_method)
                            <tr>
                                <td>月结天数</td>
                                <td>{{$application->monthly_day or ''}}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>申请原因</td>
                            <td>{{$application->reason or ''}}</td>
                        </tr>
                        <tr>
                            <td>申请人</td>
                            <td>
                                @if(\Auth::user()->hasPermissionTo('user_detail'))
                                    <a lay-href="{{route('index::user.detail', ['user_id' => $user->id])}}" lay-text="用户详情[{{$user->id}}]">{{$user->name}}</a>
                                @else
                                    {{$user->name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>状态</td>
                            <td>{{$application->status_name}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if(!$application->logs->isEmpty())
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>审核日志</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs12">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>日志信息</th>
                            <th>日志内容</th>
                            <th>操作人</th>
                            <th>时间</th>
                        </tr>
                        <tbody>
                        <?php $index = 1; ?>
                        @foreach($application->logs as $log)
                            <tr>
                                <td>{{$index++}}</td>
                                <td>{{$log->message}}</td>
                                <td>{{$log->content}}</td>
                                <td>{{$log->user->name or ''}}</td>
                                <td>{{$log->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(isset($application) && \App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW == $application->status && isset($action) && 'review' == $action)
    <div class="layui-row">
        <form class="layui-form">
            <button type="button" class="layui-btn layui-btn-normal" erp-action="agree">同意</button>
            <button type="button" class="layui-btn layui-btn-danger" erp-action="reject">驳回</button>
        </form>
    </div>
    @endif
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            $('button[erp-action=agree]').on('click', function () {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::paymentMethod.agree')}}",
                    data: {application_id: "{{$application_id or ''}}"},
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("付款方式申请已通过", {icon: 1, time: 2000}, function(){
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("付款方式申请审核失败:" + data.msg, {icon: 2, time: 2000});
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

            $('button[erp-action=reject]').on('click', function () {
                layer.prompt({
                    title: '驳回'
                }, function(value, index, elem){
                    layer.close(index);

                    var load_index = layer.load();
                    $.ajax({
                        method: "post",
                        url: "{{route('sale::paymentMethod.reject')}}",
                        data: {application_id: "{{$application_id or ''}}", reject_reason: value},
                        success: function (data) {
                            layer.close(load_index);
                            if ('success' == data.status) {
                                layer.msg("付款方式已驳回", {icon: 1, time: 2000}, function(){
                                    parent.layui.admin.closeThisTabs();
                                });
                            } else {
                                layer.msg("付款方式审核失败:" + data.msg, {icon: 2, time: 2000});
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
            });
        });
    </script>
@endsection