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
    public function boot(){}

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Centrifuge::class, function ($app) {
            if(config('centrifuge.transport') == 'redis') {
                $client = $app->make('redis')->connection(config('centrifuge.redisConnection'));
                return new Redis($client, config('centrifuge.driver'));
            } else {
                $client = new Client(['base_uri' => config('centrifuge.baseUrl')]);
                return new Http($client);
            }
        });
    }
}
