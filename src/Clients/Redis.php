<?php

namespace SKONIKS\Centrifuge\Clients;

use SKONIKS\Centrifuge\Exceptions\RedisException;
use Predis\ClientInterface;
use Predis\PredisException;

class Redis extends AbstractClient
{
    /**
     * @var \Predis\ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $driver;

    /**
     * Create a new broadcaster instance.
     *
     * @param \Predis\ClientInterface $client
     * @param string $driver Can be centrifuge or centrifugo
     */
    function __construct(ClientInterface $client, $driver = 'centrifuge')
    {
        $this->client = $client;
        $this->driver = $driver;
    }

    /**
     * @param string $method
     * @param array $params
     * @return int
     * @throws \SKONIKS\Centrifuge\Exceptions\RedisException
     */
    protected function send($method, $params)
    {
        $json = json_encode(['data' => ['method' => $method,'params' => $params]]);
        try {
            $result = $this->client->rpush($this->driver . '.api', $json);
        } catch (PredisException $e) {
            throw new RedisException;
        }
        return $result;
    }

}