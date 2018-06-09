<?php
namespace SKONIKS\Centrifugo;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;
use SKONIKS\Centrifugo\Transport\CHttp;
use SKONIKS\Centrifugo\Transport\CRedis;

class Centrifugo
{
    protected $rmethods = ['publish', 'broadcast', 'unsubscribe', 'disconnect'];
    public function publish($channel, $data)
    {
        return $this->send('publish', [
            'channel' => $channel,
            'data' => $data,
        ]);
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
        $ctx = hash_init('sha256', HASH_HMAC, config('centrifugo.secret'));
        hash_update($ctx, $userId);
        hash_update($ctx, $timestamp);
        hash_update($ctx, $info);
        return hash_final($ctx);
    }
    protected function getTransport($method){
        if(config('centrifugo.transport') == 'redis' && in_array($method, $this->rmethods)) {
            $client = Redis::connection(config('centrifugo.redisConnection'))->client();
            return new CRedis($client, config('centrifugo.driver'));
        } else {
            $client = new Client(['base_uri' => config('centrifugo.baseUrl')]);
            return new CHttp($client);
        }
    }
    protected function send($method, $params){
        $transport = $this->getTransport($method);
        $response = $transport->send($method, $params);
        return $response;
    }
}