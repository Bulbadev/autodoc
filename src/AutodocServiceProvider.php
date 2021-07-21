<?php

namespace Bulbadev\Autodoc;

use Bulbadev\Autodoc\Collectors\Collector;
use Bulbadev\Autodoc\Commands\AutodocCommand;
use Bulbadev\Autodoc\Strategies\BuildStrategy;
use Illuminate\Support\ServiceProvider;

class AutodocServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/autodoc.php' => config_path('autodoc.php'),
            ],
            'autodoc-config'
        );

        $this->publishes(
            [
                __DIR__ . '/../resources/views/swagger.blade.php' => resource_path('views/autodoc/swagger.blade.php'),
            ],
            'autodoc-view'
        );

        $this->publishes(
            [
                __DIR__ . '/../public' => public_path('vendor/autodoc'),
            ],
            'autodoc-public'
        );

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'autodoc');

        $this->commands(
            [
                AutodocCommand::class,
            ]
        );
    }

    public function register()
    {

        $this->app->singleton(AutodocRunner::class, AutodocRunner::class);

        $this->app->singleton(BuildStrategy::class, function ($app) {
            $classPath = 'Bulbadev\Autodoc\Strategies\\' . env('AUTODOC_STRATEGY', 'AddNew');

            return new $classPath();
        });

        $this->app->singleton(Collector::class, config('autodoc.data_collector'));
    }
}
