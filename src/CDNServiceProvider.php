<?php

namespace FelixL7\Cdn;

use FelixL7\Cdn\Facades\CDN as CDNFacade;
use FelixL7\Cdn\Libs\Bootstrap;
use Illuminate\Support\ServiceProvider;

class CDNServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-cdn');

        $this->app->singleton(CDN::class, function($app) {
            return new CDN;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('laravel-cdn.php'),
        ], 'config');

        CDNFacade::register([
            Bootstrap::class,
        ]);
    }
}
