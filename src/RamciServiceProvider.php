<?php

namespace Baygroup\Ramci;

use Illuminate\Support\ServiceProvider;

class RamciServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ramci/php' => config_path('ramci.php'),
        ], 'ramci');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ramci.php','ramci');

        $this->app->singleton('RamciApi', function ($app){
            $config     =   $app->make('config');
            $username   =   $config->get('ramci.username');
            $password   =   $config->get('ramci.password');
            $serviceURL =   $config->get('ramci.serviceUrl');

            return new RamciApi($username, $password, $serviceURL);

        });

        /*
        \App::bind('RamciApi', function()
        {
           //return new \App\Helper\ramciAPI();
            return new \Baygroup\Ramci\RamciApi();
        });
        */
    }

    public function provides() {
        return ['RamciApi'];
    }
}
