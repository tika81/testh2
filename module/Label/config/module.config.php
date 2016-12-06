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
            'list_resource_class' => 'Label\Resource\ListLabelResource' 
        ],
        'Label\Controller\WriteLabelController' => [
            'type' => 'write',
            'write_resource_class' => 'Label\Resource\WriteLabelResource',
            
        ],
        'Label\Controller\DeleteLabelController' => [
            'type' => 'delete',
            'delete_resource_class' => 'Label\Resource\DeleteLabelResource',
        ],
    ],
    
    'resource_config' => [
        'Label\Resource\ListLabelResource' => [
            'repository_class' => 'Label\Model\LabelRepository',
            'entity_class'     => 'Label\Model\Label',
            'table_name' => 'ht_label',
            'type'       => 'list',
        ],
        'Label\Resource\WriteLabelResource' => [
            'command_class'    => 'Label\Model\LabelCommand',
            'entity_class'     => 'Label\Model\Label',
            'form_class'       => 'Label\Form\LabelForm',
            'table_name' => 'ht_label',
            'type'       => 'write',
            'dependencies' => [
                'Label\Resource\ListLabelResource'
            ],
        ],
        'Label\Resource\DeleteLabelResource' => [
            'command_class'    => 'Label\Model\LabelCommand',
            'entity_class'     => 'Label\Model\Label',
            'table_name' => 'ht_label',
            'type'       => 'delete',
            'dependencies' => [
                'Label\Resource\ListLabelResource'
            ],
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
//                    'ajaxLabels' => [
//                        'type' => Literal::class,
//                        'options' => [
//                            'route' => '/ajaxLabels',
//                            'defaults' => [
//                                'action' => 'ajaxLabels'
//                            ],
//                        ]
//                    ],
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

