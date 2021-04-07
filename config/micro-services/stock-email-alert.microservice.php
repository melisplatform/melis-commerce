<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComStockEmailAlertService.php
                'MelisComStockEmailAlertService' => [
                    'getStockEmailRecipients' => [
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
                                    'name' => 'productIds',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Product IDs'
                                    ],
                                    'attributes' => [
                                        'id' => 'productIds',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'productId' => [
                                'name' => 'productIds',
                                'required' => true,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'checkStockLevelByOrderId' => [
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
                        ],
                    ],
                ],
            ],
        ],
    ],
];