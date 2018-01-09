<?php
namespace Pondol\Bbs;

//use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
//use Route;

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
        $this->app->bind('bbs', function($app) {
            return new Bbs;
        });
    }

	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    //public function boot(\Illuminate\Routing\Router $router)
	public function boot()
    {

         if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Https/routes/web.php';
        }
        

		//set migrations (automatic)
		
		//Log::info(__DIR__.'/migrations/');
        //Log::info(realpath(__DIR__.'/migrations/'));
		$this->loadMigrationsFrom(__DIR__.'/migrations/');
        //$this->artisan('migrate');
        \Artisan::call('migrate');
    
        //if you use this one you have to command php artisan migrate
        //$this->publishes([
        //	__DIR__.'/migrations/' => database_path('migrations'),
        //], 'migrations');
     

		//set assets
		$this->publishes([
        	__DIR__.'/Https/public/assets/' => public_path('assets/pondol/bbs'),
        ], 'public');

	    
        // LOAD THE VIEWS
        // - first the published views (in case they have any changes)
         $this->publishes([
            __DIR__.'/resources/views/bbs' => resource_path('views/bbs'),
        ]);
        // - loadViews  : 상기와 다른 점음  resources/views/bbs 에 없을 경우 아래 것에서 처리한다. for user modify
         $this->loadViewsFrom(__DIR__.'/resources/views/bbs', 'bbs');
        
        //default controller file set
        $this->publishes([
            __DIR__.'/Https/Controllers/Bbs/' => app_path('Http/Controllers/Bbs'),
        ]);

    }
}
