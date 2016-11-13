<?php
namespace Core;

return [
    'service_manager' => [
        'factories' => [
            Logger\Logger::class => Logger\LoggerFactory::class,
        ],
    ],
];