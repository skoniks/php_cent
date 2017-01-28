# skoniks / php_cent
Centrifuge (Centrifugo) PHP Server API implementation for Laravel 5

## Installation
1. Run `composer require skoniks/php_cent`
2. Create `config/centrifuge`:
3. Add provider and alias in `config/app.php`

## Config example
```php
    <?php
    return [
        'driver'          => 'centrifuge',
        'transport'       => 'http', // http || redis
        'redisConnection' => 'centrifuge', // only for redis
        'baseUrl'         => 'http://localhost:8000/api', // api url
        'secret'          => 'abacaba', // you super secret key
    ];
```

## Provider additions
```php
    'providers' => [
        ...
        SKONIKS\Centrifuge\CentrifugeServiceProvider::class,
    ],
    
    'aliases' => [
        ...
        'Centrifuge' => SKONIKS\Centrifuge\Contracts\Centrifuge::class,
    ]
```
## Sending your command
...
