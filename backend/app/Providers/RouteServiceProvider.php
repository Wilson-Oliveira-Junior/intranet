<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the route cache file for the application.
     *
     * @var string
     */
    protected $routePath = 'api.php'; // ou web.php dependendo do que você está configurando

    /**
     * Define the "namespace" to assume when no namespace is given in a route.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Configure the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php')); // Ajuste o caminho se necessário
    }
}
