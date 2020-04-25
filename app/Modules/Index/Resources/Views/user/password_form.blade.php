@extends('layouts.default')
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="password">
        @if(isset($user_id))
            <input type="hidden" name="user_id" value="{{$user_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>修改密码</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">新密码</label>
                            <div class="layui-input-block">
                                <input type="password" name="password" lay-verify="required" lay-reqText="请输入新密码" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">确认密码</label>
                            <div class="layui-input-block">
                                <input type="password" name="confirm_password" lay-verify="required" lay-reqText="请输入确认密码" class="layui-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="password">确认修改</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(password)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('index::user.reset_password')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("密码修改成功", {icon: 1, time: 2000}, function () {
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("密码修改失败:"+data.msg, {icon: 2, time: 2000});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(load_index);
                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon: 2, time: 2000});
                        return false;
                    }
                });

                return false;
            });
        });
    </script>
@endsection