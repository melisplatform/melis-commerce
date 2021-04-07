<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComCategoryService.php
                'MelisComCategoryService' => [
                    'getCategoryListById' => [
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
                                        'placeholder' => '1'
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
                            ]
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
                    'getCategoryListByIdRecursive' => [
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
                                    'name' => 'fatherId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Father ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'fatherId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ]
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
                            'fatherId' => [
                                'name' => 'fatherId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Father ID must be an integer'
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
                    'getCategoryById' => [
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getCategoryNameById' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                        ],
                    ],
                    'getCategoryProductsById' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getCategoriesProductsByIds' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                                    'name' => 'column',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Column',
                                    ],
                                    'attributes' => [
                                        'id' => 'column',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'cat_id',
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
                                    'name' => 'includeSubCategories',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Include Sub-categories',
                                    ],
                                    'attributes' => [
                                        'id' => 'includeSubCategories',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'bool'
                                    ],
                                ],
                            ]
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
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
                            'order' => [
                                'name' => 'order',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                            'includeSubCategories' => [
                                'name' => 'includeSubCategories',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getCategoriesByIds' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                                    'name' => 'column',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Column',
                                    ],
                                    'attributes' => [
                                        'id' => 'column',
                                        'value' => '',
                                        'css' => '',
                                        'placeholder' => 'cat_id',
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
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
                            'order' => [
                                'name' => 'order',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getCategoryCountriesById' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                                        'placeholder' => '0',
                                        'data-type' => 'bool'
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
                        ],
                    ],
                    'getCategoryTranslationById' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getAllSubCategoryIdById' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                        ],
                    ],
                    'getSubCategoryIdByIdRecursive' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                                    'name' => 'fatherId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Father ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'fatherId',
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
                            'fatherId' => [
                                'name' => 'fatherId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Father ID must be an integer'
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
                    'getCategorySeoById' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                        ],
                    ],
                    'getCategoryTreeview' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'fatherId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Father ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'fatherId',
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
                            'fatherId' => [
                                'name' => 'fatherId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Father ID must be an integer'
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ]
                    ],
                    'reorderProductCategory' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                        ],
                        'input_filters' => [
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
                        ]
                    ],
                    'getCategoryBreadCrumb' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
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
                        ],
                        'filter_input' => [
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
                        ],
                    ],
                    'getChildrenByLangId' => [
                        'attributes' => [
                            'name' => 'microservice_form',
                            'id' => 'microservice_form',
                            'method' => 'POST',
                            'action' => $_SERVER['REQUEST_URI'],
                        ],
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'fatherId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Father ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'fatherId',
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
                            'fatherId' => [
                                'name' => 'fatherId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Father ID must be an integer'
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
                            'onlyValid' => [
                                'name' => 'onlyValid',
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
                        ],
                    ],
                ],
            ],
        ],
    ],
];