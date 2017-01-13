<?php

namespace Ipunkt\LaravelJsonApi;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Ipunkt\LaravelJsonApi\Resources\ResourceManager;
use Route;

class LaravelJsonApiServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResourceManager::class, function () {
            return new ResourceManager($this->app);
        });
    }

    /**
     * boot with router
     */
    public function boot()
    {
        $configFile = realpath(__DIR__ . '/../config/json-api.php');

        $this->mergeConfigFrom($configFile, 'json-api');
        $this->publishes([
            $configFile => config_path('json-api.php'),
        ]);

        if (config('json-api.routes.configure', false)) {
            if (!$this->app->routesAreCached()) {
                Route::group([], function (Router $router) {
                    require __DIR__ . '/../routes/api.php';
                });
            }
        }
    }
}