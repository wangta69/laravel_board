<?php
namespace Pondol\Bbs;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Route;

class BbsServiceProvider extends ServiceProvider {



/**
     * Where the route file lives, both inside the package and in the app (if overwritten).
     *
     * @var string
     */
    public $routeFilePath = '/routes/bbs/base.php';
    
	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        
        echo "11111111111111111111111111111111111111";
		//set migrations
        $this->publishes([
        	__DIR__.'/migrations/' => database_path('migrations'),
        ], 'migrations');

		//set assets
		$this->publishes([
        	__DIR__.'/assets/' => public_path('assets/pondol/board/assets'),
        ], 'public');

		// set package route
		//if (! $this->app->routesAreCached()) {
	    //    require __DIR__.'/routes.php';
	   // }

	    // LOAD Routes
	    //$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		require __DIR__ . '/routes/web.php';
        // LOAD THE VIEWS
        // - first the published views (in case they have any changes)
        $this->loadViewsFrom(resource_path('views/bbs'), __DIR__.'/resources/views/bbs');
        // - then the stock views that come with the package, in case a published view might be missing
        //$this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'bbs');
        
        
        //$this->registerAdminMiddleware($this->app->router);
        //$this->setupRoutes($this->app->router);
        $this->publishFiles();
        $this->loadHelpers();
        
		// set path to views
		//$this->loadViewsFrom(__DIR__.'/resources/views', 'bbs');
        
        //$this->publishes([
        //    __DIR__.'/resources/views' => resource_path('views/bbs'),
       // ]);
    }


/**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
//    public function map(Router $router)
//    {
//        echo "===============================================";
        
        

        /*
        |--------------------------------------------------------------------------
        | Web Router 
        |--------------------------------------------------------------------------
        */

//        $router->group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'Bbs'], function ($router) {
//            require app_path('Http/routes.web.php');
 //       });

        /*
        |--------------------------------------------------------------------------
        | Api Router 
        |--------------------------------------------------------------------------
        */

        //$router->group(['namespace' => $this->apiNamespace], function ($router) {
        //    require app_path('Http/routes.api.php');
        //});

//    }
    
    
    /**
         * Define the routes for the application.
         *
         * @param \Illuminate\Routing\Router $router
         *
         * @return void
         */
         /*
        public function setupRoutes(Router $router)
        {
            // by default, use the routes file provided in vendor
            $routeFilePathInUse = __DIR__.$this->routeFilePath;
            // but if there's a file with the same name in routes/backpack, use that one
            if (file_exists(base_path().$this->routeFilePath)) {
                $routeFilePathInUse = base_path().$this->routeFilePath;
            }
            $this->loadRoutesFrom($routeFilePathInUse);
        }
        
        */
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
