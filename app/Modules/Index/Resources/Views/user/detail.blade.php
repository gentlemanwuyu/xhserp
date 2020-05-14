@extends('layouts.default')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>基本信息</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>邮箱</td>
                            <td>{{$user->email or ''}}</td>
                        </tr>
                        <tr>
                            <td>用户名</td>
                            <td>{{$user->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>生日</td>
                            <td>{{$user->birthday or ''}}</td>
                        </tr>
                        <tr>
                            <td>性别</td>
                            <td>{{$user->gender or ''}}</td>
                        </tr>
                        <tr>
                            <td>电话</td>
                            <td>{{$user->telephone or ''}}</td>
                        </tr>
                        <tr>
                            <td>状态</td>
                            <td>{{$user->status_name or ''}}</td>
                        </tr>
                    </table>
                </div>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>角色</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="layui-badge layui-bg-green">{{$role->display_name}}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection