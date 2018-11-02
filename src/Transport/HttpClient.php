<?php
namespace SKONIKS\Centrifugo\Transport;

class HttpClient
{
    public function send($method, $params){
        $json = json_encode([
            'method' => $method,
            'params' => $params
        ]);
        $response = $this->curl('http://127.0.0.1:8880/api/', [
            'data' => $json,
            'sign' => $this->sign($json)
        ]);
        if($response == false) return false;
        return json_decode((string)$response)[0];
    }
    private function sign($json){
        $ctx = hash_init('sha256', HASH_HMAC, config('centrifugo.secret'));
        hash_update($ctx, $json);
        return hash_final($ctx);
    }
    private function curl($url, $fields){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}