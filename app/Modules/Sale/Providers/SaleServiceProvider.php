<?php
namespace App\Modules\Sale\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class SaleServiceProvider extends ServiceProvider
{
	/**
	 * Register the Sale module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Sale\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Sale module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('sale', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('sale', base_path('resources/views/vendor/sale'));
		View::addNamespace('sale', realpath(__DIR__.'/../Resources/Views'));
	}
}
