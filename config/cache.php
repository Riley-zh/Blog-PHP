<?php

return [
    'default' => $_ENV['CACHE_DRIVER'] ?? 'file',
    
    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => dirname(__DIR__) . '/storage/cache',
        ],
        
        'array' => [
            'driver' => 'array',
        ],
    ],
    
    'prefix' => $_ENV['CACHE_PREFIX'] ?? 'modernphpblog',
    
    'path' => $_ENV['CACHE_PATH'] ?? dirname(__DIR__) . '/storage/cache',
    'ttl' => $_ENV['CACHE_TTL'] ?? 3600,
];