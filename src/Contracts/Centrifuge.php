<?php

namespace SKONIKS\Centrifuge\Contracts;

interface Centrifuge
{

    /**
     * @param $channel
     * @param $data
     * @param null|string $client
     * @return mixed
     */
    public function publish($channel, $data, $client = null);

    /**
     * @param string $channel
     * @param string $user
     * @return mixed
     */
    public function unsubscribe($channel, $user);

    /**
     * @param string $user
     * @return mixed
     */
    public function disconnect($user);

    /**
     * @param string $channel
     * @return mixed
     */
    public function presence($channel);

    /**
     * @param string $channel
     * @return mixed
     */
    public function history($channel);
    
    public function generateToken($userId, $timestamp, $info = '')
    {
        $ctx = hash_init('sha256', HASH_HMAC, config('centrifuge.secret'));
        hash_update($ctx, $userId);
        hash_update($ctx, $timestamp);
        hash_update($ctx, $info);

        return hash_final($ctx);
    }
    
}