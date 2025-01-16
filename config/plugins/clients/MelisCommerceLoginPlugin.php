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
                'MelisCommerceLoginPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerce/ClientLogin'],
                        'id' => 'userLogin',

                        // form fields
                        'm_login' => '',
                        'm_password' => '',
                        'm_remember_me' => '',
                        'm_redirection_link_ok' => '',
                        'm_required_login' => false,
                        
                        // flag true if a form is submitted
                        'm_login_is_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => [
                            'meliscommerce_login' => [
                                'attributes' => [
                                    'name' => '',
                                    'id' => 'login',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_redirection_link_ok',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_login',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_login_email',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_login',
                                                'placeholder' => 'tr_meliscommerce_plugin_login_email',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_password',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_login_password',
                                                'label_options' => [
                                                    'disable_html_escape' => true,
                                                ]
                                            ],
                                            'attributes' => [
                                                'id' => 'm_password',
                                                'placeholder' => 'tr_meliscommerce_plugin_login_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_remember_me',
                                            'type' => 'Checkbox',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_login_remember_me',
                                                'use_hidden_element' => false,
                                                'checked_value' => '1',
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_login_is_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                ],
                                'input_filter' => [
                                    'm_login' => [
                                        'name'     => 'm_login',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'EmailAddress',
                                                'options' => [
                                                    'domain'   => 'true',
                                                    'hostname' => 'true',
                                                    'mx'       => 'true',
                                                    'deep'     => 'true',
                                                    'message'  => 'tr_meliscommerce_plugin_login_invalid_email',
                                                ]
                                            ],
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
                                            ['name' => 'StripTags'],
                                            ['name' => 'StringTrim'],
                                        ],
                                    ],
                                    'm_password' => [
                                        'name'     => 'm_password',
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
                                            ['name' => 'StripTags'],
                                            ['name' => 'StringTrim'],
                                        ],
                                    ],
                                    'm_remember_me' => [
                                        'name'     => 'm_remember_me',
                                        'required' => false,
                                        'validators' => [],
                                        'filters'  => [
                                            ['name' => 'StripTags'],
                                            ['name' => 'StringTrim'],
                                        ],
                                    ]
                                ],
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
                        'name' => '\tr_meliscommerce_plugin_login',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceLoginPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_login_description',
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
                                ]
                            ],
                        ]
                    ],
                ],
            ],
        ],
     ],
];