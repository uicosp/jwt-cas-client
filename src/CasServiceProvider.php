<?php

namespace Uicosp\JwtCasClient;

use Illuminate\Support\ServiceProvider;

class CasServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('jwt-cas-client.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'jwt-cas-client'
        );
    }
}
