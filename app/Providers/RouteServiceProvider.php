<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot()
    {
        parent::boot();
        if (config("app.env") != "production" ) {
            $url = $this->app['url'];
            $url->forceRootUrl(config('app.url'));
        }
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map()
    {
        Route::group(['namespace' => $this->namespace], function () {
            require base_path('routes/web.php');
            require base_path('routes/api.php');
            require base_path('routes/admin.php');
            require base_path('routes/ajax.php');
            require base_path('routes/clientes.php');
        });
    }
}
