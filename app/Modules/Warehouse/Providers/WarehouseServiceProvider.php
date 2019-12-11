<?php
namespace App\Modules\Warehouse\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{
	/**
	 * Register the Warehouse module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Warehouse\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Warehouse module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('warehouse', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('warehouse', base_path('resources/views/vendor/warehouse'));
		View::addNamespace('warehouse', realpath(__DIR__.'/../Resources/Views'));
	}
}
