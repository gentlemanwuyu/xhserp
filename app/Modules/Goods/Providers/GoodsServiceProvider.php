<?php
namespace App\Modules\Goods\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class GoodsServiceProvider extends ServiceProvider
{
	/**
	 * Register the Goods module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Goods\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Goods module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('goods', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('goods', base_path('resources/views/vendor/goods'));
		View::addNamespace('goods', realpath(__DIR__.'/../Resources/Views'));
	}
}
