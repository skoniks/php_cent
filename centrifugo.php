<?php
return [
    'redis' => [
        'enable' => false, // Use REDIS for redis-available methods (by default uses http)
        'driver' => 'centrifugo' // REDIS channel name from Centrifugo config ($driver.".api")
        'connection' => 'centrifugo' // Name of REDIS connection in "config/database.php"
    ],
    'url_api' => env('C_URL_API', 'http://localhost:8000/api/'), // HTTP API url for Centrifugo
    'secret' => env('C_SECRET', null), // SUPER SECRET API KEY
];