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
                'MelisCommerceRegisterPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerce/ClientRegister'],
                        'id' => 'userRegistration',
                        
                        // form fields
                        'cper_email' => '',
                        'cper_password' => '',
                        'cper_password2' => '',
                        'cper_civility' => 0,
                        'cper_firstname' => '',
                        'cper_name' => '',
                        'm_country' => 1,
                        
                        'm_redirection_link_ok' => '',
                        'm_autologin' => false,
                        
                        // flag true if a form is submitted
                        'm_registration_is_submit' => false,
                        
                        'forms' => [
                            'meliscommerce_registration' => [
                                'attributes' => [
                                    'name' => '',
                                    'id' => 'registration',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_autologin',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_redirection_link_ok',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_registration_is_submit',
                                            'type' => 'hidden',
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_email_address',
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cper_password2',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'label_options' => [
                                                    'disable_html_escape' => true,
                                                ]
                                            ],
                                            'attributes' => [
                                                'id' => 'cper_password2',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                                'class' => 'form-control'
                                            ]
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_fname',
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_country',
                                            'type' => 'EcomPluginCountriesSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Client_country',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_country',
                                                'placeholder' => 'tr_meliscommerce_client_Client_country',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                ],
                                'input_filter' => [
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
                                    'cper_password2' => [
                                        'name'     => 'cper_password2',
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
                                    'm_country' => [
                                        'name'     => 'm_country',
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
                        'name' => '\tr_meliscommerce_plugin_registration_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceRegistrationPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_registration_description',
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
                            'melis_commerce_plugin_login_config' => [
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
                                    [
                                        'spec' => [
                                            'name' => 'm_redirection_link_ok',
                                            'type' => 'MelisText',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_destination_page_link',
                                                'tooltip' => 'tr_meliscommerce_general_common_destination_page_link tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_redirection_link_ok',
                                                'class' => 'melis-input-group-button',
                                                'data-button-icon' => 'fa fa-sitemap',
                                                'data-button-id' => 'meliscms-site-selector',
                                                'data-callback' => 'generatePageLink',
                                                'required' => 'required'
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_autologin',
                                            'type' => 'Checkbox',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_autologin',
                                                'tooltip' => 'tr_meliscommerce_general_common_autologin tooltip',
                                                'checked_value' => '1',
                                                'unchecked_value' => '0',
                                                'switchOptions' => [
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                ]
                                            ],
                                            'attributes' => [
                                                'id' => 'm_autologin',
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
                                    'm_redirection_link_ok' => [
                                        'name'     => 'm_redirection_link_ok',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_input_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_autologin' => [
                                        'name'     => 'm_autologin',
                                        'required' => false,
                                        'validators' => [
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