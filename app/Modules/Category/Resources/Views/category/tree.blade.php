@extends('layouts.default')
@section('content')
    <div class="layui-col-xs-12" style="margin-bottom: 15px;">
        <button type="button" class="layui-btn erp-collapse"  dtree-id="category" dtree-menu="moveDown">展开</button>
        {{--<button type="button" class="layui-btn"  dtree-id="category" dtree-menu="moveUp">收起</button>--}}
        @if(1 == $type && \Auth::user()->hasPermissionTo('add_product_category') || 2 == $type && \Auth::user()->hasPermissionTo('add_goods_category'))
            <button type="button" class="layui-btn layui-btn-normal"  dtree-id="category" dtree-menu="addRoot">添加一级分类</button>
        @endif
    </div>
    <ul id="category" class="dtree" data-id="0"></ul>
@endsection
@section('scripts')
    <script>
        var type = <?= $type ?>;
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
                        category.spread = false;
                        if (category.children) {
                            category.last = false;
                            category.children.forEach(function (son) {
                                son.level = 2;
                                son.spread = false;
                                if (son.children) {
                                    son.last = false;
                                    son.children.forEach(function (grandson) {
                                        grandson.level = 3;
                                        grandson.spread = false;
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
                ,iconfont:["layui-icon"]
                ,iconfontStyle:[{
                    fnode:{
                        node:{
                            close: "layui-icon-rate-solid",
                            open: "layui-icon-rate"
                        }
                    }
                }]
                ,done: function(data, obj){

                }
                ,menubar:true
                ,menubarTips:{
                    freedom:[
                        {
                            menubarId:"addRoot",
                            handler:function(){
                                layer.prompt({
                                    title: '添加一级分类'
                                }, function(value, index, elem){
                                    layer.close(index);
                                    var load_index = layer.load();
                                    $.ajax({
                                        method: "post",
                                        url: "{{route('category::category.save')}}",
                                        data: {type: "{{$type}}", name: value},
                                        success: function (data) {
                                            layer.close(load_index);
                                            if ('success' == data.status) {
                                                layer.msg("分类添加成功", {icon:1});
                                                category_tree.refreshTree();
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
                        }
                    ]
                    ,group:[]
                }
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
                                            category_tree.refreshTree();
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
                                            category_tree.refreshTree();
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
                                            category_tree.refreshTree();
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
                ,toolbarFun:{
                    loadToolbarBefore: function(buttons, param, $div){
                        if(3 == param.level){
                            buttons.myAdd = "";
                        }

                        if (1 == type) {
                            var has_add = <?= json_encode(\Auth::user()->hasPermissionTo('add_product_category')); ?>;
                            var has_edit = <?= json_encode(\Auth::user()->hasPermissionTo('edit_product_category')); ?>;
                            var has_delete = <?= json_encode(\Auth::user()->hasPermissionTo('delete_product_category')); ?>;
                        }else if (2 == type) {
                            var has_add = <?= json_encode(\Auth::user()->hasPermissionTo('add_goods_category')); ?>;
                            var has_edit = <?= json_encode(\Auth::user()->hasPermissionTo('edit_goods_category')); ?>;
                            var has_delete = <?= json_encode(\Auth::user()->hasPermissionTo('delete_goods_category')); ?>;
                        }
                        if (!has_add) {
                            buttons.myAdd = "";
                        }
                        if (!has_edit) {
                            buttons.myEdit = "";
                        }
                        if (!has_delete) {
                            buttons.myDel = "";
                        }

                        return buttons;
                    }
                }
            });

            $('button.erp-collapse').on('click', function () {
                var $this = $(this)
                        ,dtreeMenu = $this.attr('dtree-menu')
                        ,dtreeMethods = category_tree.menubarMethod();

                if ('moveDown' == dtreeMenu) {
                    dtreeMethods.openAllNode();
                    $this.attr('dtree-menu', 'moveUp');
                    $this.html('收起');
                }else {
                    dtreeMethods.closeAllNode();
                    $this.attr('dtree-menu', 'moveDown');
                    $this.html('展开');
                }
            })
        });
    </script>
@endsection