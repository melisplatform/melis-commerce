<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'plugins' => [
        'meliscommerce' => [
            'forms' => [
                'meliscommerce_products' => [
                    'meliscommerce_product_text_form' => [
                        'attributes' => [
                            'name' => 'productTextForm',
                            'id' => '',
                            'class' => 'productTextForm',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'ptxt_id',
                                    'type' => 'hidden',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_product_text_lang_id',
                                    ],
                                    'attributes' => [
                                        'id' => 'ptxt_id',
                                        'readonly' => 'readonly',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptxt_lang_id',
                                    'type' => 'hidden',
//                                     'type' => 'EcomLanguageSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_product_text_lang',
                                    ],
                                    'attributes' => [
                                        'id' => 'ptxt_lang_id',
                                        'readonly' => 'readonly',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptxt_type',
                                    'type' => 'EcomProductTextTypeSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_product_text_type',
                                        'tooltip' => 'tr_meliscommerce_product_text_type tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'ptxt_type',
                                        'readonly' => 'readonly',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptxt_field_short',
                                    'type' => 'MelisText',
                                    'options' => [

                                        'label_options' => [
                                            'disable_html_escape' => true,
                                        ]
                                    ],
                                    'attributes' => [
                                        'id' => 'ptxt_field_short',
                                        'style' => 'display:none;',
                                        'maxlength' => 45
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptxt_field_long',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label_options' => [
                                            'disable_html_escape' => true,
                                        ]
                                    ],
                                    'attributes' => [
                                        'class' => 'form-control hidden',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptt_id',
                                    'type' => 'Hidden',
                                    'options' => [],
                                    'attributes' => [
                                        'id' => 'ptt_id',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptt_code',
                                    'type' => 'Hidden',
                                    'options' => [],
                                    'attributes' => [
                                        'id' => 'ptt_code',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptt_name',
                                    'type' => 'Hidden',
                                    'options' => [],
                                    'attributes' => [
                                        'id' => 'ptt_name',
                                    ]
                                ]
                            ],
                        ],
                        'input_filter' => [
                            'ptxt_field_short' => [
                                'name'     => 'ptxt_field_short',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_product_text_short_too_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ptxt_type' => [
                                'name'     => 'ptxt_type',
                                'required' => false,
                                'validators' => [
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ptxt_field_long' => [
                                'name'     => 'ptxt_field_long',
                                'required' => true,
                                'validators' => [
                                ],
                                'filters'  => [
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ptxt_lang_id' => [
                                'name'     => 'ptxt_lang_id',
                                'required' => false,
                                'validators' => [
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ptt_id' => [
                                'name'     => 'ptt_id',
                                'required' => false,
                                'validators' => [
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ptt_code' => [
                                'name'     => 'ptt_code',
                                'required' => false,
                                'validators' => [
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ptt_code' => [
                                'name'     => 'ptt_code',
                                'required' => false,
                                'validators' => [
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ],
                    ],
                    'meliscommerce_product_text_type_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => 'productTextTypeForm',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'ptt_code',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_product_text_type_code',
                                        'tooltip' => 'tr_meliscommerce_product_text_type_code tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'ptt_code',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptt_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_product_text_type_name',
                                        'tooltip' => 'tr_meliscommerce_product_text_type_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'ptt_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ptt_field_type',
                                    'type' => 'Laminas\Form\Element\Select',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_product_text_type_field',
                                        'tooltip' => 'tr_meliscommerce_product_text_type_field tooltip',
                                        'value_options' => [
                                            '1' => 'tr_meliscommerce_product_text_short',
                                            '2' => 'tr_meliscommerce_product_text_long',
                                        ],
                                    ],
                                    'attributes' => [
                                        'id' => 'ptt_field_type',
                                        'value' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'ptt_code' => [
                                'name'     => 'ptt_code',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_product_text_type_code_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_product_text_type_code_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ptt_name' => [
                                'name'     => 'ptt_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_product_text_type_name_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_product_text_type_name_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ],
                    ],
                    'meliscommerce_products_reference_form' => [
                        'attributes' => [
                            'name' => 'product',
                            'id' => 'product',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'prd_reference',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'tooltip' => 'tr_meliscommerce_products_reference tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'prd_reference',
                                        'value' => '',
                                        'placeholder' => 'tr_meliscommerce_products_reference',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'prd_reference' => [
                                'name'     => 'prd_reference',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_products_reference_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_products_reference_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
