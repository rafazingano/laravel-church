<?php

namespace ConfrariaWeb\Church\Providers;

use ConfrariaWeb\Vendor\Traits\ProviderTrait;
use Illuminate\Support\ServiceProvider;
use ConfrariaWeb\Church\Commands\InstallPackage;

class ChurchServiceProvider extends ServiceProvider
{

    use ProviderTrait;

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
