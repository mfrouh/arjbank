<?php

namespace MFrouh\ArjBank\Providers;

use MFrouh\ArjBank\BaseClass;
use Illuminate\Support\ServiceProvider;

class ArjBankServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ArjBank.php', 'ArjBank');

        $this->app->bind('ArjBank', function ($app) {
            return new BaseClass();
        });
    }

    public function boot()
    {
    
    }
}
