<?php

namespace RashiqulRony\ApiDoc;

class IDocLumenServiceProvider extends IDocServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        $this->registerPublishing();

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'apidoc');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/', 'apidoc');

        if ($this->app->runningInConsole()) {
            $this->commands([
                IDocGeneratorCommand::class,
            ]);
        }
    }

    protected function registerRoutes()
    {
        app()->router->group($this->routeConfiguration(), function ($router) {
            require __DIR__ . '/../../resources/routes/lumen.php';
        });
    }

    protected function routeConfiguration()
    {
        return [
            'domain' => config('apidoc.domain'),
            'prefix' => config('apidoc.path'),
            'middleware' => config('apidoc.middleware', []),
            'as' => 'apidoc',
        ];
    }
}
