<?php
namespace App\Modules\Finance\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
	/**
	 * Register the Finance module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Finance\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Finance module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('finance', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('finance', base_path('resources/views/vendor/finance'));
		View::addNamespace('finance', realpath(__DIR__.'/../Resources/Views'));
	}
}
