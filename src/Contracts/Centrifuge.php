<?php

namespace SKONIKS\Centrifuge\Contracts;

interface Centrifuge
{
    public function publish($channel, $data, $client = null);
    public function unsubscribe($channel, $user);
    public function disconnect($user);
    public function presence($channel);
    public function history($channel);
    public function generateToken($userId, $timestamp, $info = '');
}