<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$sys_configs['APP_NAME'] or 'ERP系统'}}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{asset('/assets/layui-src/dist/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('/assets/layuiadmin/style/admin.css')}}" media="all">
</head>
<body class="layui-layout-body">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="fullscreen">
                        <i class="layui-icon layui-icon-screen-full"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right" style="padding: 0 10px;">
                {{--<li class="layui-nav-item">--}}
                    {{--<a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">--}}
                        {{--<i class="layui-icon layui-icon-notice"></i>--}}
                        {{--<!-- 如果有新消息，则显示小圆点 -->--}}
                        {{--<span class="layui-badge-dot"></span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="theme">
                        <i class="layui-icon layui-icon-theme"></i>
                    </a>
                </li>
                {{--<li class="layui-nav-item layui-hide-xs" lay-unselect>--}}
                    {{--<a href="javascript:;" layadmin-event="note">--}}
                        {{--<i class="layui-icon layui-icon-note"></i>--}}
                    {{--</a>--}}
                {{--</li>--}}
                <li class="layui-nav-item">
                    <a href="javascript:;">
                        <cite>{{\Auth::user()->name}}</cite>
                    </a>
                    <dl class="layui-nav-child">
                        {{--<dd><a lay-href="set/user/info.html">基本资料</a></dd>--}}
                        <dd><a lay-href="{{route('index::user.password_form')}}">修改密码</a></dd>
                        <hr>
                        <dd style="text-align: center;"><a href="{{route('index::index.logout')}}">退出</a></dd>
                    </dl>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="{{route('index::index.home')}}">
                    <span>{{$sys_configs['COMPANY_NAME'] or ''}}</span>
                </div>

                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-list"></i>
                            <cite>分类管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('category::category.tree', ['type' => 1])}}"><i class="layui-icon layui-icon-tree"></i>产品分类</a>
                            </dd>
                        </dl>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('category::category.tree', ['type' => 2])}}"><i class="layui-icon layui-icon-tree"></i>商品分类</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-templeate-1"></i>
                            <cite>产品管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('product::product.list')}}"><i class="layui-icon layui-icon-template-1"></i>产品列表</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-templeate-1"></i>
                            <cite>商品管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('goods::goods.list')}}"><i class="layui-icon layui-icon-template-1"></i>商品列表</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-component"></i>
                            <cite>仓库管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('warehouse::stockout.index')}}"><i class="layui-icon layui-icon-template-1"></i>备货管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('warehouse::entry.index')}}"><i class="layui-icon layui-icon-template-1"></i>入库管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('warehouse::egress.index')}}"><i class="layui-icon layui-icon-template-1"></i>出库管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('warehouse::saleReturn.index')}}"><i class="layui-icon layui-icon-template-1"></i>销售退货管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('warehouse::express.index')}}"><i class="layui-icon layui-icon-release"></i>快递管理</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-cart-simple"></i>
                            <cite>采购管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('purchase::supplier.index')}}"><i class="layui-icon layui-icon-group"></i>供应商管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('purchase::order.index')}}"><i class="layui-icon layui-icon-form"></i>采购订单管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('purchase::returnOrder.index')}}"><i class="layui-icon layui-icon-form"></i>采购退货单管理</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-cart"></i>
                            <cite>销售管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('sale::customer.index')}}"><i class="layui-icon layui-icon-group"></i>客户管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('sale::paymentMethod.index')}}"><i class="layui-icon layui-icon-auz"></i>付款方式申请</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('sale::order.index')}}"><i class="layui-icon layui-icon-form"></i>订单管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('sale::deliveryOrder.index')}}"><i class="layui-icon layui-icon-form"></i>出货单管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('sale::returnOrder.index')}}"><i class="layui-icon layui-icon-form"></i>退货单管理</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-rmb"></i>
                            <cite>财务管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('finance::collection.index')}}"><i class="layui-icon layui-icon-rate-solid"></i>收款管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('finance::pendingCollection.index')}}"><i class="layui-icon layui-icon-rate-solid"></i>销售应收款</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('finance::payment.index')}}"><i class="layui-icon layui-icon-rate"></i>付款管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('finance::pendingPayment.index')}}"><i class="layui-icon layui-icon-rate"></i>采购应付款</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('finance::account.index')}}"><i class="layui-icon layui-icon-template"></i>账户管理</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-set"></i>
                            <cite>系统管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="{{route('index::user.index')}}"><i class="layui-icon layui-icon-user"></i>用户管理</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('index::config.index')}}"><i class="layui-icon layui-icon-set-sm"></i>系统配置</a>
                            </dd>
                            <dd>
                                <a lay-href="{{route('index::index.logs')}}"><i class="layui-icon layui-icon-log"></i>系统日志</a>
                            </dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="{{route('index::index.home')}}" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>

        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="{{route('index::index.home')}}" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('/assets/layui-src/dist/layui.all.js')}}"></script>
<script>
    layui.config({
        base: '/assets/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
</script>
</body>
</html>


