@extends('layouts.default')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <ul id="category" class="dtree" data-id="0"></ul>
@endsection
@section('scripts')
    <script>
        layui.extend({
            dtree: '/assets/dtree/dtree'
        }).use(['dtree'], function(){
            var dtree = layui.dtree;

            // 初始化树
            var category_tree = dtree.render({
                elem: "#category"
                ,method: 'GET'
                ,url: "{{route('category::category.data', ['type' => $type])}}"
                ,response: {title: 'name', parentId: 'parent_id'}
                ,success: function(data, obj, first){ // 渲染前回调
                    data.data.forEach(function (category) {
                        category.level = 1;
                        category.spread = true;
                        if (category.children) {
                            category.last = false;
                            category.children.forEach(function (son) {
                                son.level = 2;
                                son.spread = true;
                                if (son.children) {
                                    son.last = false;
                                    son.children.forEach(function (grandson) {
                                        grandson.level = 3;
                                        grandson.spread = true;
                                        grandson.last = true;
                                    });
                                }else {
                                    son.last = true;
                                }
                            });
                        }else {
                            category.last = true;
                        }
                    });
                }
                ,line: true
                ,skin: "category"
                ,ficon: ["1","-1"]
                ,icon: "-1"
                ,toolbar:true
                ,toolbarShow:[]
                ,toolbarExt:[
                    {
                        toolbarId: "myAdd",
                        icon: "layui-icon layui-icon-add-1",
                        title: "添加子分类",
                        handler: function (node, $div) {
                            layer.prompt({
                                title: '添加子分类'
                            }, function(value, index, elem){
                                layer.close(index);
                                var load_index = layer.load();
                                $.ajax({
                                    method: "post",
                                    url: "{{route('category::category.save')}}",
                                    data: {type: "{{$type}}", parent_id: node.nodeId, name: value},
                                    success: function (data) {
                                        layer.close(load_index);
                                        if ('success' == data.status) {
                                            layer.msg("分类添加成功", {icon:1});
                                            location.reload();
                                        } else {
                                            layer.msg("分类添加失败:"+data.msg, {icon:2});
                                            return false;
                                        }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                        layer.close(load_index);
                                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                                        return false;
                                    }
                                });
                            });
                        }
                    },
                    {
                        toolbarId: "myEdit",
                        icon: "layui-icon layui-icon-edit",
                        title: "编辑",
                        handler: function (node, $div) {
                            console.log(node.context);
                            layer.prompt({
                                title: '编辑分类',
                                value: node.context
                            }, function(value, index, elem){
                                if (value == node.context) {
                                    return false;
                                }

                                layer.close(index);
                                var load_index = layer.load();
                                $.ajax({
                                    method: "post",
                                    url: "{{route('category::category.save')}}",
                                    data: {category_id: node.nodeId, name: value},
                                    success: function (data) {
                                        layer.close(load_index);
                                        if ('success' == data.status) {
                                            layer.msg("分类编辑成功", {icon:1});
                                            location.reload();
                                        } else {
                                            layer.msg("分类编辑失败:"+data.msg, {icon:2});
                                            return false;
                                        }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                        layer.close(load_index);
                                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                                        return false;
                                    }
                                });
                            });
                        }
                    },
                    {
                        toolbarId: "myDel",
                        icon:"layui-icon layui-icon-delete",
                        title:"删除",
                        handler: function(node,$div) {
                            var $child = $div.parent('li.dtree-nav-item').find('ul.dtree-nav-ul-sid>li');
                            if (0 != $child.length) {
                                layer.msg("该分类下还有子分类，不能删除", {icon:2});
                                return false;
                            }
                            layer.confirm("确认要删除该分类？", {icon: 3, title:"确认"}, function (index) {
                                layer.close(index);
                                var load_index = layer.load();
                                $.ajax({
                                    method: "post",
                                    url: "{{route('category::category.delete')}}",
                                    data: {category_id: node.nodeId},
                                    success: function (data) {
                                        layer.close(load_index);
                                        if ('success' == data.status) {
                                            layer.msg("分类删除成功", {icon:1});
                                            location.reload();
                                        } else {
                                            layer.msg("分类删除失败:"+data.msg, {icon:2});
                                            return false;
                                        }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                        layer.close(load_index);
                                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                                        return false;
                                    }
                                });
                            });
                        }
                    }
                ]
            });
        });
    </script>
@endsection