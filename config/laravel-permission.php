<?php

return [

    'models' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => \App\Modules\Index\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => \App\Modules\Index\Models\Role::class,

    ],

    'table_names' => [

        /*
         * The table that your application uses for users. This table's model will
         * be using the "HasRoles" and "HasPermissions" traits.
         */

        'users' => 'users',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your users permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'user_has_permissions' => 'user_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your users roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'user_has_roles' => 'user_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'foreign_keys' => [

        /*
         * The name of the foreign key to the users table.
         */
        'users' => 'user_id',
    ],

    'erp_permissions' => [
        [ // 分类管理
            'name' => 'category_management', 'display_name' => '分类管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 产品分类
                    'name' => 'product_category', 'display_name' => '产品分类', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_product_category', 'display_name' => '添加产品分类', 'route' => '', 'type' => 2],
                        ['name' => 'edit_product_category', 'display_name' => '编辑产品分类', 'route' => '', 'type' => 2],
                        ['name' => 'delete_product_category', 'display_name' => '删除产品分类', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 商品分类
                    'name' => 'goods_category', 'display_name' => '商品分类', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_goods_category', 'display_name' => '添加商品分类', 'route' => '', 'type' => 2],
                        ['name' => 'edit_goods_category', 'display_name' => '编辑商品分类', 'route' => '', 'type' => 2],
                        ['name' => 'delete_goods_category', 'display_name' => '删除商品分类', 'route' => '', 'type' => 2],
                    ]
                ],
            ]
        ],
        [ // 产品管理
            'name' => 'product_management', 'display_name' => '产品管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 产品列表
                    'name' => 'product_list', 'display_name' => '产品列表', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'product_detail', 'display_name' => '产品详情', 'route' => '', 'type' => 2],
                        ['name' => 'set_inventory', 'display_name' => '设置库存', 'route' => '', 'type' => 2],
                        ['name' => 'add_product', 'display_name' => '添加产品', 'route' => '', 'type' => 2],
                        ['name' => 'edit_product', 'display_name' => '编辑产品', 'route' => '', 'type' => 2],
                        ['name' => 'delete_product', 'display_name' => '删除产品', 'route' => '', 'type' => 2],
                    ]
                ],
            ]
        ],
        [ // 商品管理
            'name' => 'goods_management', 'display_name' => '商品管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 商品列表
                    'name' => 'goods_list', 'display_name' => '商品列表', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'goods_detail', 'display_name' => '商品详情', 'route' => '', 'type' => 2],
                        ['name' => 'add_goods', 'display_name' => '添加商品', 'route' => '', 'type' => 2],
                        ['name' => 'edit_goods', 'display_name' => '编辑商品', 'route' => '', 'type' => 2],
                        ['name' => 'delete_goods', 'display_name' => '删除商品', 'route' => '', 'type' => 2],
                    ]
                ],
            ]
        ],
        [ // 仓库管理
            'name' => 'warehouse_management', 'display_name' => '仓库管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 备货管理
                    'name' => 'stockout_management', 'display_name' => '备货管理', 'route' => '', 'type' => 1, 'children' => []
                ],
                [ // 入库管理
                    'name' => 'entry_management', 'display_name' => '入库管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'entry', 'display_name' => '入库', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 出库管理
                    'name' => 'egress_management', 'display_name' => '出库管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'egress_detail', 'display_name' => '出库详情', 'route' => '', 'type' => 2],
                        ['name' => 'egress_finished', 'display_name' => '出库完成', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 销售退货管理
                    'name' => 'sale_return_management', 'display_name' => '销售退货管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'sale_return_handle', 'display_name' => '处理销售退货', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 采购退货管理
                    'name' => 'purchase_return_management', 'display_name' => '采购退货管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'purchase_return_egress', 'display_name' => '采购退货出库', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 快递管理
                    'name' => 'express_management', 'display_name' => '快递管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_express', 'display_name' => '添加快递', 'route' => '', 'type' => 2],
                        ['name' => 'edit_express', 'display_name' => '编辑快递', 'route' => '', 'type' => 2],
                        ['name' => 'delete_express', 'display_name' => '删除快递', 'route' => '', 'type' => 2],
                    ]
                ],
            ]
        ],
        [ // 采购管理
            'name' => 'purchase_management', 'display_name' => '采购管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 供应商管理
                    'name' => 'supplier_management', 'display_name' => '供应商管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'supplier_detail', 'display_name' => '供应商详情', 'route' => '', 'type' => 2],
                        ['name' => 'add_supplier', 'display_name' => '添加供应商', 'route' => '', 'type' => 2],
                        ['name' => 'edit_supplier', 'display_name' => '编辑供应商', 'route' => '', 'type' => 2],
                        ['name' => 'delete_supplier', 'display_name' => '删除供应商', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 采购订单管理
                    'name' => 'purchase_order_management', 'display_name' => '采购订单管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'purchase_order_detail', 'display_name' => '采购订单详情', 'route' => '', 'type' => 2],
                        ['name' => 'add_purchase_order', 'display_name' => '添加采购订单', 'route' => '', 'type' => 2],
                        ['name' => 'review_purchase_order', 'display_name' => '审核采购订单', 'route' => '', 'type' => 2],
                        ['name' => 'return_purchase_order', 'display_name' => '采购订单退货', 'route' => '', 'type' => 2],
                        ['name' => 'cancel_purchase_order', 'display_name' => '取消采购订单', 'route' => '', 'type' => 2],
                        ['name' => 'edit_purchase_order', 'display_name' => '编辑采购订单', 'route' => '', 'type' => 2],
                        ['name' => 'delete_purchase_order', 'display_name' => '删除采购订单', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 采购退货单管理
                    'name' => 'purchase_return_order_management', 'display_name' => '采购退货单管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'purchase_return_order_detail', 'display_name' => '采购退货单详情', 'route' => '', 'type' => 2],
                        ['name' => 'delete_purchase_return_order', 'display_name' => '删除采购退货单', 'route' => '', 'type' => 2],
                    ]
                ],
            ]
        ],
        [ // 销售管理
            'name' => 'sale_management', 'display_name' => '销售管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 客户管理
                    'name' => 'customer_management', 'display_name' => '客户管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'customer_detail', 'display_name' => '客户详情', 'route' => '', 'type' => 2],
                        ['name' => 'add_customer', 'display_name' => '添加客户', 'route' => '', 'type' => 2],
                        ['name' => 'edit_customer', 'display_name' => '编辑客户', 'route' => '', 'type' => 2],
                        ['name' => 'delete_customer', 'display_name' => '删除客户', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 付款方式申请
                    'name' => 'payment_method_application', 'display_name' => '付款方式申请', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'payment_method_application_detail', 'display_name' => '付款方式申请详情', 'route' => '', 'type' => 2],
                        ['name' => 'edit_payment_method_application', 'display_name' => '编辑付款方式申请', 'route' => '', 'type' => 2],
                        ['name' => 'review_payment_method_application', 'display_name' => '审核付款方式申请', 'route' => '', 'type' => 2],
                        ['name' => 'delete_payment_method_application', 'display_name' => '删除付款方式申请', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 订单管理
                    'name' => 'order_management', 'display_name' => '订单管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'order_detail', 'display_name' => '订单详情', 'route' => '', 'type' => 2],
                        ['name' => 'add_order', 'display_name' => '添加订单', 'route' => '', 'type' => 2],
                        ['name' => 'review_order', 'display_name' => '审核订单', 'route' => '', 'type' => 2],
                        ['name' => 'edit_order', 'display_name' => '编辑订单', 'route' => '', 'type' => 2],
                        ['name' => 'delete_order', 'display_name' => '删除订单', 'route' => '', 'type' => 2],
                        ['name' => 'cancel_order', 'display_name' => '取消订单', 'route' => '', 'type' => 2],
                        ['name' => 'order_delivery', 'display_name' => '订单出货', 'route' => '', 'type' => 2],
                        ['name' => 'order_return', 'display_name' => '订单退货', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 出货管理
                    'name' => 'delivery_order_management', 'display_name' => '出货管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'delivery_order_detail', 'display_name' => '出货单详情', 'route' => '', 'type' => 2],
                        ['name' => 'edit_delivery_order', 'display_name' => '编辑出货单', 'route' => '', 'type' => 2],
                        ['name' => 'delete_delivery_order', 'display_name' => '删除出货单', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 退货单管理
                    'name' => 'return_order_management', 'display_name' => '退货单管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'return_order_detail', 'display_name' => '退货单详情', 'route' => '', 'type' => 2],
                        ['name' => 'edit_return_order', 'display_name' => '编辑退货单', 'route' => '', 'type' => 2],
                        ['name' => 'review_return_order', 'display_name' => '审核退货单', 'route' => '', 'type' => 2],
                        ['name' => 'delete_return_order', 'display_name' => '删除退货单', 'route' => '', 'type' => 2],
                    ]
                ],
            ]
        ],
        [ // 财务管理
            'name' => 'finance_management', 'display_name' => '财务管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 收款管理
                    'name' => 'collection_management', 'display_name' => '收款管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_collection', 'display_name' => '添加收款单', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 销售应收款
                    'name' => 'pending_collection', 'display_name' => '销售应收款', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'deduct_pending_collection', 'display_name' => '抵扣销售应收款', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 付款管理
                    'name' => 'payment_management', 'display_name' => '付款管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_payment', 'display_name' => '添加付款单', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 采购应收款
                    'name' => 'pending_payment', 'display_name' => '采购应收款', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'deduct_pending_payment', 'display_name' => '抵扣采购应收款', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 账户管理
                    'name' => 'account_management', 'display_name' => '账户管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_account', 'display_name' => '添加账户', 'route' => '', 'type' => 2],
                        ['name' => 'edit_account', 'display_name' => '编辑账户', 'route' => '', 'type' => 2],
                        ['name' => 'delete_account', 'display_name' => '删除账户', 'route' => '', 'type' => 2],
                    ]
                ],
            ]
        ],
        [ // 系统管理
            'name' => 'system_management', 'display_name' => '系统管理', 'route' => '', 'type' => 1, 'children' =>
            [
                [ // 用户管理
                    'name' => 'user_management', 'display_name' => '用户管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'assign_user_permission', 'display_name' => '分配用户权限', 'route' => '', 'type' => 2],
                        ['name' => 'add_user', 'display_name' => '添加用户', 'route' => '', 'type' => 2],
                        ['name' => 'edit_user', 'display_name' => '编辑用户', 'route' => '', 'type' => 2],
                        ['name' => 'delete_user', 'display_name' => '删除用户', 'route' => '', 'type' => 2],
                    ]
                ],
//                [ // 组织结构
//                    'name' => 'organization', 'display_name' => '组织结构', 'route' => '', 'type' => 1, 'children' =>
//                    [
//
//                    ]
//                ],
                [ // 角色管理
                    'name' => 'role_management', 'display_name' => '角色管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_role', 'display_name' => '添加角色', 'route' => '', 'type' => 2],
                        ['name' => 'edit_role', 'display_name' => '编辑角色', 'route' => '', 'type' => 2],
                        ['name' => 'delete_role', 'display_name' => '删除角色', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 权限管理
                    'name' => 'permission_management', 'display_name' => '权限管理', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_permission', 'display_name' => '添加权限', 'route' => '', 'type' => 2],
                        ['name' => 'edit_permission', 'display_name' => '编辑权限', 'route' => '', 'type' => 2],
                        ['name' => 'delete_permission', 'display_name' => '删除权限', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 系统配置
                    'name' => 'system_config', 'display_name' => '系统配置', 'route' => '', 'type' => 1, 'children' =>
                    [
                        ['name' => 'add_system_config', 'display_name' => '添加系统配置', 'route' => '', 'type' => 2],
                        ['name' => 'edit_system_config', 'display_name' => '编辑系统配置', 'route' => '', 'type' => 2],
                        ['name' => 'delete_system_config', 'display_name' => '删除系统配置', 'route' => '', 'type' => 2],
                    ]
                ],
                [ // 系统日志
                    'name' => 'system_log', 'display_name' => '系统日志', 'route' => '', 'type' => 1, 'children' => []
                ],
            ]
        ],
    ],
];
