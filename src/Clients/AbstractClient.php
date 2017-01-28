<?php

namespace SKONIKS\Centrifuge\Clients;

use SKONIKS\Centrifuge\Contracts\Centrifuge;

abstract class AbstractClient implements Centrifuge
{
    public function publish($channel, $data, $client = null)
    {
        $params = [
            'channel' => $channel,
            'data' => $data,
        ];
        $isClient = empty($client) ? [] : ['client' => $client];
        return $this->send('publish', array_merge($params, $isClient));
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
    abstract protected function send($method, $params);
}