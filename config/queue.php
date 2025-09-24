<?php

return [
    'default' => $_ENV['QUEUE_CONNECTION'] ?? 'file',
    
    'connections' => [
        'file' => [
            'driver' => 'file',
            'path' => dirname(__DIR__) . '/storage/queues',
        ],
    ],
    
    'queue' => $_ENV['QUEUE_NAME'] ?? 'default',
    'path' => $_ENV['QUEUE_PATH'] ?? dirname(__DIR__) . '/storage/queues',
];