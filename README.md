# skoniks / php_cent
Centrifuge (Centrifugo) [1.6+] PHP Server REDIS & HTTP API implementation for Laravel 5+

## Base Installation
1. Run `composer require skoniks/php_cent` & `composer update`
2. Create `config/centrifuge.php` as provided below
3. Add alias in `config/app.php` as provided below

## Config example `config/centrifuge.php`
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
        'Centrifuge'=> SKONIKS\Centrifuge\Centrifuge::class,
    ]
    
```

## Setting redis as transport
>Read notes about redis transport provided methods below
To set redis as transport :

1. Add your redis connections add your connection to `config/database.php` as provided below
2. Change `config/centrifuge.php` to redis settings

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

>Redis dont provides this methods:
* presence
* history

## [Module usage || sending your requests] example
```php
<?php
use Centrifuge;

class Controller
{
    public function your_func()
    {
        // declare centrifuge
        $Centrifuge = new Centrifuge();

        // generating token example
        $current_time = time();
        $steamid = '76561198073063637'
        $token = $Centrifuge->generateToken($steamid, $current_time, '');
        // publishing example
        $Centrifuge->publish("channel" , ["yout text or even what rou want"]);
        
        // each method returns its response; 
        // list of awailible methods: 
        $response = $Centrifuge->publish($channle, $messageData);
        $response = $Centrifuge->unsubscribe($channle, $userId);
        $response = $Centrifuge->disconnect($userId);
        $response = $Centrifuge->presence($channle);
        $response = $Centrifuge->history($channle);
        $response = $Centrifuge->generateToken($user, $timestamp, $info);
        
        // You can create a controller to bild your own interface;
    }
```
### For more informations go [here](https://fzambia.gitbooks.io/centrifugal/content/)
