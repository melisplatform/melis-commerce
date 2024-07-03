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
                'meliscommerce_order_list' => [
                    'meliscommerce_order_list_status_form' => [
                        'attributes' => [
                            'name' => 'order-list-status',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'ord_id',
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
                                    'name' => 'ord_status',
                                    'type' => 'EcomOrderStatusSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_status',
                                        'tooltip' => 'tr_meliscommerce_orders_status tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'ord_status' => [
                                'name'     => 'ord_status',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsInt',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_orders_invalid_status',
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
                    'meliscommerce_order_list_export_form' => [
                        'attributes' => [
                            'name' => 'order-list-export',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'ord_status',
                                    'type' => 'EcomOrderStatusAllSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_status',
                                        'empty_option' => 'tr_meliscommerce_orders_status_empty',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'date_start',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_date_start',
                                    ],
                                    'attributes' => [
                                        'dateId' => 'date_start',
                                        'dateLabel' => 'tr_meliscommerce_orders_date_start',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'date_end',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_date_end',
                                    ],
                                    'attributes' => [
                                        'dateId' => 'date_end',
                                        'dateLabel' => 'tr_meliscommerce_orders_date_end',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'separator',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_sperator',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'value' => ';',
                                        'maxlength' => '1'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'ord_status' => [
                                'name'     => 'ord_status',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsInt',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_orders_invalid_status',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'separator' => [
                                'name' => 'separator',
                                'require' => true,
                                'validators' => [
                                    /* [
                                        'name'    => '\Laminas\Validator\InArray',
                                        'options' => [
                                            'haystack' => [',', ';'],
                                            'messages'=> [\Laminas\Validator\InArray::NOT_IN_ARRAY => 'tr_meliscommerce_orders_invalid_separator'],
                                        ],
                                    ], */
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ],
                    ],
                ],
                'meliscommerce_orders' => [
                    'meliscommerce_order_information_form' => [
                        'attributes' => [
                            'name' => 'order',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'ord_id',
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
                                    'name' => 'ord_reference',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_code',
                                        'tooltip' => 'tr_meliscommerce_orders_code tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '100'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'ord_id' => [
                                'name'     => 'ord_id',
                                'required' => false,
                                'validators' => [

                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ord_reference' => [
                                'name'     => 'ord_reference',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
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
                        ],
                    ],
                    'meliscommerce_order_address_form' => [
                        'attributes' => [
                            'name' => 'orderAddressForm',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'oadd_id',
                                    'type' => 'hidden',
                                    'attributes' => [],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_order_id',
                                    'type' => 'hidden',
                                    'attributes' => [],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_type',
                                    'type' => 'hidden',
                                    'attributes' => [],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_creation_date',
                                    'type' => 'hidden',
                                    'attributes' => [],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_company',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_company',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '100'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_civility',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_firstname',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_fname',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '255'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_lname',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '255'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_middle_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_mname',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '255'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_num',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_address_num',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '10'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_street',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_street',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '255'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_building_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_building',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '45'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_stairs',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_floor',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '10'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_city',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_city',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '100'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_state',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_state',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '50'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_country',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_country',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '50'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_zipcode',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_zip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '15'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_phone_mobile',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_mobile',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '45'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_phone_landline',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_phone',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '45'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oadd_complementary',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_address_comments',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 255,
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'oadd_civility' => [
                                'name'     => 'oadd_civility',
                                'required' => false,
                                'validators' => [],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_name' => [
                                'name'     => 'oadd_name',
                                'required' => true,
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
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_middle_name' => [
                                'name'     => 'oadd_middle_name',
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
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_firstname' => [
                                'name'     => 'oadd_firstname',
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
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_num' => [
                                'name'     => 'oadd_num',
                                'required' => false,
                                'validators' => [
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
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_stairs' => [
                                'name'     => 'oadd_stairs',
                                'required' => false,
                                'validators' => [
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
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_building_name' => [
                                'name'     => 'oadd_building_name',
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
                            'oadd_company' => [
                                'name'     => 'oadd_company',
                                'required' => false,
                                'validators' => [
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
                            'oadd_street' => [
                                'name'     => 'oadd_street',
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
                            'oadd_city' => [
                                'name'     => 'oadd_city',
                                'required' => false,
                                'validators' => [
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
                            'oadd_state' => [
                                'name'     => 'oadd_state',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_50',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_country' => [
                                'name'     => 'oadd_country',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_50',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_zipcode' => [
                                'name'     => 'oadd_zipcode',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 15,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_15',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'regex', false,
                                        'options' => [
                                            'pattern' => '/^([0-9\(\]\/\+ \-]*)$/',
                                            'messages'=> [\Laminas\Validator\Regex::NOT_MATCH => 'tr_meliscommerce_orders_invalid_number'],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_complementary' => [
                                'name'     => 'oadd_complementary',
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
                            'oadd_phone_mobile' => [
                                'name'     => 'oadd_phone_mobile',
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
                                    [
                                        'name'    => 'regex', false,
                                        'options' => [
                                            'pattern' => '/^([0-9\(\]\/\+ \-]*)$/',
                                            'messages'=> [\Laminas\Validator\Regex::NOT_MATCH => 'tr_meliscommerce_orders_invalid_phone'],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oadd_phone_landline' => [
                                'name'     => 'oadd_phone_landline',
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
                                    [
                                        'name'    => 'regex', false,
                                        'options' => [
                                            'pattern' => '/^([0-9\(\]\/\+ \-]*)$/',
                                            'messages'=> [\Laminas\Validator\Regex::NOT_MATCH => 'tr_meliscommerce_orders_invalid_phone'],
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
                    'meliscommerce_order_shipping_form' => [
                        'attributes' => [
                            'name' => 'orderShippingForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'oship_id',
                                    'type' => 'hidden',
                                    'attributes' => [
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oship_tracking_code',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_tracking_code',
                                        'tooltip' => 'tr_meliscommerce_orders_tracking_code tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => 100,
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oship_content',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_tracking_details',
                                        'tooltip' => 'tr_meliscommerce_orders_tracking_details tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 1200,
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'oship_date_sent',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_tracking_date',
                                        'tooltip' => 'tr_meliscommerce_orders_tracking_date tooltip',
                                    ],
                                    'attributes' => [
                                        'dateId' => 'shippingDate',
                                        'dateLabel' => 'tr_meliscommerce_orders_tracking_date',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'oship_tracking_code' => [
                                'name'     => 'oship_tracking_code',
                                'required' => true,
                                'validators' => [
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
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oship_content' => [
                                'name'     => 'oship_content',
                                'required' => true,
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
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'oship_date_sent' => [
                                'name'     => 'oship_date_sent',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => '\Laminas\Validator\NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
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
                    'meliscommerce_order_message_form' => [
                        'attributes' => [
                            'name' => 'orderMessagesForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'omsg_message',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_message_your',
                                        'tooltip' => 'tr_meliscommerce_orders_message_your tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 1200,
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'omsg_message' => [
                                'name'     => 'omsg_message',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => '\Laminas\Validator\NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ],
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
                ],
                'meliscommerce_order_status' => [
                    'meliscommerce_order_status_form' => [
                        'attributes' => [
                            'name' => 'order_status',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'osta_id',
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
                                    'name' => 'osta_color_code',
                                    'type' => 'EcomColorPicker',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_order_status_col_color_code',
                                        'tooltip' => 'tr_meliscommerce_order_status_col_color_code tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'required' => 'required',
                                        'class' => 'form-control osta_color_code minicolor'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'osta_color_code' => [
                                'name'     => 'osta_color_code',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => '\Laminas\Validator\NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ],
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
                        ],
                    ],
                    'meliscommerce_order_status_trans_form' => [
                        'attributes' => [
                            'name' => 'order_trans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'ostt_id',
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
                                    'name' => 'ostt_status_id',
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
                                    'name' => 'ostt_lang_id',
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
                                    'name' => 'ostt_status_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_order_status_col_ord_status',
                                        'tooltip' => 'tr_meliscommerce_order_status_col_ord_status tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'ostt_status_name' => [
                                'name'     => 'ostt_status_name',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_50',
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
