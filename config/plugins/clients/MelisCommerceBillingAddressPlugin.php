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
                'MelisCommerceBillingAddressPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerce/ClientBillingAddress'],
                        'id' => 'userBillingAddress',
                        
                        // enables user to add new addresses, select address to edit
                        'show_select_address_data' => false,
                        'select_billing_addresses' => '',
                        'select_billing_addresses_submit' => false,
                        
                        // form fields
                        'cadd_id' => '',
                        'cadd_address_name' => '',
                        'cadd_civility' => '',
                        'cadd_firstname' => '',
                        'cadd_name' => '',
                        'cadd_middle_name' => '',
                        'cadd_num' => '',
                        'cadd_street' => '',
                        'cadd_building_name' => '',
                        'cadd_stairs' => '',
                        'cadd_city' => '',
                        'cadd_country' => '',
                        'cadd_zipcode' => '',
                        'cadd_company' => '',
                        'cadd_phone_mobile' => '',
                        'cadd_phone_landline' => '',
                        'cadd_complementary' => '',
                        // flag true if a form is submitted
                        'billing_address_save_submit' => false,
                        'billing_address_delete_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => [
                            'select_billing_address' => [
                                'attributes' => [
                                    'name' => 'select_billing_address',
                                    'method' => 'POST',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'select_billing_addresses_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'select_billing_addresses',
                                            'type' => 'EcomPluginBillingAddressSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_select_address',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'select_billing_addresses',
                                                'data-selectaddress' => 'select-address',
                                                'class' => 'form-control',
                                                'onchange' => 'this.form.submit()'
                                            ]
                                        ]
                                    ],
                                ],
                                'input_filter' => [
                                    'select_billing_addresses' => [
                                        'name'     => 'select_billing_addresses',
                                        'required' => false,
                                        'validators' => [
                                        ],
                                        'filters' => [
                                        ],
                                    ],
                                ]
                            ],
                            'billing_address' => [
                                'attributes' => [
                                    'name' => 'billing_address',
                                    'method' => 'POST',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'billing_address_save_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_id',
                                            'type' => 'hidden',
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_address_name',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_address_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_civility',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_firstname',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_firstname',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_middle_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_mname',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_middle_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_num',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_num',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_num',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_street',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_street',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_building_name',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_building_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_building_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_stairs',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_stairs',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_stairs',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_city',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_city',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_city',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_state',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_state',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_state',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_country',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_country',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_country',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_zipcode',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_zipcode',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_company',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_company_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_company',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_phone_mobile',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_phone_mobile',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_phone_landline',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_phone_landline',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cadd_complementary',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
                                            ],
                                            'attributes' => [
                                                'id' => 'cadd_complementary',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                ],
                                'input_filter' => [
                                    'cadd_id' => [
                                        'name'     => 'cadd_id',
                                        'required' => false,
                                        'validators' => [
                                            [
                                                'name'    => 'IsInt',
                                            ],
                                        ],
                                        'filters' => [
                                        ],
                                    ],
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
                                    'cadd_firstname' => [
                                        'name'     => 'cadd_firstname',
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
                                    'cadd_name' => [
                                        'name'     => 'cadd_name',
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
                        'name' => '\tr_meliscommerce_plugin_billing_address_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceBillingAddressPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_billing_address_description',
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
                                    [
                                        'spec' => [
                                            'name' => 'show_select_address_data',
                                            'type' => 'Checkbox',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_show_select_addresses',
                                                'tooltip' => 'tr_meliscommerce_general_common_show_select_addresses tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => [
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                ]
                                            ],
                                            'attributes' => [
                                                'id' => 'show_select_address_data',
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
                                    'show_select_address_data' => [
                                        'name'     => 'show_select_address_data',
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