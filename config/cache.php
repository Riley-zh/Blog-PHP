<?php

return [
    'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',

    'file' => [
        'path' => $_ENV['CACHE_PATH'] ?? dirname(__DIR__) . '/storage/cache',
        'ttl' => (int) ($_ENV['CACHE_TTL'] ?? 3600),
    ],

    'redis' => [
        'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
        'port' => (int) ($_ENV['REDIS_PORT'] ?? 6379),
        'password' => $_ENV['REDIS_PASSWORD'] ?? null,
        'timeout' => 1.5,
    ],

    'prefix' => $_ENV['CACHE_PREFIX'] ?? 'modernphpblog',
];