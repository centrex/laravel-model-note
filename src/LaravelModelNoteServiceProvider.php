<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote;

use Illuminate\Support\ServiceProvider;

class LaravelModelNoteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-model-note');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-model-note');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-model-note.php'),
            ], 'laravel-model-note-config');

            // Publishing the migrations.
            /*$this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'laravel-model-note-migrations');*/

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-model-note'),
            ], 'laravel-model-note-views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-model-note'),
            ], 'laravel-model-note-assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-model-note'),
            ], 'laravel-model-note-lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-model-note');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-model-note', function () {
            return new LaravelModelNote();
        });
    }
}
