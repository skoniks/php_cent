# skoniks / php_cent
Centrifugo (Centrifuge) [1.0+] PHP Server REDIS & HTTP API implementation for Laravel 5+
Incompatible with Centrifugo [2.0+], will be updated later!
## Base Installation
1. Run `composer require skoniks/php_cent` & `composer update`
2. Create `config/centrifugo.php` as provided below
3. Add alias in `config/app.php` as provided below

>For laravel 5.5+ use version >= "2.5"

## Config example `config/centrifugo.php`
[centrifugo.php](https://github.com/skoniks/php_cent/blob/master/centrifugo.php)

## Alias additions `config/app.php`
```php
    'aliases' => [
        ...
        'Centrifugo'=> SKONIKS\Centrifugo\Centrifugo::class,
    ]
    
```

## Setting redis as transport
1. Add your redis connection to `config/database.php`
2. Change `config/centrifugo.php` to redis settings

## Adding redis connection `config/database.php`
```php
...
    'redis' => [
        ...
        'centrifugo' => [
            'host' => '127.0.0.1',
            'password' => '',
            'port' => 6379,
            'database' => 1,
        ],
    ],
...
```

## Redis supported transport
>Make shure that **HTTP connection must work independently from redis connection**.
>It is because redis transport provides only this methods:
* 'publish' 
* 'broadcast' 
* 'unsubscribe' 
* 'disconnect'

>Redis dont provide this methods:
* presence
* history
* channels
* stats
* node

## [Module usage || sending your requests] example
```php
<?php
use Centrifugo;
class Controller {
    public function _function(){
        // declare Centrifugo
        $centrifugo = new Centrifugo();

        // generating token example
        $current_time = time();
        $user_id = '1234567890';
        $token = Centrifugo::token($user_id, $current_time, 'custom info');
                                
        // publishing example
        $centrifugo->publish("channel" , ["custom data"]);
        
        // list of awailible methods: 
        $response = $centrifugo->publish($channel, $data);
        $response = $centrifugo->unsubscribe($channel, $user_id);
        $response = $centrifugo->disconnect($user_id);
        $response = $centrifugo->presence($channel);
        $response = $centrifugo->history($channel);
        $response = $centrifugo->channels();
        $response = $centrifugo->stats();
        $response = $centrifugo->node();
        $token = Centrifugo::token($user_id, $timestamp, $info);
                                
        // $response == false | when error
    }
```
### For more information go [here](https://fzambia.gitbooks.io/centrifugal/content/)
