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
                'MelisCommerceLostPasswordGetEmailPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerce/ClientLostPassword'],
                        'id' => 'userLostPasswordGetEmail',
                        
                        // email of the account
                        'm_email' => '',
                        // submit form flag
                        'm_lost_password_get_email_is_submit' => false,
                        // reset password page link
                        'lost_password_reset_page_link' => '',
                        // email details
                        'email' => [
                            // custome email template
                            'email_template_path' => 'MelisCommerce/emailLayout',
                            'email_to' => '',
                            'email_to_name' => '',
                            'email_reply_to' => '',
                            'email_from' => 'noreply@melistechnology.com',
                            'email_from_name' => 'Melis Commerce',
                            'email_subject' => 'tr_meliscommerce_plugin_lost_password_email_default_subject',
                            'email_content' => 'tr_meliscommerce_plugin_lost_password_email_default_content',
                            'email_content_tag_replace' => [
                                'lostPasswordLink' => '', 
                                'recoveryKey' => ''
                            ],
                        ],
                        'forms' => [
                            'lost_password' => [
                                'attributes' => [
                                    'name' => 'lost_password',
                                    'id' => 'lost_password',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_lost_password_get_email_is_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'lost_password_reset_page_link',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_email',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_email_address',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_email',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_email_address',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                ],
                                'input_filter' => [
                                    'm_email' => [
                                        'name'     => 'm_email',
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
                        'name' => '\tr_meliscommerce_plugin_lost_password_get_email_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceLostPasswordGetEmailPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_lost_password_get_email_name_description',
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
                            'melis_commerce_plugin_lost_password_config' => [
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
                                            'name' => 'lost_password_reset_page_link',
                                            'type' => 'MelisText',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_lost_password_reset_page',
                                                'tooltip' => 'tr_meliscommerce_plugin_lost_password_reset_page tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'lost_password_reset_page_link',
                                                'class' => 'melis-input-group-button',
                                                'data-button-icon' => 'fa fa-sitemap',
                                                'data-button-id' => 'meliscms-site-selector',
                                                'data-callback' => 'generatePageLink',
                                                'data-absolute' => true,
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
                                    'lost_password_reset_page_link' => [
                                        'name'     => 'lost_password_reset_page_link',
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