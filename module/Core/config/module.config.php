<?php
namespace Core;

use Core\Factory\ListControllerFactory;
use Core\Factory\WriteControllerFactory;
use Core\Factory\DeleteControllerFactory;
use Core\Resource\ListResourceFactory;
use Core\Resource\WriteResourceFactory;
use Core\Resource\DeleteResourceFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
            ListResourceFactory::class,
            WriteResourceFactory::class,
            DeleteResourceFactory::class,
        ],
        'factories' => [
            Logger\MonologLogger::class => Logger\LoggerFactory::class,
        ],
    ],
    'controllers' => [
        'abstract_factories' => [
            ListControllerFactory::class,
            WriteControllerFactory::class,
            DeleteControllerFactory::class,
        ]
    ],
];