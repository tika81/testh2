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
        'handlers' => [
            'default' => [
                'name' => 'Monolog\Handler\StreamHandler',
                'logger_name' => 'TestH2',
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
            ]
        ]
    ],
];