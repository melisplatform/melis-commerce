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
                'MelisCommerceAddToCartPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceOrder/add-to-cart'],
                        'id' => 'addToCart',
                        // Id of the variant
                        'm_variant_id' => null,
                        // country id of the variant,
                        'm_variant_country' => -1,
                        // Quantity of the variant added
                        'm_variant_quantity' => 1,
                        // flag true if a form is submitted
                        'm_add_to_cart_is_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => [
                            'meliscommerce_add_to_cart_form' => [
                                'attributes' => [
                                    'name' => '',
                                    'id' => '',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_add_to_cart_is_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_variant_id',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_variant_country',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_variant_quantity',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_add_to_cart_quantity',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_variant_quantity',
                                                'class' => 'form-control',
                                            ]
                                        ]
                                    ],
                                ],
                                'input_filter' => [
                                    'm_variant_id' => [
                                        'name'     => 'm_variant_id',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_id_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                            ['name' => 'StripTags'],
                                            ['name' => 'StringTrim'],
                                        ],
                                    ],
                                    'm_variant_country' => [
                                        'name'     => 'm_variant_country',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_country_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                            ['name' => 'StripTags'],
                                            ['name' => 'StringTrim'],
                                        ],
                                    ],
                                    'm_variant_quantity' => [
                                        'name'     => 'm_variant_quantity',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'Digits',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscommerce_add_to_cart_variant_quantity_invalid',
                                                        \Laminas\Validator\Digits::STRING_EMPTY => '',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_quantity_empty',
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
                            'id' => 'CART',
                            'title' => 'tr_meliscommerce_car_Cart'
                        ],
                        'name' => '\tr_meliscommerce_plugin_add_cart_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceAddToCartPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_add_cart_description',
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
                            'melis_commerce_plugin_account_config' => [
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