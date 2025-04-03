<?php

namespace Rangkotodotcom\Pushnotif;

use Rangkotodotcom\Pushnotif\Pushnotif;
use Illuminate\Support\ServiceProvider;
use Rangkotodotcom\Pushnotif\Networks\HttpClient;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * Class PushnotifServiceProvider
 * @package Rangkotodotcom\Pushnotif\Providers
 */
class PushnotifServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/pushnotif.php' => config_path('pushnotif.php'),
            ], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('pushnotif');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pushnotif.php', 'pushnotif');

        $this->app->singleton('pushnotif', function () {
            return new Pushnotif(new HttpClient(config('pushnotif.pushnotif_mode'), config('pushnotif.pushnotif_client_id'), config('pushnotif.pushnotif_client_secret'), config('pushnotif.pushnotif_main_domain')));
        });
    }
}
