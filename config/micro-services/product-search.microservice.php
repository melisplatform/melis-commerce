<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComProductSearchService.php
                'MelisComProductSearchService' => [
                    'searchProductByTextFields' => [
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
                            [
                                'spec' => [
                                    'name' => 'fieldsTypeCodes[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Field Type Codes',
                                    ],
                                    'attributes' => [
                                        'id' => 'fieldsTypeCodes',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
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
                                    'name' => 'categoryId[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Category ID',
                                    ],
                                    'attributes' => [
                                        'id' => 'categoryId',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
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
                                        'placeholder' => '',
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
                        ],
                        'input_filter' => [
                            'search' => [
                                'name' => 'search',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'fieldsTypeCodes' => [
                                'name' => 'fieldsTypeCodes',
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
                            'categoryId' => [
                                'name' => 'categoryId',
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
                        ],
                    ],
                    'searchProductByAttributeValuesAndPriceRange' => [
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
                                    'name' => 'attributeValuesIds[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Attribute Value IDs',
                                    ],
                                    'attributes' => [
                                        'id' => 'attributeValuesIds',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'priceMin',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Minimum Price'
                                    ],
                                    'attributes' => [
                                        'id' => 'priceMin',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'priceMax',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Maximum Price'
                                    ],
                                    'attributes' => [
                                        'id' => 'priceMax',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
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
                                    'name' => 'categoryId[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Category ID',
                                    ],
                                    'attributes' => [
                                        'id' => 'categoryId',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
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
                                        'placeholder' => '',
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
                        ],
                        'input_filter' => [
                            'attributeValuesIds' => [
                                'name' => 'attributeValuesIds',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'priceMin' => [
                                'name' => 'priceMin',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Minimum Price must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'priceMax' => [
                                'name' => 'priceMax',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Maxmimum Price must be an integer'
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
                            'categoryId' => [
                                'name' => 'categoryId',
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
                        ],
                    ],
                    'searchProductFull' => [
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
                            [
                                'spec' => [
                                    'name' => 'fieldsTypeCodes[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Field Type Codes',
                                    ],
                                    'attributes' => [
                                        'id' => 'fieldsTypeCodes',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'attributeValuesIds[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Attribute Value IDs',
                                    ],
                                    'attributes' => [
                                        'id' => 'attributeValuesIds',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'priceMin',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Minimum Price'
                                    ],
                                    'attributes' => [
                                        'id' => 'priceMin',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'priceMax',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Maximum Price'
                                    ],
                                    'attributes' => [
                                        'id' => 'priceMax',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
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
                                    'name' => 'categoryId[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Category IDs',
                                    ],
                                    'attributes' => [
                                        'id' => 'categoryId',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
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
                                        'placeholder' => '',
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
                                        'placeholder' => '',
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
                                    'name' => 'sort',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Sort'
                                    ],
                                    'attributes' => [
                                        'id' => 'sort',
                                        'value' => '',
                                        'class' => '',
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
                        ],
                        'input_filter' => [
                            'search' => [
                                'name' => 'search',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'fieldsTypeCodes' => [
                                'name' => 'fieldsTypeCodes',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'attributeValuesIds' => [
                                'name' => 'attributeValuesIds',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'priceMin' => [
                                'name' => 'priceMin',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Minimum Price must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'priceMax' => [
                                'name' => 'priceMax',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Maxmimum Price must be an integer'
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
                            'categoryId' => [
                                'name' => 'categoryId',
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
                            'sort' => [
                                'name' => 'sort',
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
                    'getProductByCategory' => [
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
                                    'name' => 'fieldsTypeCodes[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Field Type Codes',
                                    ],
                                    'attributes' => [
                                        'id' => 'fieldsTypeCodes',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'docTypes[]',
                                    'type' => 'text',
                                    'options' => [
                                        'label' => 'Document Types'
                                    ],
                                    'attributes' => [
                                        'id' => 'docTypes',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'array'
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
                            'fieldsTypeCodes' => [
                                'name' => 'fieldsTypeCodes',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'docTypes' => [
                                'name' => 'docTypes',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getPriceByColumn' => [
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
                                    'name' => 'column',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Column',
                                    ],
                                    'attributes' => [
                                        'id' => 'column',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'price_net',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'categoryId[]',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Category ID',
                                    ],
                                    'attributes' => [
                                        'id' => 'categoryId',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'separate by comma',
                                        'data-type' => 'array'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'order' => [
                                'name' => 'order',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'column' => [
                                'name' => 'column',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'categoryId' => [
                                'name' => 'categoryId',
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