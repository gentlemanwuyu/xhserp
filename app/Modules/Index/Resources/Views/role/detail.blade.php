@extends('layouts.default')
@section('css')
    <style>
        #permission_list .layui-table th, #permission_list .layui-table td{text-align: left;}
        .has-permission{background-color: #009688; color: #fff;}
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
    <?php $role_permissions = array_column($role->permissions->toArray(), 'name'); ?>
    <div class="erp-detail" id="permission_list">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>权限列表</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <table class="layui-table">
                <thead>
                <tr>
                    <th width="15%">一级菜单</th>
                    <th width="15%">二级菜单</th>
                    <th>按钮</th>
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $p1)
                    <?php
                        $p1_rowspan = empty($p1['children']) ? 1 : count($p1['children']);
                    ?>
                    <tr>
                        <td rowspan="{{$p1_rowspan}}" @if(in_array($p1['name'], $role_permissions)) class="has-permission" @endif>{{$p1['display_name']}}</td>
                        @if(empty($p1['children']))
                            <?php $p2s = []; ?>
                            <td></td>
                            <td></td>
                        @else
                            <?php
                                $p2s = $p1['children'];
                                $first_p2 = array_shift($p2s);
                            ?>
                            <td @if(in_array($p1['name'], $role_permissions)) class="has-permission" @endif>{{$first_p2['display_name']}}</td>
                            <td>
                                @if(!empty($first_p2['children']))
                                    @foreach($first_p2['children'] as $p3)
                                        @if(in_array($p3['name'], $role_permissions))
                                            <button type="button" class="layui-btn layui-btn-sm">
                                                <i class="layui-icon layui-icon-ok"></i>{{$p3['display_name']}}
                                            </button>
                                        @else
                                            <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">{{$p3['display_name']}}</button>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        @endif
                    </tr>
                    @foreach($p2s as $p2)
                        <tr>
                            <td @if(in_array($p1['name'], $role_permissions)) class="has-permission" @endif>{{$p2['display_name']}}</td>
                            <td>
                                @if(!empty($p2['children']))
                                    @foreach($p2['children'] as $p3)
                                        @if(in_array($p3['name'], $role_permissions))
                                            <button type="button" class="layui-btn layui-btn-sm">
                                                <i class="layui-icon layui-icon-ok"></i>{{$p3['display_name']}}
                                            </button>
                                        @else
                                            <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">{{$p3['display_name']}}</button>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection