<?php
namespace Pondol\Board;

class BoardServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		//set migrations
        $this->publishes([
        	__DIR__.'/migrations/' => database_path('migrations'),
        ], 'migrations');

		//set assets
		$this->publishes([
        	__DIR__.'/assets/' => public_path('assets/pondol/board/assets'),
        ], 'public');

		// set package route
		if (! $this->app->routesAreCached()) {
	        require __DIR__.'/routes.php';
	    }

		// 기본 스킨을 위한 view 경로 지정.
		$this->loadViewsFrom(__DIR__.'/resources/views', 'pondol/board');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
