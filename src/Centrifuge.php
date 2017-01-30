<?php

namespace SKONIKS\Centrifuge;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;
use SKONIKS\Centrifuge\Transport\CHttp;
use SKONIKS\Centrifuge\Transport\CRedis;
use SKONIKS\Centrifuge\Centrifuge;

class Centrifuge
{
    public function publish($channel, $data)
    {
        $params = [
            'channel' => $channel,
            'data' => $data,
        ];
        return $this->send('publish', $params);
    }
    public function unsubscribe($channel, $user)
    {
        return $this->send('unsubscribe', [
            'channel' => $channel,
            'user' => $user,
        ]);
    }
    public function disconnect($user)
    {
        return $this->send('disconnect', [
            'user' => $user,
        ]);
    }
    public function presence($channel)
    {
        return $this->send('presence', [
            'channel' => $channel,
        ]);
    }
    public function history($channel)
    {
        return $this->send('history', [
            'channel' => $channel,
        ]);
    }
    public function generateToken($userId, $timestamp, $info = '')
    {
        $ctx = hash_init('sha256', HASH_HMAC, config('centrifuge.secret'));
        hash_update($ctx, $userId);
        hash_update($ctx, $timestamp);
        hash_update($ctx, $info);
        return hash_final($ctx);
    }
    protected function getTransport(){
        if(config('centrifuge.transport') == 'redis') {
            $client = Redis::connection(config('centrifuge.redisConnection'));
            return new CRedis($client, config('centrifuge.driver'));
        } else {
            $client = new Client(['base_uri' => config('centrifuge.baseUrl')]);
            return new CHttp($client);
        }
    }
    protected function send($method, $params){
        $transport = $this->getTransport();
        $response = $transport->send($method, $params);
        return $response;
    }
}