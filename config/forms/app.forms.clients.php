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
                'meliscommerce_clients' => [
                    'meliscommerce_clients_main_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => 'id_meliscommerce_clients_main_form',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cli_group_id',
                                    'type' => 'EcomOrderClientsGroupSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_clients_group_common_group',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'cli_group_id',
                                        'class' => 'form-control',
                                        'required' => false,
                                        'value' => 1,
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cli_country_id',
                                    'type' => 'EcomCountriesNoAllCountriesSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Client_country',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'cli_country_id',
                                        'class' => 'form-control',
                                    ]
                                ]
                            ],
                        ],
                        'input_filter' => [
                            'cli_group_id' => [
                                'name'     => 'cli_group_id',
                                'required' => false,
                            ],
                            'cli_country_id' => [
                                'name'     => 'cli_country_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ]
                    ],
                    'meliscommerce_client_list_export_form' => [
                        'attributes' => [
                            'name' => 'client-list-export',
//                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cli_status',
                                    'type' => 'Select',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_page_status',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_all',
                                        'value_options' => [
                                            'active' => 'tr_meliscommerce_coupon_page_status_online',
                                            'inactive' => 'tr_meliscommerce_coupon_page_status_offline',
                                        ],
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_civility',
                                    ]
                                ]
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
                            'cli_status' => [
                                'name' => 'cli_status',
                                'required' => false,
                                'validators' => [
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
                    'meliscommerce_clients_contact_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                            'class' => 'clientContactForm',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cper_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_client_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_is_main_person',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_status',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_civility',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_civility tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_civility',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_firstname',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_fname',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_fname tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_firstname',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_name',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_middle_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_mname',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_mname tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_middle_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_lang_id',
                                    'type' => 'EcomLanguageSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_language',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_language tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_lang_id',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_email',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_email_address',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_email_address tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_email',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_password',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_password',
                                        'label_options' => [
                                            'disable_html_escape' => true,
                                        ]
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_password',
                                        'Type' => 'password',
                                        'autocomplete' => 'off',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_confirm_password',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_confirm_password',
                                        'label_options' => [
                                            'disable_html_escape' => true,
                                        ]
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_confirm_password',
                                        'Type' => 'password',
                                        'autocomplete' => 'off',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_job_title',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_job_title',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_job_title tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_job_title',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_job_service',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_job_service',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_job_service tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_job_service',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_tel_mobile',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_mobile_num',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_mobile_num tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_tel_mobile',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cper_tel_landline',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_tp_num',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_tp_num tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cper_tel_landline',
                                    ]
                                ]
                            ],
                        ],
                        'input_filter' => [
                            'cper_civility' => [
                                'name'     => 'cper_civility',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_firstname' => [
                                'name'     => 'cper_firstname',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_name' => [
                                'name'     => 'cper_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_lang_id' => [
                                'name'     => 'cper_lang_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_email' => [
                                'name'     => 'cper_email',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'EmailAddress',
                                        'options' => [
                                            'domain'   => 'true',
                                            'hostname' => 'true',
                                            'mx'       => 'true',
                                            'deep'     => 'true',
                                            'message'  => 'tr_meliscommerce_client_Contact_invalid_email',
                                        ]
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_password' => [
                                'name'     => 'cper_password',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => '\MelisCommerce\Validator\MelisPasswordValidator',
                                        'options' => [
                                            'min' => 8,
                                            'messages' => [
                                                \MelisCommerce\Validator\MelisPasswordValidator::TOO_SHORT => 'tr_meliscommerce_client_Contact_password_error_low',
                                                \MelisCommerce\Validator\MelisPasswordValidator::NO_DIGIT => 'tr_meliscommerce_client_Contact_password_regex_not_match',
                                                \MelisCommerce\Validator\MelisPasswordValidator::NO_LOWER => 'tr_meliscommerce_client_Contact_password_regex_not_match',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_confirm_password' => [
                                'name'     => 'cper_confirm_password',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'Identical',
                                        'options' => [
                                            'token' => 'cper_password',
                                            'messages' => [
                                                \Laminas\Validator\Identical::NOT_SAME => 'tr_meliscommerce_client_Contact_confirm_password_not_match'
                                            ]
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_job_title' => [
                                'name'     => 'cper_job_title',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 80,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_80',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_job_service' => [
                                'name'     => 'cper_job_service',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 80,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_80',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_tel_mobile' => [
                                'name'     => 'cper_tel_mobile',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cper_tel_landline' => [
                                'name'     => 'cper_tel_landline',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ]
                    ],
                    'meliscommerce_clients_addresses_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cadd_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_client_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_client_person',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_address_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_address_name',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_type',
                                    'type' => 'EcomAddressTypeSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_type',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_type tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_type',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_civility',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_civility tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_civility',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_firstname',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_fname',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_fname tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_firstname',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_name',
                                        'required' => 'required',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_middle_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_mname',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_mname tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_middle_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_num',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_street_num',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_street_num tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_num',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_street',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_street_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_street_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_street',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_building_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_building_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_building_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_building_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_stairs',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_stairs',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_stairs tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_stairs',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_city',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_city',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_city tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_city',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_state',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_state',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_state tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_state',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_country',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_country',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_country tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_country',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_zipcode',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_zipcode tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_zipcode',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_company',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_company_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_company',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_phone_mobile',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_mobile_number tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_phone_mobile',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_phone_landline',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_phone_landline tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_phone_landline',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_complementary',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_additional_information tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_complementary',
                                    ]
                                ]
                            ],
                        ],
                        'input_filter' => [
                            'cadd_address_name' => [
                                'name'     => 'cadd_address_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_type' => [
                                'name'     => 'cadd_type',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_civility' => [
                                'name'     => 'cadd_civility',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_name' => [
                                'name'     => 'cadd_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_firstname' => [
                                'name'     => 'cadd_firstname',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_num' => [
                                'name'     => 'cadd_num',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_stairs' => [
                                'name'     => 'cadd_stairs',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_building_name' => [
                                'name'     => 'cadd_building_name',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_company' => [
                                'name'     => 'cadd_company',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_street' => [
                                'name'     => 'cadd_street',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_zipcode' => [
                                'name'     => 'cadd_zipcode',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 15,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_15',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_city' => [
                                'name'     => 'cadd_city',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_state' => [
                                'name'     => 'cadd_state',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_country' => [
                                'name'     => 'cadd_country',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_phone_mobile' => [
                                'name'     => 'cadd_phone_mobile',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_phone_landline' => [
                                'name'     => 'cadd_phone_landline',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_complementary' => [
                                'name'     => 'cadd_complementary',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ]
                    ],
                    'meliscommerce_clients_company_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => 'id_meliscommerce_clients_company_form',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'ccomp_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_client_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Company_name'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_number_id',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Company_id'
                                    ],
                                    'attributes' => [
                                        'id' => 'comp_number_id',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_vat_number',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Company_vat_num'
                                    ],
                                    'attributes' => [
                                        'id' => 'comp_vat_number',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_group',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Company_ccomp_group'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_group',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_comp_creation_date',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_creation_date'
                                    ],
                                    'attributes' => [
                                        'dateId' => 'client_company_creation_date',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_employee_nb',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_number_employees'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_employee_nb',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_add_number',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_street_number'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_add_number',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_add_street',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_street_name'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_add_street',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_add_building',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_building'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_add_building',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_add_zipcode',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_zipcode'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_add_zipcode',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_add_city',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_city'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_add_city',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_add_state',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_state'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_add_state',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_add_country',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_country'
                                    ],
                                    'attributes' => [
                                        'id' => 'ccomp_add_country',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'ccomp_logo',
                                    'type' => 'file',
                                    'attributes' => [
                                        'id' => 'ccomp_logo',
                                        'value' => '',
                                        'class' => 'filestyle ccomp_logo',
                                        'label' => 'Upload',
                                        'onchange' => 'companyLogoPreview(".client-company-thumbnail", this);',
                                    ],
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_logo',
                                    ],
                                ]
                            ]
                        ],
                        'input_filter' => [
                            'ccomp_name' => [
                                'name'     => 'ccomp_name',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_number_id' => [
                                'name'     => 'ccomp_number_id',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 150,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_150',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_vat_number' => [
                                'name'     => 'ccomp_vat_number',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 150,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_150',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_group' => [
                                'name'     => 'ccomp_group',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_comp_creation_date' => [
                                'name'     => 'ccomp_comp_creation_date',
                                'required' => false,
                                'validators' => [],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_employee_nb' => [
                                'name'     => 'ccomp_employee_nb',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'tr_melis_cms_sites_field_digits',
                                                \Laminas\I18n\Validator\IsInt::INVALID => 'tr_melis_cms_sites_field_digits',
                                            ]
                                        ]
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_number' => [
                                'name'     => 'ccomp_add_number',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_street' => [
                                'name'     => 'ccomp_add_street',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_building' => [
                                'name'     => 'ccomp_add_building',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_floor' => [
                                'name'     => 'ccomp_add_floor',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_zipcode' => [
                                'name'     => 'ccomp_add_zipcode',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 15,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_15',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_city' => [
                                'name'     => 'ccomp_add_city',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_state' => [
                                'name'     => 'ccomp_add_state',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_add_country' => [
                                'name'     => 'ccomp_add_country',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max' => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'ccomp_logo' => [
                                'name'     => 'ccomp_logo',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'FileIsImage',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\File\IsImage::FALSE_TYPE => 'tr_meliscommerce_client_Contact_company_logo_file_type'
                                            ],
                                        ]
                                    ], [
                                        'name' => 'FileSize',
                                        'options' => [
                                            'max' => '500kB',
                                            'messages' => [
                                                \Laminas\Validator\File\Size::TOO_BIG => 'tr_meliscommerce_client_Contact_company_logo_too_big'
                                            ]
                                        ]
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ]
                    ],
                ]
            ]
        ]
    ]
];