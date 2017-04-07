<?php

namespace SKONIKS\Centrifugo\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SKONIKS\Centrifugo\Exceptions\HttpException;

class CHttp
{
    protected $client;
    function __construct(Client $client)
    {
        $this->client = $client;
    }
    public function send($method, $params)
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
    protected function generateSign($jsonData)
    {
        $ctx = hash_init('sha256', HASH_HMAC, config('centrifugo.secret'));
        hash_update($ctx, $jsonData);
        return hash_final($ctx);
    }
}