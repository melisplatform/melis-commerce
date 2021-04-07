<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComDuplicationService.php
                'MelisComDuplicationService' => [
                    'duplicateProduct' => [
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
                                    'name' => 'productId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Product ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'productId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
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
                            [
                                'spec' => [
                                    'name' => 'duplicateImages',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Duplicate Images'
                                    ],
                                    'attributes' => [
                                        'id' => 'duplicateImages',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'duplicateFiles',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Duplicate Files'
                                    ],
                                    'attributes' => [
                                        'id' => 'duplicateFiles',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'productId' => [
                                'name' => 'productId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Product ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
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
                            'duplicateImages' => [
                                'name' => 'duplicateImages',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Duplicate Images field must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'duplicateFiles' => [
                                'name' => 'duplicateFiles',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Duplicate Files field must be an integer'
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
                    'duplicateDocuments' => [
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
                                    'name' => 'docRelation',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Document Relation'
                                    ],
                                    'attributes' => [
                                        'id' => 'docRelation',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'docRelationId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Document Relation ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'docRelationId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'newDocRelationId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'New Document Relation ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'newDocRelationId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'duplicateImages',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Duplicate Images'
                                    ],
                                    'attributes' => [
                                        'id' => 'duplicateImages',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'duplicateFiles',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Duplicate Files'
                                    ],
                                    'attributes' => [
                                        'id' => 'duplicateFiles',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'docRelation' => [
                                'name' => 'docRelation',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'docRelationId' => [
                                'name' => 'docRelationId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Document Relation ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'newDocRelationId' => [
                                'name' => 'newDocRelationId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'New Document Relation ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'duplicateImages' => [
                                'name' => 'duplicateImages',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Duplicate Images field must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'duplicateFiles' => [
                                'name' => 'duplicateFiles',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Duplicate Files field must be an integer'
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