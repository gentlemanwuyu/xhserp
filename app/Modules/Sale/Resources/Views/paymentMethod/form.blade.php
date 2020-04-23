@extends('layouts.default')
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="payment_method_application">
        <input type="hidden" name="application_id" value="{{$application_id}}">
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item" pane="">
                            <label class="layui-form-label required">付款方式</label>
                            <div class="layui-input-block">
                                @foreach(\App\Modules\Sale\Models\Customer::$payment_methods as $method_id => $method)
                                    @if(1 != $method_id)
                                        <input type="radio" name="payment_method" value="{{$method_id}}" title="{{$method}}" lay-verify="checkReq" lay-reqText="请输入付款方式" lay-filter="payment_method" @if(isset($application) && $application->payment_method == $method_id) checked @endif>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @if(2 == $application->payment_method)
                            <div class="layui-form-item">
                                <label class="layui-form-label required">额度</label>
                                <div class="layui-input-block">
                                    <input type="text" name="credit" class="layui-input" placeholder="额度(元)" lay-verify="required" lay-reqText="请输入额度" value="{{$application->credit}}" oninput="value=value.replace(/[^\d]/g, '')">
                                </div>
                            </div>
                        @endif
                        @if(3 == $application->payment_method)
                            <div class="layui-form-item">
                                <label class="layui-form-label required">月结天数</label>
                                <div class="layui-input-block">
                                    <input type="text" name="monthly_day" class="layui-input" placeholder="月结天数" lay-verify="required" lay-reqText="请输入月结天数" value="{{$application->monthly_day}}" oninput="value=value.replace(/[^\d]/g, '')">
                                </div>
                            </div>
                        @endif
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label required">申请原因</label>
                            <div class="layui-input-block">
                                <textarea name="reason" class="layui-textarea" placeholder="申请原因" lay-verify="required" lay-reqText="请输入申请原因">{{$application->reason}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="payment_method_application">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            // 付款方式单选监听
            form.on('radio(payment_method)', function(data){console.log(111);
                var $paymentMethodItem = $(data.elem).parents('.layui-form-item');
                $('input[name=credit]').parents('.layui-form-item').remove();
                $('input[name=monthly_day]').parents('.layui-form-item').remove();
                $('textarea[name=reason]').parents('.layui-form-item').remove();

                if (2 == data.value) {
                    var html = '';
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label required">额度</label>';
                    html += '<div class="layui-input-block">';
                    html += '<input type="text" name="credit" class="layui-input" placeholder="额度(元)" lay-verify="required" lay-reqText="请输入额度" oninput="value=value.replace(/[^\\d]/g, \'\')">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="layui-form-item layui-form-text">';
                    html += '<label class="layui-form-label required">申请原因</label>';
                    html += '<div class="layui-input-block">';
                    html += '<textarea name="reason" class="layui-textarea" placeholder="申请原因" lay-verify="required" lay-reqText="请输入申请原因"></textarea>';
                    html += '</div>';
                    html += '</div>';

                    $paymentMethodItem.after(html);
                }else if (3 == data.value) {
                    var html = '';
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label required">月结天数</label>';
                    html += '<div class="layui-input-block">';
                    html += '<input type="text" name="monthly_day" class="layui-input" placeholder="月结天数" lay-verify="required" lay-reqText="请输入月结天数" oninput="value=value.replace(/[^\\d]/g, \'\')">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="layui-form-item layui-form-text">';
                    html += '<label class="layui-form-label required">申请原因</label>';
                    html += '<div class="layui-input-block">';
                    html += '<textarea name="reason" class="layui-textarea" placeholder="申请原因" lay-verify="required" lay-reqText="请输入申请原因"></textarea>';
                    html += '</div>';
                    html += '</div>';

                    $paymentMethodItem.after(html);
                }
            });

            // 提交付款方式申请
            form.on('submit(payment_method_application)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::paymentMethod.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("付款方式申请成功", {icon: 1, time: 2000}, function () {
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("付款方式申请失败:"+data.msg, {icon: 2, time: 2000});
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
    </script>
@endsection