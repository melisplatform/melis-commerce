<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComLinksService.php
                'MelisComLinksService' => [
                    'getPageLink' => [
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
                                    'name' => 'typeLink',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type'
                                    ],
                                    'attributes' => [
                                        'id' => 'typeLink',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'product',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'id',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'ID'
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
                            [
                                'spec' => [
                                    'name' => 'langId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Language ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'langId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'absolute',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Absolute'
                                    ],
                                    'attributes' => [
                                        'id' => 'absolute',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'typeLink' => [
                                'name' => 'typeLink',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'id' => [
                                'name' => 'id',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'langId' => [
                                'name' => 'langId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'absolute' => [
                                'name' => 'absolute',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getPageIdAssociated' => [
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
                                    'name' => 'typeLink',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type'
                                    ],
                                    'attributes' => [
                                        'id' => 'typeLink',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'product',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'id',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'ID'
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
                            [
                                'spec' => [
                                    'name' => 'langId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Language ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'langId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'typeLink' => [
                                'name' => 'typeLink',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'id' => [
                                'name' => 'id',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'langId' => [
                                'name' => 'langId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID must be an integer'
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
                    'getPageLinkCategory' => [
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
                                        'label' => 'ID'
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
                            [
                                'spec' => [
                                    'name' => 'langId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Language ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'langId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'absolute',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Absolute'
                                    ],
                                    'attributes' => [
                                        'id' => 'absolute',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'onlyPageId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Only Page ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'onlyPageId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'id' => [
                                'name' => 'id',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'langId' => [
                                'name' => 'langId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'absolute' => [
                                'name' => 'absolute',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'onlyPageId' => [
                                'name' => 'onlyPageId',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getPageLinkProduct' => [
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
                                        'label' => 'ID'
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
                            [
                                'spec' => [
                                    'name' => 'langId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Language ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'langId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'absolute',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Absolute'
                                    ],
                                    'attributes' => [
                                        'id' => 'absolute',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'onlyPageId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Only Page ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'onlyPageId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'id' => [
                                'name' => 'id',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'langId' => [
                                'name' => 'langId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'absolute' => [
                                'name' => 'absolute',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'onlyPageId' => [
                                'name' => 'onlyPageId',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getPageLinkVariant' => [
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
                                        'label' => 'ID'
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
                            [
                                'spec' => [
                                    'name' => 'langId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Language ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'langId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'absolute',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Absolute'
                                    ],
                                    'attributes' => [
                                        'id' => 'absolute',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'onlyPageId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Only Page ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'onlyPageId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'id' => [
                                'name' => 'id',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'langId' => [
                                'name' => 'langId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'absolute' => [
                                'name' => 'absolute',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'onlyPageId' => [
                                'name' => 'onlyPageId',
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