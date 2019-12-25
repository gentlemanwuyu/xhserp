@extends('layouts.default')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="account">
        <input type="hidden" name="account_id" value="{{$account_id or ''}}">
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>账户信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">账户名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入账户名称" class="layui-input" value="{{$account->name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">银行</label>
                            <div class="layui-input-block">
                                <input type="text" name="bank" lay-verify="required" lay-reqText="请输入银行" class="layui-input" value="{{$account->bank or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">分支行</label>
                            <div class="layui-input-block">
                                <input type="text" name="branch" class="layui-input" value="{{$account->branch or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">收款人</label>
                            <div class="layui-input-block">
                                <input type="text" name="payee" lay-verify="required" lay-reqText="请输入收款人" class="layui-input" value="{{$account->payee or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">账号</label>
                            <div class="layui-input-block">
                                <input type="text" name="account" lay-verify="required" lay-reqText="请输入账号" class="layui-input" value="{{$account->account or ''}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="account">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form', 'table'], function () {
            var form = layui.form;

            // 提交收款单
            form.on('submit(account)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('finance::account.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("账号添加成功", {icon: 1, time: 2000});
                            location.reload();
                        } else {
                            layer.msg("账号添加失败:"+data.msg, {icon: 2, time: 2000});
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