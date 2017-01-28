<?php

namespace SKONIKS\Centrifuge\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SKONIKS\Centrifuge\Exceptions\HttpException;

class Http extends AbstractClient
{
    protected $client;

    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $method
     * @param $params
     * @return mixed
     * @throws \SKONIKS\Centrifuge\Exceptions\HttpException
     */
    protected function send($method, $params)
    {
        $json = json_encode([
            'method' => $method,
            'params' => $params
        ]);

        try {
            $response = $this->client->post('', [
                'form_params' => [
                    'data' => $json,
                    'sign' => $this->generateSign($json)
                ],
            ]);
            $finally = json_decode((string) $response->getBody())[0];
        } catch (ClientException $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        return $finally;
    }

    /**
     * @param string $jsonData
     * @return string
     */
    protected function generateSign($jsonData)
    {
        $ctx = hash_init('sha256', HASH_HMAC, config('centrifuge.secret'));
        hash_update($ctx, $jsonData);
        return hash_final($ctx);
    }
}