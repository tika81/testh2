<?php
namespace Core;

use Core\Factory\ListControllerFactory;
use Core\Factory\WriteControllerFactory;
use Core\Factory\DeleteControllerFactory;
use Core\Factory\RepositoryFactory;
use Core\Factory\CommandFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
            RepositoryFactory::class,
            CommandFactory::class,
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