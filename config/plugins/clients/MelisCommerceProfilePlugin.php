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
            'plugins' => [
                'MelisCommerceProfilePlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerce/ClientProfile'],
                        'id' => 'userProfile',
                        // User input fields
                        'cper_civility' => '',
                        'cper_firstname' => '',
                        'cper_name' => '',
                        'cper_middle_name' => '',
                        'cper_lang_id' => '',
                        'cper_email' => '',
                        'cper_password' => '',
                        'cper_confirm_password' => '',
                        'cper_job_title' => '',
                        'cper_job_service' => '',
                        'cper_tel_mobile' => '',
                        'cper_tel_landline' => '',
                        'cli_country_id' => '',
                        'profile_is_submit' => false,
                        'forms' => [
                            'meliscommerce_profile' => [
                                'attributes' => [
                                    'name' => '',
                                    'id' => '',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'profile_is_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_civility',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_firstname',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_firstname',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_middle_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_mname',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_middle_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_lang_id',
                                            'type' => 'EcomPluginLanguageSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_language',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_lang_id',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cli_country_id',
                                            'type' => 'EcomPluginCountriesSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Client_country',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'country',
                                                'placeholder' => 'tr_meliscommerce_client_Client_country',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_email',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_email_address',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_email',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_password',
                                            'type' => 'Text',
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password_placeholder',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_confirm_password',
                                            'type' => 'Text',
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password_placeholder',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_job_title',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_job_title',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_job_title',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_job_service',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_job_service',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_job_service',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_tel_mobile',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_mobile_num',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_tel_mobile',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_tel_landline',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_tp_num',
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_tel_landline',
                                                'class' => 'form-control'
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
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'melis' => [
                        /*
                        * if set this plugin will belong to a specific marketplace section,
                        * if not it will go directly to ( Others ] section
                        *  - available section for templating plugins as of 2019-05-16
                        *    - MelisCms
                        *    - MelisMarketing
                        *    - MelisSite
                        *    - MelisCommerce
                        *    - Others
                        *    - CustomProjects
                        */
                        'section' => 'MelisCommerce',
                        'subcategory' => [
                            'id' => 'CLIENTS',
                            'title' => 'tr_meliscommerce_clients_Clients'
                        ],
                        'name' => '\tr_meliscommerce_plugin_profile_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceProfilePlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_profile_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => [
                            'css' => [
                            ],
                            'js' => [
                            ],
                        ],
                        'modal_form' => [
                            'melis_commerce_plugin_profile_config' => [
                                'tab_title' => 'tr_meliscommerce_general_plugin_properties_title',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => [
                                                'label' => 'tr_melis_Plugins_Template',
                                                'tooltip' => 'tr_melis_Plugins_Template tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'template_path' => [
                                        'name'     => 'template_path',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                ]
                            ],
                        ]
                    ],
                ],
            ],
        ],
     ],
];