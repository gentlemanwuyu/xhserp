@extends('layouts.default')
@section('content')
    <div class="layui-fluid">
        <div class="layadmin-tips">
            <i class="layui-icon" face>&#xe664;</i>
            <div class="layui-text">
                <h2>对不起，您没有权限访问！请联系管理员</h2>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.setTimeout(function () {
            parent.layui.admin.closeThisTabs();
        }, 3000);
    </script>
@endsection