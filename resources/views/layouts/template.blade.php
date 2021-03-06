<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('/assets/layui-src/dist/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('/assets/layuiadmin/style/admin.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('/assets/dtree/dtree.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('/assets/dtree/font/dtreefont.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('/assets/layui-table-dropdown/dropdown.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('/assets/css/erp.css')}}" media="all">
    @yield('css')
</head>
<body>
@yield('body')
<script src="{{asset('/assets/layui-src/dist/layui.all.js')}}"></script>
<script src="{{asset('/assets/echarts/dist/echarts.min.js')}}"></script>
<script src="{{asset('/assets/moment/moment.js')}}"></script>
<script src="{{asset('/assets/xm-select/dist/xm-select.js')}}"></script>
<script src="{{asset('/assets/js/erp.js')}}"></script>
@yield('scripts')
</body>
</html>