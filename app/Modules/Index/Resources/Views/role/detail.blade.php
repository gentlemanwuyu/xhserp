@extends('layouts.default')
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
                            <td>角色名</td>
                            <td>{{$role->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>显示名称</td>
                            <td>{{$role->display_name}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection