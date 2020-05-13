<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //注册本地服务，在这里注册比在app.php里providers加有个好处，加快Laravel启动时间
        if (env('APP_ENV') == 'local')
        {
            //注册调试工具
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            //注册ide帮助工具
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
