<?php

namespace RashiqulRony\ApiDoc;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class IDocServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::middlewareGroup('apidoc', config('apidoc.middleware', []));

        $this->registerRoutes();
        $this->registerPublishing();

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'apidoc');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/', 'apidoc');

        if ($this->app->runningInConsole()) {
            $this->commands([
                IDocGeneratorCommand::class,
                IDocCustomConfigGeneratorCommand::class,
            ]);
        }
    }

    /**
     * Get the apidoc route group configuration array.
     *
     * @return array
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../../resources/routes/apidoc.php', 'apidoc');
        });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../resources/lang' => $this->resourcePath('lang/vendor/apidoc'),
            ], 'apidoc-language');

            $this->publishes([
                __DIR__ . '/../../resources/views' => $this->resourcePath('views/vendor/apidoc'),
            ], 'idoc-views');

            $this->publishes([
                __DIR__ . '/../../config/apidoc.php' => app()->basePath() . '/config/apidoc.php',
            ], 'apidoc-config');
        }
    }

    /**
     * Get the apidoc route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'domain' => config('apidoc.domain', null),
            'prefix' => config('apidoc.path'),
            'middleware' => 'iapidocdoc',
            'as' => 'iapidocdoc.',
        ];
    }

    /**
     * Register the API doc commands.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/apidoc.php', 'apidoc');
    }

    /**
     * Return a fully qualified path to a given file.
     *
     * @param string $path
     *
     * @return string
     */
    public function resourcePath($path = '')
    {
        return app()->basePath() . '/resources' . ($path ? '/' . $path : $path);
    }
}
