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
                                <input type="text" name="birthday"  lay-verify="date" class="layui-input" value="{{$user->birthday or ''}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="layui-form-item" pane="">
                            <label class="layui-form-label">性别</label>
                            <div class="layui-input-block">
                                @foreach(\App\Modules\Index\Models\User::$genders as $gender_id => $gender_name)
                                    <input type="radio" name="gender_id" value="{{$gender_id}}" title="{{$gender_name}}" @if(!empty($user) && $gender_id == $user->gender_id) checked @endif>
                                @endforeach
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">电话</label>
                            <div class="layui-input-block">
                                <input type="text" name="telephone"  lay-verify="phone" class="layui-input" value="{{$user->telephone or ''}}">
                            </div>
                        </div>
                        @if(YES == \Auth::user()->is_admin)
                            <div class="layui-form-item" pane="">
                                <label class="layui-form-label">是否管理员</label>
                                <div class="layui-input-block">
                                    <input type="checkbox" name="is_admin" value="1" lay-skin="switch" lay-text="是|否"  @if(!empty($user) && 1 == $user->is_admin) checked @endif>
                                </div>
                            </div>
                        @endif
                        <div class="layui-form-item">
                            <label class="layui-form-label">角色</label>
                            <div class="layui-input-block">
                                <div id="roles_select"></div>
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
        var roles = <?= json_encode($roles); ?>
                ,initRoles = <?= isset($user) ? json_encode(array_column($user->roles->toArray(), 'name')) : '[]'; ?>;
        layui.use(['form', 'laydate'], function () {
            var form = layui.form
                    ,laydate = layui.laydate;
            laydate.render({
                elem: 'input[name=birthday]'
            });

            xmSelect.render({
                el: '#roles_select',
                name: 'roles',
                initValue: initRoles,
                tips: '请选择角色',
                model: {icon: 'show'},
                filterable: true,
                searchTips: '搜索...',
                theme:{
                    color: '#5FB878'
                },
                prop: {
                    name: 'display_name',
                    value: 'name'
                },
                height: 'auto',
                data: roles
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
                                parent.layui.admin.closeThisTabs();
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