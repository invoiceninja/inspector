<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class InspectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'inspector');

        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            /** @var BladeComponent $component */
            foreach (config('inspector.components', []) as $alias => $component) {
                $blade->component($component, $alias, 'inspector');
            }
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('inspector.php'),
            ], 'inspector-config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/inspector'),
            ], 'views');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'inspector');

        // Register the main class to use with the facade
        $this->app->singleton('inspector', function () {
            return new Inspector;
        });
    }
}
