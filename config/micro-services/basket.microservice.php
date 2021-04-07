<?php

return [
    'plugins' => [
        'microservice' => [
            // Module Name
            'MelisCommerce' => [
                // MelisComBasketService.php
                'MelisComBasketService' => [
                    'getBasketItemById' => [
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
                                    'name' => 'basketId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Basket ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'basketId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'type',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Type'
                                    ],
                                    'attributes' => [
                                        'id' => 'type',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'basketId' => [
                                'name' => 'basketId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Basket ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'type' => [
                                'name' => 'type',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'getBasket' => [
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
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
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
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientKey' => [
                                'name' => 'clientKey',
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
                    'getPersistentBasket' => [
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
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
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
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
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
                    'getAnonymousBasket' => [
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
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
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
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => true,
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
                    'addVariantToBasket' => [
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
                                    'name' => 'variantId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Variant ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'variantId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'quantity',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Quantity'
                                    ],
                                    'attributes' => [
                                        'id' => 'quantity',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'variantId' => [
                                'name' => 'variantId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Variant ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'quantity' => [
                                'name' => 'quantity',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Quantity must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'addVariantToPersistentBasket' => [
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
                                    'name' => 'variantId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Variant ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'variantId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'quantity',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Quantity'
                                    ],
                                    'attributes' => [
                                        'id' => 'quantity',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'variantId' => [
                                'name' => 'variantId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Variant ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'quantity' => [
                                'name' => 'quantity',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Quantity must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
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
                    'addVariantToAnonymousBasket' => [
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
                                    'name' => 'variantId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Variant ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'variantId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'quantity',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Quantity'
                                    ],
                                    'attributes' => [
                                        'id' => 'quantity',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'variantId' => [
                                'name' => 'variantId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Variant ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'quantity' => [
                                'name' => 'quantity',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Quantity must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'removeVariantFromBasket' => [
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
                                    'name' => 'variantId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Variant ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'variantId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'quantity',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Quantity'
                                    ],
                                    'attributes' => [
                                        'id' => 'quantity',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'variantId' => [
                                'name' => 'variantId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Variant ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'quantity' => [
                                'name' => 'quantity',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Quantity must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'removeVariantToAnonymousBasket' => [
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
                                    'name' => 'variantId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Variant ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'variantId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'quantity',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Quantity'
                                    ],
                                    'attributes' => [
                                        'id' => 'quantity',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'variantId' => [
                                'name' => 'variantId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Variant ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'quantity' => [
                                'name' => 'quantity',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Quantity must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'removeVariantToPersistentBasket' => [
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
                                    'name' => 'variantId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Variant ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'variantId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'quantity',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Quantity'
                                    ],
                                    'attributes' => [
                                        'id' => 'quantity',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'variantId' => [
                                'name' => 'variantId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Variant ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'quantity' => [
                                'name' => 'quantity',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Quantity must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
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
                    'emptyBasket' => [
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
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim']
                                ],
                            ],
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'emptyAnonymousBasket' => [
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
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => false,
                                'validators' => [

                                ],
                            ],
                        ],
                    ],
                    'emptyPersistentBasket' => [
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
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
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
                    'cleanAnonymousBaskets' => [
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
                                    'name' => 'daysToKeep',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Number of days to keep'
                                    ],
                                    'attributes' => [
                                        'id' => 'daysToKeep',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'daysToKeep' => [
                                'name' => 'daysToKeep',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Number of days to keep must be an integer'
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
                    'transferAnonymousBasketToPersistentBasket' => [
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
                                    'name' => 'clientKey',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client Key'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientKey',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '',
                                        'data-type' => 'string'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'clientId',
                                    'type' => 'Text',
                                    'options' => [
                                        'label' => 'Client ID'
                                    ],
                                    'attributes' => [
                                        'id' => 'clientId',
                                        'value' => '',
                                        'class' => '',
                                        'placeholder' => '1',
                                        'data-type' => 'int'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'clientKey' => [
                                'name' => 'clientKey',
                                'required' => true,
                                'validators' => [

                                ],
                            ],
                            'clientId' => [
                                'name' => 'clientId',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID must be an integer'
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