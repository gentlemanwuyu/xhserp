@extends('layouts.default')
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="user">
        @if(isset($user_id))
            <input type="hidden" name="user_id" value="{{$user_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">邮箱</label>
                            <div class="layui-input-block">
                                @if(!empty($user))
                                    <span class="erp-form-span">{{$user->email}}</span>
                                @else
                                    <input type="text" name="email" lay-verify="required|email" lay-reqText="请输入邮箱" class="layui-input">
                                @endif
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">用户名</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入用户名" class="layui-input" value="{{$user->name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">生日</label>
                            <div class="layui-input-block">
                                <input type="text" name="birthday"  lay-verify="date" class="layui-input" value="{{$user->birthday or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item" pane="">
                            <label class="layui-form-label">性别</label>
                            <div class="layui-input-block">
                                <input type="radio" name="gender_id" value="1" title="男" @if(!empty($user) && 1 == $user->gender_id) checked @endif>
                                <input type="radio" name="gender_id" value="2" title="女" @if(!empty($user) && 2 == $user->gender_id) checked @endif>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">电话</label>
                            <div class="layui-input-block">
                                <input type="text" name="telephone"  lay-verify="phone" class="layui-input" value="{{$user->telephone or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item" pane="">
                            <label class="layui-form-label">是否管理员</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="is_admin" value="1" lay-skin="switch" lay-text="是|否"  @if(!empty($user) && 1 == $user->is_admin) checked @endif>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="user">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form', 'laydate'], function () {
            var form = layui.form
                    ,laydate = layui.laydate;
            laydate.render({
                elem: 'input[name=birthday]'
            });

            form.on('submit(user)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('index::user.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("用户保存成功", {icon: 1, time: 2000}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg("用户保存失败:"+data.msg, {icon: 2, time: 2000});
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