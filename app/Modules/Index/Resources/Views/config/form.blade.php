@extends('layouts.default')
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="config">
        @if(isset($config_id))
            <input type="hidden" name="config_id" value="{{$config_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>系统配置</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">键</label>
                            <div class="layui-input-block">
                                <input type="text" name="key" lay-verify="required" lay-reqText="请输入键" class="layui-input" value="{{$config->key or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label required">值</label>
                            <div class="layui-input-block">
                                <textarea name="value" class="layui-textarea" lay-verify="required" lay-reqText="请输入值">{{$config->value or ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="config">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(config)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('index::config.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("配置保存成功", {icon: 1, time: 2000}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg("配置保存失败:"+data.msg, {icon: 2, time: 2000});
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