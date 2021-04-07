<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComClientGroupsService.php
                'MelisComClientGroupsService' => [
                    'getClientsGroupList' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
                        'hydrator' => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'start',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Start'
                                    ],
                                    'attributes' => [
                                        'id' => 'start',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'limit',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Limit'
                                    ],
                                    'attributes' => [
                                        'id' => 'limit',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'order',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Order',
                                    ],
                                    'attributes' => [
                                        'id' => 'order',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'ASC',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'orderKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Order column',
                                    ],
                                    'attributes' => [
                                        'id' => 'orderKey',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'searchValue',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Search',
                                    ],
                                    'attributes' => [
                                        'id' => 'searchValue',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'searchKeys[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Search Keys',
                                    ],
                                    'attributes' => [
                                        'id' => 'searchKeys',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'seperate values by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'status',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Status'
                                    ],
                                    'attributes' => [
                                        'id' => 'status',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'start' => [
                                'name' => 'start',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Start must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'limit' => [
                                'name' => 'limit',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Limit must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'order' => [
                                'name' => 'order',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'orderKey' => [
                                'name' => 'orderColumn',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'searchValue' => [
                                'name' => 'search',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'searchKeys' => [
                                'name' => 'search',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'status' => [
                                'name' => 'status',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Status must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                        ],
                    ],
                    'getClientsGroupById' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
                        'hydrator' => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'id',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Group ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'id',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'id' => [
                                'name' => 'id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client Group ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];