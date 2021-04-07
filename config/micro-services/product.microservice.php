<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComProductService.php
                'MelisComProductService' => [
                    'getProductList' => [
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
                                    'name' => 'categoryIds[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Category Ids',
                                    ],
                                    'attributes' => [
                                        'id' => 'categoryIds',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'countryId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Country ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'countryId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'onlyValid',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Only Valid'
                                    ],
                                    'attributes' => [
                                        'id' => 'onlyValid',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'bool'
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
                                    'name' => 'orderColumn',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Order column',
                                    ],
                                    'attributes' => [
                                        'id' => 'orderColumn',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'prd_id',
                                        'data-type' => 'string'
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
                                    'name' => 'search',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Search',
                                    ],
                                    'attributes' => [
                                        'id' => 'search',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
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
                            'categoryIds' => [
                                'name' => 'categoryIds',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'countryId' => [
                                'name' => 'countryId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Country ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'onlyValid' => [
                                'name' => 'onlyValid',
                                'required' => false,
                                'validators' => [

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
                            'orderColumn' => [
                                'name' => 'orderColumn',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'order' => [
                                'name' => 'order',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'search' => [
                                'name' => 'search',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ]
                    ],
                    'getAssocProducts' => [
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
                        ]
                    ],
                    'getProductAssociation' => [
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
                        ]
                    ],
                    'getProductById' => [
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
                                    'name' => 'countryId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Country ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'countryId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'groupId',
                                    'type' => 'text',
                                    'options' => [
                                        'label' => 'Group ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'groupId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '-1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'docType',
                                    'type' => 'text',
                                    'options' => [
                                        'label' => 'Document Type'
                                    ],
                                    'attributes' => [
                                        'id' => 'docType',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'IMG, FILE',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'docSubType[]',
                                    'type' => 'text',
                                    'options' => [
                                        'label' => 'Document Sub Type'
                                    ],
                                    'attributes' => [
                                        'id' => 'docSubType',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => 'DEFAULT, SMALL, LARGE, MEDIUM',
                                        'data-type' => 'array'
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
                            'countryId' => [
                                'name' => 'countryId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Country ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'groupId' => [
                                'name' => 'groupId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Group ID must be an integer'
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
                            'docSubType' => [
                                'name' => 'docSubType',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getProductAttributesById' => [
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
                    'getProductTextsById' => [
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
                                    'name' => 'productTextCode',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Product Text Code'
                                    ],
                                    'attributes' => [
                                        'id' => 'productTextCode',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
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
                            'productTextCode' => [
                                'name' => 'productTextCode',
                                'required' => false,
                                'validators' => [

                                ]
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
                    'getProductPricesById' => [
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
                                    'name' => 'countryId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Country ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'countryId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'groupId',
                                    'type' => 'text',
                                    'options' => [
                                        'label' => 'Group ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'groupId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '-1',
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
                            'countryId' => [
                                'name' => 'countryId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Country ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'groupId' => [
                                'name' => 'groupId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Group ID must be an integer'
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
                    'getProductCategories' => [
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
                        ],
                    ],
                    'getProductName' => [
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
                    'getProductTextByCode' => [
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
                                    'name' => 'typeCode',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type Code'
                                    ],
                                    'attributes' => [
                                        'id' => 'typeCode',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
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
                            'typeCode' => [
                                'name' => 'typeCode',
                                'required' => false,
                                'validators' => [

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
                    'getProductsByCategoryId' => [
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
                                    'name' => 'categoryId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Category ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'categoryId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'onlyValid',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Only Valid'
                                    ],
                                    'attributes' => [
                                        'id' => 'onlyValid',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'bool'
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
                        ],
                        'input_filter' => [
                            'categoryId' => [
                                'name' => 'categoryId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Category ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'onlyValid' => [
                                'name' => 'onlyValid',
                                'required' => false,
                                'validators' => [

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
                            'order' => [
                                'name' => 'order',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getProductVariants' => [
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
                                    'name' => 'onlyValid',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Only Valid'
                                    ],
                                    'attributes' => [
                                        'id' => 'onlyValid',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'bool'
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'formatPrice' => [
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
                                    'name' => 'price',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Price'
                                    ],
                                    'attributes' => [
                                        'id' => 'price',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '123',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'overrideLocale',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Override Locale'
                                    ],
                                    'attributes' => [
                                        'id' => 'overrideLocale',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'price' => [
                                'name' => 'price',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Price must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'overrideLocale' => [
                                'name' => 'overrideLocale',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ]
                    ],
                    'getProductVariantPriceById' => [
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
                                    'name' => 'priceColumn',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Price Column',
                                    ],
                                    'attributes' => [
                                        'id' => 'priceColumn',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'price_net',
                                        'data-type' => 'string'
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
                            'order' => [
                                'name' => 'order',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'priceColumn' => [
                                'name' => 'priceColumn',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getProductTitleAndSeoById' => [
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
                    'getProductBasicDetails' => [
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
                                    'name' => 'countryId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Country ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'countryId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'groupId',
                                    'type' => 'text',
                                    'options' => [
                                        'label' => 'Group ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'groupId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '-1',
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
                            'countryId' => [
                                'name' => 'countryId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Country ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'groupId' => [
                                'name' => 'groupId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Group ID must be an integer'
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
                        ]
                    ],
                    'getMaximumMinimumPrice' => [
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
                                    'name' => 'priceColumn',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Price Column',
                                    ],
                                    'attributes' => [
                                        'id' => 'priceColumn',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'price_net',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'from',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'From',
                                    ],
                                    'attributes' => [
                                        'id' => 'from',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'product',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'type' => [
                                'name' => 'type',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'priceColumn' => [
                                'name' => 'priceColumn',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'from' => [
                                'name' => 'from',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getProductPageAssociationsByProductId' => [
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
                        ],
                    ],
                ],
            ],
        ],
    ],
];