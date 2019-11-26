<?php
namespace App\Modules\Category\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
	/**
	 * Register the Category module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Category\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Category module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('category', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('category', base_path('resources/views/vendor/category'));
		View::addNamespace('category', realpath(__DIR__.'/../Resources/Views'));
	}
}
