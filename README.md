# skoniks / php_cent
Centrifugo (Centrifuge) [1.6+] PHP Server REDIS & HTTP API implementation for Laravel 5+

## Base Installation
1. Run `composer require skoniks/php_cent` & `composer update`
2. Create `config/centrifugo.php` as provided below
3. Add alias in `config/app.php` as provided below

## Config example `config/centrifugo.php`
```php
<?php
    return [
        'driver'          => 'centrifugo', // redis channel name as provided in cent. conf ($driver.".api")
        'transport'       => 'http', // http || redis connection, check more information below
        'redisConnection' => 'centrifugo', // only for redis, name of connection more information below
        'baseUrl'         => 'http://localhost:8000/api/', // full api url
        'secret'          => 'shlasahaposaheisasalssushku', // you super secret key
    ];

```

## Alias additions `config/app.php`
```php
    'aliases' => [
        ...
        'Centrifugo'=> SKONIKS\Centrifugo\Centrifugo::class,
    ]
    
```

## Setting redis as transport
>Read notes about redis transport provided methods below
To set redis as transport :

1. Add your redis connections add your connection to `config/database.php` as provided below
2. Change `config/centrifugo.php` to redis settings

## Adding redis connection `config/database.php`
```php
 'redis' => [
        ...
        'centrifugo' => [
            'scheme' => 'tcp',      // unix
            'host' => '127.0.0.1',  // null for unix
            'path' => '',           // or unix path
            'password' => '',
            'port' => 6379,         // null for unix
            'database' => 1,        // cent. db like in cent. configs
        ],
    ],
```


## Redis supported transport
>Make shure that **HTTP connection must work independently from redis connection**.
>It is because redis transport provides only this methods:
* 'publish' 
* 'broadcast' 
* 'unsubscribe' 
* 'disconnect'

## [Module usage || sending your requests] example
```php
<?php
use Centrifugo;

class Controller
{
    public function your_func()
    {
        // declare Centrifugo
        $Centrifugo = new Centrifugo();

        // generating token example
        $current_time = time();
        $steamid = '76561198073063637'
        $token = $Centrifugo->generateToken($steamid, $current_time, '');
        // publishing example
        $Centrifugo->publish("channel" , ["yout text or even what rou want"]);
        
        // each method returns its response; 
        // list of awailible methods: 
        $response = $Centrifugo->publish($channle, $messageData);
        $response = $Centrifugo->unsubscribe($channle, $userId);
        $response = $Centrifugo->disconnect($userId);
        $response = $Centrifugo->presence($channle);
        $response = $Centrifugo->history($channle);
        $response = $Centrifugo->generateToken($user, $timestamp, $info);
        
        // You can create a controller to bild your own interface;
    }
```
### For more informations go [here](https://fzambia.gitbooks.io/centrifugal/content/)
