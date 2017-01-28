<?php

namespace SKONIKS\Centrifuge\Clients;

use SKONIKS\Centrifuge\Contracts\Centrifuge;

abstract class AbstractClient implements Centrifuge
{
    /**
     * @param $channel
     * @param $data
     * @param null|string $client
     * @return mixed
     */
    public function publish($channel, $data, $client = null)
    {
        $params = [
            'channel' => $channel,
            'data' => $data,
        ];
        $isClient = empty($client) ? [] : ['client' => $client];
        return $this->send('publish', array_merge($params, $isClient));
    }

    /**
     * @param string $channel
     * @param string $user
     * @return mixed
     */
    public function unsubscribe($channel, $user)
    {
        return $this->send('unsubscribe', [
            'channel' => $channel,
            'user' => $user,
        ]);
    }

    /**
     * @param string $user
     * @return mixed
     */
    public function disconnect($user)
    {
        return $this->send('disconnect', [
            'user' => $user,
        ]);
    }

    /**
     * @param string $channel
     * @return mixed
     */
    public function presence($channel)
    {
        return $this->send('presence', [
            'channel' => $channel,
        ]);
    }

    /**
     * @param string $channel
     * @return mixed
     */
    public function history($channel)
    {
        return $this->send('history', [
            'channel' => $channel,
        ]);
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    abstract protected function send($method, $params);
}