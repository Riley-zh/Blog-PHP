<?php

return [
    'default' => $_ENV['LOG_CHANNEL'] ?? 'stack',
    
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],
        
        'single' => [
            'driver' => 'single',
            'path' => dirname(__DIR__) . '/storage/logs/app.log',
            'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
        ],
        
        'daily' => [
            'driver' => 'daily',
            'path' => dirname(__DIR__) . '/storage/logs/app.log',
            'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
            'days' => 14,
        ],
    ],
    
    'path' => $_ENV['LOG_PATH'] ?? dirname(__DIR__) . '/storage/logs/app.log',
    'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
];