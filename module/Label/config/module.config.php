<?php
namespace Label;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    
    'controller_config' => [
        'Label\Controller\ListLabelController' => [
            'type' => 'list',
            'repository_class' => 'Label\Model\LabelRepository',
        ],
        'Label\Controller\WriteLabelController' => [
            'type' => 'write',
            'repository_class' => 'Label\Model\LabelRepository',
            'command_class'    => 'Label\Model\LabelCommand',
            'form_class'       => 'Label\Form\LabelForm',
        ],
        'Label\Controller\DeleteLabelController' => [
            'type' => 'delete',
            'repository_class' => 'Label\Model\LabelRepository',
            'command_class'    => 'Label\Model\LabelCommand',
        ],
    ],
    
    'mapper_config' => [
        'Label\Model\LabelRepository' => [
            'table_name' => 'ht_label',
            'entity_class' => 'Label\Model\Label',
            'type' => 'repository',
        ],
        'Label\Model\LabelCommand' => [
            'table_name' => 'ht_label',
            'entity_class' => 'Label\Model\Label',
            'type' => 'command',
        ],
    ],
    
    'router' => [
        'routes' => [
            'label' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/label',
                    'defaults' => [
                        'controller' => Controller\ListLabelController::class,
                        'action'     => 'index'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'detail' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/:id',
                            'defaults' => [
                                'action' => 'detail'
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*'
                            ]
                        ]
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'controller' => 
                                    Controller\WriteLabelController::class,
                                'action' => 'add',
                            ]
                        ]
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/edit/:id',
                            'defaults' => [
                                'controller' => 
                                    Controller\WriteLabelController::class,
                                'action'     => 'edit',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/:id',
                            'defaults' => [
                                'controller' => 
                                    Controller\DeleteLabelController::class,
                                'action' => 'delete',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ]
                    ],
                ],
            ],
            
            
//            'about' => [
//                'type' => Literal::class,
//                'options' => [
//                    'route' => '/about-me',
//                    'defaults' => [
//                        'controller' => 'AboutMeController',
//                        'action'     => 'aboutme'
//                    ],
//                ],
//            ],
        ],
    ],
];

