<?php
namespace App\Modules\Index\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class IndexServiceProvider extends ServiceProvider
{
	/**
	 * Register the Index module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Index\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Index module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('index', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('index', base_path('resources/views/vendor/index'));
		View::addNamespace('index', realpath(__DIR__.'/../Resources/Views'));
	}
}
