<?php

namespace SKONIKS\Centrifugo\Transport;

use SKONIKS\Centrifugo\Exceptions\RedisException;
use Predis\ClientInterface;
use Predis\PredisException;

class CRedis
{
    protected $client;
    protected $driver;
    function __construct(ClientInterface $client, $driver = 'centrifugo')
    {
        $this->client = $client;
        $this->driver = $driver;
    }
    public function send($method, $params)
    {
        $json = json_encode(['method' => $method,'params' => $params]);
        try {
            $result = $this->client->rpush($this->driver . '.api', $json);
        } catch (PredisException $e) {
            throw new RedisException;
        }
        return $result;
    }
}