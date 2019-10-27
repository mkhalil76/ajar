<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AjarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind('apphelper', function() {
            return new \App\Helpers\AppHelper;
        });
    }
}
