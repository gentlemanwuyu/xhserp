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

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

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
        [ // 分配管理
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
        ]
    ],
];
