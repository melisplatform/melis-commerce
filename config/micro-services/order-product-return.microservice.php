<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComOrderProductReturnService.php
                'MelisComOrderProductReturnService' => [
                    'getOrderProductReturnList' => [
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
                                    'name' => 'orderId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Order ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'orderId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
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
                                        'placeholder' => 'obas_id ASC',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'orderKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Order Key',
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
                                        'label' => 'Search Value',
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
                        ],
                        'input_filter' => [
                            'orderId' => [
                                'name' => 'orderId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Order ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
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
                                'name' => 'orderKey',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'searchValue' => [
                                'name' => 'searchValue',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'searchKeys' => [
                                'name' => 'searchKeys',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];