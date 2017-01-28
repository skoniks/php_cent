<?php

namespace SKONIKS\Centrifuge;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use SKONIKS\Centrifuge\Clients\Http;
use SKONIKS\Centrifuge\Clients\Redis;
use SKONIKS\Centrifuge\Contracts\Centrifuge;

class CentrifugeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/centrifuge.php' => config_path('centrifuge.php'),]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/centrifuge.php', 'centrifuge');
        $this->app->singleton(Centrifuge::class, function ($app) {
            $config = $app->make('config');
            if($config->get('centrifuge.transport') == 'redis') {
                $client = $app->make('redis')->connection($config->get('centrifuge.redisConnection'));
                return new Redis($client,$config->get('centrifuge.driver'));
            } else {
                $client = new Client(['base_uri' => $config->get('centrifuge.baseUrl')]);
                return new Http($client, $config);
            }
        });
    }
}
