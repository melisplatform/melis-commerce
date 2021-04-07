<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComSeoService.php
                'MelisComSeoService' => [
                    'getSeoByType' => [
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
                                    'name' => 'type',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type',
                                    ],
                                    'attributes' => [
                                        'id' => 'type',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'typeId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'typeId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'type' => [
                                'name' => 'type',
                                'required' => true,
                                'validators' => [

                                ],
                            ],
                            'typeId' => [
                                'name' => 'typeId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Type ID must be an integer'
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