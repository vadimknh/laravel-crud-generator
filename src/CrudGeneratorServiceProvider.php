<?php

namespace Vadimknh\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use Vadimknh\CrudGenerator\Commands\CrudGenerator;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/stubs', 'CrudGenerator');

        // php artisan vendor:publish 
        // Copy stubs to LaravelApp/resources/views
        $this->publishes([
            __DIR__.'/resources/stubs' => resource_path('views/vendor/vadimknh/stubs'),
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
           CrudGenerator::class,
        ]);
    }
}
