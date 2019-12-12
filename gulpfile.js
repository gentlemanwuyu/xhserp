var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.copy('node_modules/layui-src/dist','public/assets/layui-src/dist');
    mix.copy('resources/assets/layuiadmin','public/assets/layuiadmin');
    mix.copy('resources/assets/dtree','public/assets/dtree');
    mix.copy('resources/assets/layui-table-dropdown','public/assets/layui-table-dropdown');
    mix.copy('resources/assets/images','public/assets/images');
    mix.copy('resources/assets/js','public/assets/js');
    mix.copy('resources/assets/css','public/assets/css');

    // 添加版本管理
    mix.version([
        'public/assets/css/erp.css',
        'public/assets/js/erp.js'
    ]);
});
