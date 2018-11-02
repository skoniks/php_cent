<?php
namespace SKONIKS\Centrifugo\Transport;
use Illuminate\Support\Facades\Redis;

class RedisClient {
    public function send($method, $params){
        $json = json_encode(['method' => $method, 'params' => $params]);
        $client = Redis::connection(config('centrifugo.redis.connection'))->client();
        try {
            return $client->rpush(config('centrifugo.redis.driver') . '.api', $json);
        } catch (\Exception $e) {
            return false;
        }
    }
}