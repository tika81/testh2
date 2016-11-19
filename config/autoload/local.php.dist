<?php
return [
    'view_manager' => [
        'display_exceptions' => true,
    ],
    'db' => [
        'dsn' => 'mysql:dbname=horisen_test;host=localhost',
        'username' => 'root',
        'password' => 'root',
    ],
    
    'logger' => [
        'handler' => 'Monolog\Handler\StreamHandler',
        'name' => 'TestH2',
        'args' => [
            'path' => 'data/log/app.log',
            'level' => \Monolog\Logger::DEBUG,
            'bubble' => true
        ],
        'formatter' => [
            'name' => 'Monolog\Formatter\LogstashFormatter',
            'args' => [
                'application' => 'TestH2',
            ],
        ]
    ],
];