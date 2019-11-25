@extends('layouts.default')
@section('content')
    <div class="layui-col-xs6">
        <form class="layui-form" lay-filter="user">
            <div class="layui-form-item">
                <label class="layui-form-label">邮箱</label>
                <div class="layui-input-block">
                    <input type="text" name="email" lay-verify="required|email" lay-reqText="请输入邮箱" placeholder="" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-block">
                    <input type="text" name="name" lay-verify="required|alpha_dash" lay-reqText="请输入用户名" placeholder="" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">生日</label>
                <div class="layui-input-block">
                    <input type="text" name="birthday"  lay-verify="date" placeholder="" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input type="radio" name="gender_id" value="1" title="男">
                    <input type="radio" name="gender_id" value="2" title="女">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">电话</label>
                <div class="layui-input-block">
                    <input type="text" name="telephone"  lay-verify="phone" placeholder="" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">是否管理员</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_admin" lay-skin="switch" lay-text="是|否">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="button" class="layui-btn" lay-submit lay-filter="user">提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        layui.use(['form', 'laydate'], function () {
            var form = layui.form
                    ,laydate = layui.laydate;
            laydate.render({
                elem: 'input[name=birthday]'
            });
        });
    </script>
@endsection