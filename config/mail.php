<?php

return [
    'default' => $_ENV['MAIL_MAILER'] ?? 'mail',
    
    'mailers' => [
        'mail' => [
            'transport' => 'mail',
        ],
        
        'smtp' => [
            'transport' => 'smtp',
            'host' => $_ENV['MAIL_HOST'] ?? 'localhost',
            'port' => $_ENV['MAIL_PORT'] ?? 25,
            'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? null,
            'username' => $_ENV['MAIL_USERNAME'] ?? null,
            'password' => $_ENV['MAIL_PASSWORD'] ?? null,
        ],
    ],
    
    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'hello@example.com',
        'name' => $_ENV['MAIL_FROM_NAME'] ?? 'Example',
    ],
];