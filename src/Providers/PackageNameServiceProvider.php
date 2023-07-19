<?php

declare(strict_types=1);

namespace VendorName\PackageName\Providers;

use Illuminate\Support\ServiceProvider;

final class PackageNameServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'package_name');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'package_name');
        $this->loadRoutesFrom(__DIR__ . '/../../routes');

        $this->publishes([
            __DIR__ . '/../../config/package_name.php' => config_path('package_name.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/package_name.php',
            'package_name'
        );

        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/package_name'),
        ], ['package_name-assets', 'laravel-assets']);

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/package_name'),
        ]);

        $this->commands([]);
    }
}
