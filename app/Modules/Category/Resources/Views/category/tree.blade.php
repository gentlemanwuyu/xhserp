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
                ,toolbar:true
                ,toolbarShow:[]
                ,toolbarExt:[
                    {
                        toolbarId: "testAdd",
                        icon: "layui-icon layui-icon-add-1",
                        title: "添加子分类",
                        handler: function (node, $div) {
                            layer.prompt({
                                title: '添加子分类'
                            }, function(value, index, elem){

                            });
                        }
                    },
                    {
                        toolbarId: "testEdit",
                        icon: "layui-icon layui-icon-edit",
                        title: "编辑",
                        handler: function (node, $div) {
                            console.log(node.context);
                            layer.prompt({
                                title: '编辑分类',
                                value: node.context
                            }, function(value, index, elem){

                            });
                        }
                    },
                    {
                        toolbarId: "testDel",
                        icon:"layui-icon layui-icon-delete",
                        title:"删除",
                        handler: function(node,$div) {
                            layer.confirm("确认要删除该分类？", {icon: 3, title:"确认"}, function (index) {
                                layer.close(index);
                            });
                        }
                    }
                ]
            });
        });
    </script>
@endsection