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
    $admin_config = config('pondol-bbs.route_admin', []);
    $admin_user_middleware = $admin_config['middleware'] ?? [];
    $admin_final_middleware = array_unique(array_merge(['web'], $admin_user_middleware));

    Route::middleware($admin_final_middleware)
        ->prefix($admin_config['prefix'] ?? 'bbs/admin')
        ->as($admin_config['as'] ?? 'bbs.admin.')
        ->namespace('Pondol\Bbs\Http\Controllers\Admin')
        ->group(__DIR__ . '/routes/admin.php');

    $front_config = config('pondol-bbs.route_front', []);
    $front_user_middleware = $front_config['middleware'] ?? [];
    $front_final_middleware = array_unique(array_merge(['web'], $front_user_middleware));
    Route::middleware($front_final_middleware)
        ->prefix($front_config['prefix'] ?? 'bbs')
        ->as($front_config['as'] ?? 'bbs.')
        ->namespace('Pondol\Bbs\Http\Controllers')
        ->group(__DIR__ . '/routes/web.php');;

    $api_config = config('pondol-bbs.route_api', []);
    $api_user_middleware = $api_config['middleware'] ?? [];
    // API 라우트는 기본적으로 'api' 그룹에 속합니다.
    $api_final_middleware = array_unique(array_merge(['api'], $api_user_middleware));

    Route::middleware($api_final_middleware)
        ->prefix($api_config['prefix'] ?? 'api/v1/bbs')
        ->as($api_config['as'] ?? 'bbs.api.')
        ->namespace('Pondol\Bbs\Http\Controllers')
        ->group(__DIR__ . '/routes/api.php');
  }
}
