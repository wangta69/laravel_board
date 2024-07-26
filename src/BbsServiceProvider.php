<?php
namespace Pondol\Bbs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Blade;
use Pondol\Bbs\View\Components\ItemCommnents;

class BbsServiceProvider extends ServiceProvider {


/**
     * Where the route file lives, both inside the package and in the app (if overwritten).
     *
     * @var string
     */
   // public $routeFilePath = '/routes/bbs/base.php';

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    // $this->app->bind('bbs', function($app) {
    $this->app->singleton('bbs', function($app) {
      return new Bbs;
    });

    $this->mergeConfigFrom(
      // __DIR__.'/../config/courier.php', 'courier'
      __DIR__.'/config/bbs.php', 'bbs'
    );
  }

  /**
     * Bootstrap any application services.
     *
     * @return void
     */
    //public function boot(\Illuminate\Routing\Router $router)
  public function boot()
  {
    // if (!$this->app->routesAreCached()) {
    //   // Log::info(__DIR__ . '/Https/routes/web.php');
    //   //require __DIR__ . '/Https/routes/web.php';
    //   require_once __DIR__ . '/Https/routes/web.php';
    //   require_once __DIR__ . '/Https/routes/api.php';
    // }
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		$this->loadRoutesFrom(__DIR__.'/routes/api.php');

    $this->loadMigrationsFrom(__DIR__.'/migrations/');
      //$this->artisan('migrate');
      \Artisan::call('migrate');

      //if you use this one you have to command php artisan migrate
      //$this->publishes([
      //  __DIR__.'/migrations/' => database_path('migrations'),
      //], 'migrations');


    //set assets
    $this->publishes([
      __DIR__.'/public/assets/' => public_path('assets/pondol/bbs'),
    ], 'public');

    // copy config
    // $this->publishes([__DIR__.'/config/bbs.php' => config_path('bbs.php')], 'public');


    // LOAD THE VIEWS
    // - first the published views (in case they have any changes)
    $this->publishes([
      // copy config
      __DIR__.'/config/bbs.php' => config_path('bbs.php'),
      // copy resource 파일
      __DIR__.'/resources/views/bbs/components' => resource_path('views/bbs/components'),
      __DIR__.'/resources/views/bbs/templates' => resource_path('views/bbs/templates'),
      // controllers;
      __DIR__.'/Https/Controllers/Bbs/' => app_path('Http/Controllers/Bbs')
    ]);
    
    // - loadViews  : 상기와 다른 점음  resources/views/bbs 에 없을 경우 아래 것에서 처리한다. for user modify
    $this->loadViewsFrom(__DIR__.'/resources/views/bbs', 'bbs');

    // $this->publishes([
    //   __DIR__.'/Https/Controllers/Bbs/' => app_path('Http/Controllers/Bbs'),
    // ]);
    //  var_dump($result);

    Blade::component('item-comments', ItemCommnents::class);
    // <x-item-comments/>

    // Language Files
    $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'bbs');
  }
}
