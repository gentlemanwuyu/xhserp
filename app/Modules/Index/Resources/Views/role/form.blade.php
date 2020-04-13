@extends('layouts.default')
@section('css')
    <style>
        .layui-card[erp-level_1]{box-shadow: none;border: 1px solid #F2F2F2;}
        .layui-card[erp-level_1]>.layui-card-header{background-color: #F2F2F2;}
        .layui-card[erp-level_2]{margin-bottom: 0;box-shadow: none;}
        .layui-card[erp-level_2]>.layui-card-header{border-bottom: 0;}
        .layui-card[erp-level_2]>.layui-card-body{padding-left: 35px;}
    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="role">
        @if(isset($role_id))
            <input type="hidden" name="role_id" value="{{$role_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs6">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">角色名</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入角色名" class="layui-input" value="{{$role->name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">显示名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="display_name" lay-verify="required" lay-reqText="请输入显示名称" class="layui-input" value="{{$role->display_name or ''}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($tree))
            <div class="layui-card" id="assign_permission">
                <div class="layui-card-header">
                    <h3 style="display: inline;">分配权限</h3>
                    <a href="javascript:;" erp-event="assign_all" data-value="1">[全选]</a>
                </div>
                <div class="layui-card-body">
                    @foreach($tree as $p1)
                        <div class="layui-card" erp-level_1>
                            <div class="layui-card-header">
                                <h3><input type="checkbox" name="permissions[]" value="{{$p1['name']}}" title="{{$p1['display_name']}}" lay-filter="permission" lay-skin="primary" erp-level="1" @if(in_array($p1['name'], $permissions)) checked @endif></h3>
                            </div>
                            @if(!empty($p1['children']))
                                <div class="layui-card-body">
                                    @foreach($p1['children'] as $p2)
                                        <div class="layui-card" erp-level_2>
                                            <div class="layui-card-header">
                                                <h3><input type="checkbox" name="permissions[]" value="{{$p2['name']}}" title="{{$p2['display_name']}}" lay-filter="permission" lay-skin="primary" erp-level="2" @if(in_array($p2['name'], $permissions)) checked @endif></h3>
                                            </div>
                                            @if(!empty($p2['children']))
                                                <div class="layui-card-body">
                                                    @foreach($p2['children'] as $p3)
                                                        <input type="checkbox" name="permissions[]" value="{{$p3['name']}}" title="{{$p3['display_name']}}" lay-filter="permission" lay-skin="primary" erp-level="3" @if(in_array($p3['name'], $permissions)) checked @endif>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <button type="button" class="layui-btn" lay-submit lay-filter="role">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('checkbox(permission)', function(data){
                var $checkbox = $(data.elem)
                        ,level = $checkbox.attr('erp-level')
                        ,checked = data.elem.checked;

                if (1 == level) {
                    var $card = $checkbox.parents('.layui-card[erp-level_1]');
                    $card.find('.layui-card-body input[lay-filter=permission]').prop('checked', checked);
                }else if (2 == level) {
                    var $card = $checkbox.parents('.layui-card[erp-level_2]');
                    $card.find('.layui-card-body input[lay-filter=permission]').prop('checked', checked);
                }

                form.render('checkbox');
            });

            $('*[erp-event=assign_all]').on('click', function () {
                var value = $(this).attr('data-value')
                        ,$card = $(this).parents('.layui-card');
                if (1 == value) {
                    $card.find('input[lay-filter=permission]').prop('checked', true);
                    $(this).html('[全不选]');
                    $(this).attr('data-value', 0);
                }else {
                    $card.find('input[lay-filter=permission]').prop('checked', false);
                    $(this).html('[全选]');
                    $(this).attr('data-value', 1);
                }
                form.render('checkbox');
            });

            form.on('submit(role)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('index::role.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("角色保存成功", {icon: 1, time: 2000}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg("角色保存失败:"+data.msg, {icon: 2, time: 2000});
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