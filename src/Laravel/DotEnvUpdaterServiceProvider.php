<?php

namespace CodeZero\DotEnvUpdater\Laravel;

use CodeZero\DotEnvUpdater\DotEnvUpdater;
use Illuminate\Support\ServiceProvider;

class DotEnvUpdaterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DotEnvUpdater::class, function ($app) {
            return new DotEnvUpdater($app->environmentFilePath());
        });
    }
}
