<?php

namespace ConfrariaWeb\Church\Providers;

use Illuminate\Support\ServiceProvider;
use ConfrariaWeb\Church\Commands\InstallPackage;

class ChurchServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPackage::class
            ]);
        }
        $this->registerSeedsFrom(__DIR__ . '/../../databases/Seeds');
    }

    public function register()
    {

    }

}
