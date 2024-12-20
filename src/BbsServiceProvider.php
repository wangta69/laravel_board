<?php
namespace Pondol\Bbs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Pondol\Bbs\View\Components\ItemCommnents;
use Pondol\Bbs\View\Components\BbsItemList;
use Pondol\Bbs\View\Components\BbsCard;

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
  }

  /**
     * Bootstrap any application services.
     *
     * @return void
     */
  public function boot()
  {

    // Register config
    if (!config()->has('pondol-bbs')) {
      $this->publishes([
        __DIR__ . '/config/pondol-bbs.php' => config_path('pondol-bbs.php'),
      ], 'config');
    }
    $this->mergeConfigFrom(
      __DIR__ . '/config/pondol-bbs.php',
      'pondol-bbs'
    );


    // Register routes
    $this->loadBbsRoutes();

    $this->loadMigrationsFrom(__DIR__.'/migrations/');

    $this->commands([
      Console\InstallCommand::class,
    ]);

    // LOAD THE VIEWS
    // - first the published views (in case they have any changes)
    $this->publishes([
      // copy resource 파일
      __DIR__.'/resources/views/components' => resource_path('views/bbs/components'),
      __DIR__.'/resources/templates' => resource_path('views/bbs/templates'),
      __DIR__.'/resources/assets/' => public_path('pondol/bbs'),
      // controllers;
      // __DIR__.'/Http/Controllers/Bbs/' => app_path('Http/Controllers/Bbs')
    ]);
    
    // - loadViews  : 상기와 다른 점음  resources/views/bbs 에 없을 경우 아래 것에서 처리한다. for user modify
    $this->loadViewsFrom(__DIR__.'/resources/views', 'bbs');

    Blade::component('item-comments', ItemCommnents::class);
    Blade::component('bbs-lists', BbsItemList::class);
    Blade::component('bbs-card', BbsCard::class);
    
    // Language Files
    $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'bbs');
  }

  private function loadBbsRoutes()
  {
    $config = config('pondol-bbs.route_admin');
    Route::prefix($config['prefix'])
      ->as($config['as'])
      ->middleware($config['middleware'])
      ->namespace('Pondol\Bbs\Http\Controllers\Admin')
      ->group(__DIR__ . '/routes/admin.php');

    $config = config('pondol-bbs.route_front');
    Route::prefix($config['prefix'])
      ->as($config['as'])
      ->middleware($config['middleware'])
      ->namespace('Pondol\Bbs\Http\Controllers')
      ->group(__DIR__ . '/routes/web.php');

    $config = config('pondol-bbs.route_api');
    Route::prefix($config['prefix'])
      ->as($config['as'])
      ->middleware($config['middleware'])
      ->namespace('Pondol\Bbs\Http\Controllers')
      ->group(__DIR__ . '/routes/api.php');
  }
}
