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
                'MelisCommerceCheckoutPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceCheckout/checkout'],
                        'id' => 'checkout',
                        
                        // checkout steps
                        'm_checkout_step' => '',
                        // country id
                        'm_checkout_country_id' => 1,
                        // site id
                        'm_checkout_site_id' => 1,
                        // page link
                        'm_checkout_page_link' => '',
                        // page link ro reroute user if not logged in
                        'm_login_page_link' => '',
                        // if true this will allow plugin to redirect to a url
                        'm_redirect_to_url' => true,
                        
                        // Sub plugin parameters
                        'checkout_cart_parameters' => [],
                        'checkout_addresses_parameters' => [],
                        'checkout_summary_parameters' => [],
                        'checkout_confirm_summary_parameters' => [],
                        'checkout_confirm_parameters' => [],
                        
                        // Sub plugins
                        'sub_plugins_params' => [
                            'checkout_cart_parameters',
                            'checkout_addresses_parameters',
                            'checkout_summary_parameters',
                            'checkout_confirm_summary_parameters',
                            'checkout_confirm_parameters',
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
                            'id' => 'ORDERS',
                            'title' => 'tr_meliscommerce_orders_Orders'
                        ],
                        'name' => '\tr_meliscommerce_plugin_checkout_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCheckoutPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_checkout_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => [
                            'css' => [
                            ],
                            'js' => [
                            ],
                        ],
                        'js_initialization' => [],
                        'modal_form' => [
                            'melis_commerce_plugin_checkout_config' => [
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
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_template_tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => true,
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_checkout_step',
                                            'type' => 'Select',
                                            'options' => [
                                                'label' => 'Step',
                                                'tooltip' => 'tr_meliscommerce_plugin_checkout_step tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => [
                                                    'checkout-cart' => 'Cart',
                                                    'checkout-addresses' => 'Delivery and billing addresses',
                                                    'checkout-summary' => 'Summary',
                                                    'checkout-confirm-summary' => 'Summary confirmation',
                                                    'checkout-payment' => 'Payment',
                                                    'checkout-confirm' => 'Checkout confirmation',
                                                ],
                                            ],
                                            'attributes' => [
                                                'id' => 'm_checkout_step',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_checkout_country_id',
                                            'type' => 'EcomPluginPriceCountriesSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_country',
                                                'tooltip' => 'tr_meliscommerce_general_common_country tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_checkout_country_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_checkout_site_id',
                                            'type' => 'MelisCoreSiteSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_site',
                                                'tooltip' => 'tr_meliscommerce_general_common_site tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_checkout_site_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_checkout_page_link',
                                            'type' => 'MelisText',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_destination_page_link',
                                                'tooltip' => 'tr_meliscommerce_general_common_destination_page_link tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_checkout_page_link',
                                                'class' => 'melis-input-group-button',
                                                'data-button-icon' => 'fa fa-sitemap',
                                                'data-button-id' => 'meliscms-site-selector',
                                                'data-callback' => '',
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
                                    'm_checkout_step' => [
                                        'name'     => 'm_checkout_step',
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
                                    'm_checkout_country_id' => [
                                        'name'     => 'm_checkout_country_id',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_category_product_list_no_country',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_checkout_site_id' => [
                                        'name'     => 'm_checkout_site_id',
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
                        ],
                    ],
                ],
            ],
        ],
    ],
];