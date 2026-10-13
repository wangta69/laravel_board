<?php

namespace Pondol\Bbs;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Pondol\Bbs\View\Components\BbsCard;
use Pondol\Bbs\View\Components\BbsItemList;
use Pondol\Bbs\View\Components\ItemCommnents;

class BbsServiceProvider extends ServiceProvider
{
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
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Register config
        $this->publishes([
            __DIR__.'/config/pondol-bbs.php' => config_path('pondol-bbs.php'),
        ], 'bbs-config');

        $this->mergeConfigFrom(
            __DIR__.'/config/pondol-bbs.php',
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
        // LOAD THE VIEWS
        $this->publishes([
            // 1. 뷰 템플릿 전체를 라라벨 표준 오버라이드 경로(views/vendor/bbs)로 복사
            __DIR__.'/resources/views' => resource_path('views/vendor/bbs'),
            // 2. CSS/JS 에셋 복사
            __DIR__.'/resources/assets' => public_path('pondol/bbs'),
        ], 'bbs');

        // 패키지 뷰 로드 (1순위: views/vendor/bbs 확인 -> 2순위: 패키지 내부 views 실행)
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
            ->group(__DIR__.'/routes/admin.php');

        $front_config = config('pondol-bbs.route_front', []);
        $front_user_middleware = $front_config['middleware'] ?? [];
        $front_final_middleware = array_unique(array_merge(['web'], $front_user_middleware));
        Route::middleware($front_final_middleware)
            ->prefix($front_config['prefix'] ?? 'bbs')
            ->as($front_config['as'] ?? 'bbs.')
            ->namespace('Pondol\Bbs\Http\Controllers')
            ->group(__DIR__.'/routes/web.php');

        $api_config = config('pondol-bbs.route_api', []);
        $api_user_middleware = $api_config['middleware'] ?? [];
        // API 라우트는 기본적으로 'api' 그룹에 속합니다.
        $api_final_middleware = array_unique(array_merge(['api'], $api_user_middleware));

        Route::middleware($api_final_middleware)
            ->prefix($api_config['prefix'] ?? 'api/v1/bbs')
            ->as($api_config['as'] ?? 'bbs.api.')
            ->namespace('Pondol\Bbs\Http\Controllers')
            ->group(__DIR__.'/routes/api.php');
    }
}
