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
                'MelisCommerceCartPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceCart/cart'],
                        'id' => 'cartPlugin',
                        // Country id
                        'cart_country_id' => null,
                        // pagination config
                        'pagination' => [
                            'cart_current' => 1,
                            'cart_per_page' => 10,
                            'cart_nb_page_before_after' => 2,
                        ],
                        // Cart item deletion
                        'cart_variant_remove' => null
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
                            'id' => 'CART',
                            'title' => 'tr_meliscommerce_car_Cart'
                        ],
                        'name' => '\tr_meliscommerce_plugin_cart_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCartPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_cart_description',
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
                            'melis_commerce_plugin_cart_config' => [
                                'tab_title' => 'tr_meliscommerce_general_common_configuration',
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
                            'melis_commerce_plugin_cart_pagination_config' => [
                                'tab_title' => 'tr_meliscommerce_general_common_pagination',
                                'tab_icon'  => 'fa fa-forward',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'attributes' => [
                                    'name' => 'melis_commerce_plugin_cart_pagination_config',
                                    'id' => 'melis_commerce_plugin_cart_pagination_config',
                                    'method' => '',
                                    'action' => '',
                                ],
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'cart_per_page',
                                            'type' => 'MelisText',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_per_page',
                                                'tooltip' => 'tr_meliscommerce_general_common_per_page tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'cart_per_page',
                                                'class' => 'form-control',
                                                'placeholder' => 'tr_meliscommerce_general_common_per_page',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'cart_nb_page_before_after',
                                            'type' => 'MelisText',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_num_page_before_after',
                                                'tooltip' => 'tr_meliscommerce_general_common_num_page_before_after tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'cart_nb_page_before_after',
                                                'class' => 'form-control',
                                                'placeholder' => 'tr_meliscommerce_general_common_num_page_before_after',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'cart_per_page' => [
                                        'name'     => 'cart_per_page',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name'    => 'Digits',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\Digits::NOT_DIGITS => 'tr_front_common_input_not_digit',
                                                        \Laminas\Validator\Digits::STRING_EMPTY => '',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_front_common_input_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'cart_nb_page_before_after' => [
                                        'name'     => 'cart_nb_page_before_after',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name'    => 'Digits',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\Digits::NOT_DIGITS => 'tr_front_common_input_not_digit',
                                                        \Laminas\Validator\Digits::STRING_EMPTY => '',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_front_common_input_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                ]
                            ]
                        ],
                    ]
                ],
            ],
        ],
     ],
];