<?php
namespace App\Modules\Purchase\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{
	/**
	 * Register the Purchase module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Purchase\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Purchase module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('purchase', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('purchase', base_path('resources/views/vendor/purchase'));
		View::addNamespace('purchase', realpath(__DIR__.'/../Resources/Views'));
	}
}
