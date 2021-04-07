<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComDocumentService.php
                'MelisComDocumentService' => [
                    'getDocumentById' => [
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
                                    'name' => 'documentId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Document ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'documentId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'documentId' => [
                                'name' => 'documentId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Document ID must be an integer'
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
                    'getDocumentRelationByDocumentId' => [
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
                                    'name' => 'documentId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Document ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'documentId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'documentId' => [
                                'name' => 'documentId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Document ID must be an integer'
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
                    'getDocumentsByRelation' => [
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
                                    'name' => 'relationId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Relation ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'relationId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
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
                            'relationId' => [
                                'name' => 'relationId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Relation ID must be an integer'
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
                    'getDocumentsByRelationAndTypes' => [
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
                                    'name' => 'relationId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Relation ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'relationId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'typeCode1',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type Code 1'
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
                                    'name' => 'typeCode2[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type Code 2'
                                    ],
                                    'attributes' => [
                                        'id' => 'typeCode2',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'seperate values by comma',
                                        'data-type' => 'array'
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
                            'relationId' => [
                                'name' => 'relationId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Relation ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'typeCode1' => [
                                'name' => 'typeCode1',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'typeCode2' => [
                                'name' => 'typeCode2',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getDocDefaultImageFilePath' => [
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
                                    'name' => 'relationId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Relation ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'relationId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
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
                            'relationId' => [
                                'name' => 'relationId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Relation ID must be an integer'
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
                    'getFinalImageFilePath' => [
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
                                    'name' => 'relationId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Relation ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'relationId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'docType',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Document Type'
                                    ],
                                    'attributes' => [
                                        'id' => 'docType',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'seperate values by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'customDefaultImg',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Custom Default Image'
                                    ],
                                    'attributes' => [
                                        'id' => 'customDefaultImg',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
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
                            'relationId' => [
                                'name' => 'relationId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Relation ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'docType' => [
                                'name' => 'docType',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'customDefaultImg' => [
                                'name' => 'customDefaultImg',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getDocumentTypes' => [
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
                                    'name' => 'parentId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Parent ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'parentId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'parentId' => [
                                'name' => 'parentId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Parent ID must be an integer'
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