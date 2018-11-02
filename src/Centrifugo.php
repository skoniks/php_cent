<?php
namespace SKONIKS\Centrifugo;
use SKONIKS\Centrifugo\Transport\RedisClient;
use SKONIKS\Centrifugo\Transport\HttpClient;

class Centrifugo {
    protected $redis_methods = [
        'publish', 'broadcast', 'unsubscribe', 'disconnect'
    ];
    public function publish($channel, $data){
        return $this->send('publish', [
            'channel' => $channel,
            'data' => $data,
        ]);
    }
    public function broadcast($channels, $data){
        return $this->send('broadcast', [
            'channels' => $channels,
            'data' => $data,
        ]);
    }
    public function unsubscribe($channel, $user){
        return $this->send('unsubscribe', [
            'channel' => $channel,
            'user' => $user,
        ]);
    }
    public function disconnect($user){
        return $this->send('disconnect', [
            'user' => $user,
        ]);
    }
    public function presence($channel){
        return $this->send('presence', [
            'channel' => $channel,
        ]);
    }
    public function history($channel){
        return $this->send('history', [
            'channel' => $channel,
        ]);
    }
    public function channels(){
        return $this->send('channels', []);
    }
    public function stats(){
        return $this->send('stats', []);
    }
    public function node(){
        return $this->send('node', []);
    }
    protected function transport($method){
        if(config('centrifugo.redis.enable') && in_array($method, $this->redis_methods))
            return new RedisClient();
        return new HttpClient();
    }
    protected function send($method, $params){
        return $this->transport($method)->send($method, $params);
    }
    public static function token($user_id, $timestamp, $info = ''){
        $ctx = hash_init('sha256', HASH_HMAC, config('centrifugo.secret'));
        hash_update($ctx, $user_id);
        hash_update($ctx, $timestamp);
        hash_update($ctx, $info);
        return hash_final($ctx);
    }
}