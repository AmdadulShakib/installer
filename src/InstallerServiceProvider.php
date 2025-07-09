<?php

namespace amdadulshakib\installer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class InstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'installer');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

         $this->publishes([
             __DIR__.'/resources/views' => resource_path('views/vendor/installer'),
         ], 'installer-views');

         $this->app['router']->pushMiddlewareToGroup('web', \amdadulshakib\installer\Middleware\IsNotInstalled::class);
    }

    public function register()
    {
        $this->app['router']->aliasMiddleware('install.lock', \amdadulshakib\installer\Middleware\IsNotInstalled::class);
    }
}
