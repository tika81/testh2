<?php
namespace Core;

return [
    'service_manager' => [
        'factories' => [
            Logger\MonologLogger::class => Logger\LoggerFactory::class,
        ],
    ],
];