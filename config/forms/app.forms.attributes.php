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
                'meliscommerce_attributes' => [
                    'meliscommerce_attribute_general_data' => [
                        'attributes' => [
                            'name' => 'attribute',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'attr_reference',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_reference',
                                        'tooltip' => 'tr_meliscommerce_attribute_reference tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'max' => '45',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'attr_type_id',
                                    'type' => 'EcomAttributeTypeSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_type',
                                        'tooltip' => 'tr_meliscommerce_attribute_type tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'required' => 'required',
                                    ]
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'attr_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'attr_reference' => [
                                'name'     => 'attr_reference',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_45',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'attr_type_id' => [
                                'name'     => 'attr_type_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
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
                    'meliscommerce_attribute_text_trans' => [
                        'attributes' => [
                            'name' => 'attributeTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'atrans_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_label',
                                        'tooltip' => 'tr_meliscommerce_attribute_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'max' => 100,
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'atrans_description',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_label_description',
                                        'tooltip' => 'tr_meliscommerce_attribute_label_description tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 45,
                                    ]
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'atrans_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'atrans_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'atrans_name' => [
                                'name'     => 'atrans_name',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_100',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'atrans_description' => [
                                'name'     => 'atrans_description',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_45',
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
                    'meliscommerce_attribute_value_avt_v_int' => [
                        'attributes' => [
                            'name' => 'attributeValueTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'avt_v_int',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_value_integer',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '9',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'av_attribute_value_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'avt_v_int' => [
                                'name'     => 'avt_v_int',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsInt',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_coupon_input_invalid_digit',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_10',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [

                                ],
                            ],
                        ],
                    ],
                    'meliscommerce_attribute_value_avt_v_float' => [
                        'attributes' => [
                            'name' => 'attributeValueTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'avt_v_float',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_value_decimal',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'max' => '11',
                                    ]
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'av_attribute_value_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'avt_v_float' => [
                                'name'     => 'avt_v_float',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsFloat',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsFloat::NOT_FLOAT  => 'tr_meliscommerce_coupon_input_invalid_decimal'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [

                                ],
                            ],
                        ],
                    ],
                    'meliscommerce_attribute_value_avt_v_bool' => [
                        'attributes' => [
                            'name' => 'attributeValueTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'avt_v_bool',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_value_boolean',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ]
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'av_attribute_value_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [],
                    ],
                    'meliscommerce_attribute_value_avt_v_varchar' => [
                        'attributes' => [
                            'name' => 'attributeValueTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'avt_v_varchar',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_value_varchar',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'max' => '255',
                                    ]
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'av_attribute_value_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'avt_v_varchar' => [
                                'name'     => 'avt_v_varchar',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_255',
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
                    'meliscommerce_attribute_value_avt_v_text' => [
                        'attributes' => [
                            'name' => 'attributeValueTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'avt_v_text',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_value_text',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 1200,
                                    ]
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'av_attribute_value_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'avt_v_text' => [
                                'name'     => 'avt_v_text',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 1200,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_1200',
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
                    'meliscommerce_attribute_value_avt_v_datetime' => [
                        'attributes' => [
                            'name' => 'attributeValueTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'avt_v_datetime',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_attribute_value_date',
                                    ],
                                    'attributes' => [
                                        'dateId' => 'valueDate',
                                        'dateLabel' => 'tr_meliscommerce_attribute_value_date',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'av_attribute_value_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'avt_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [],
                    ],
                ],
            ],
        ],
    ],
];