@extends('layouts.default')
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="permission">
        @if(isset($permission_id))
            <input type="hidden" name="permission_id" value="{{$permission_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">类型</label>
                            <div class="layui-input-block">
                                <select name="type" lay-verify="required" lay-reqText="请选择类型">
                                    <option value="">请选择类型</option>
                                    @foreach(\App\Modules\Index\Models\Permission::$types as $type_id => $type_name)
                                        <option value="{{$type_id}}" @if(isset($permission->type) && $type_id == $permission->type) selected @endif>{{$type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">权限名</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入权限名" class="layui-input" value="{{$permission->name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">显示名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="display_name" lay-verify="required" lay-reqText="请输入显示名称" class="layui-input" value="{{$permission->display_name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">路由</label>
                            <div class="layui-input-block">
                                <input type="text" name="route" lay-verify="required" lay-reqText="请输入路由" class="layui-input" value="{{$permission->route or ''}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="permission">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(permission)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('index::permission.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("权限保存成功", {icon: 1, time: 2000}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg("权限保存失败:"+data.msg, {icon: 2, time: 2000});
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