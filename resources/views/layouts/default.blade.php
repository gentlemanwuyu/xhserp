@extends('layouts.template')
@section('body')
    <div class="layui-fluid">
        @yield('content')
    </div>
    @include('layouts.constants')
@endsection