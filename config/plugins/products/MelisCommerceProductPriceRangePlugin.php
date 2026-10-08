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
                'MelisCommerceProductPriceRangePlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceProduct/product-price-range'],
                        'id' => 'productPriceRange',
                        // filtering
                        'm_box_product_price_min' => null,
                        'm_box_product_price_max' => null,
                
                        // price column are : price_net, price_gross
                        'm_box_product_price_column' => 'price_net',
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
                            'id' => 'PRODUCTS',
                            'title' => 'tr_meliscommerce_products_Products'
                        ],
                        'name' => '\tr_meliscommerce_plugin_product_price_range_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceProductPriceRangePlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_product_price_range_description',
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
                            'melis_commerce_plugin_category_product_price_config' => [
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
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_box_product_price_column',
                                            'type' => 'select',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_product_price_column',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_product_price_column_tooltip',
                                                'value_options' => [
                                                    'price_net' => 'tr_meliscommerce_plugin_product_price_column_price_net',
                                                    'price_gross' => 'tr_meliscommerce_plugin_product_price_column_price_gross',
                                                    'price_vat_price' => 'tr_meliscommerce_plugin_product_price_column_price_vat_price',
                                                    'price_other_tax_price' => 'tr_meliscommerce_plugin_product_price_column_price_other_tax_price',
                                                ],
                                            ],
                                            'attributes' => [
                                                'id' => 'm_box_product_price_column',
                                                'class' => 'form-control',
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
                        ],
                        'product_price_range_form' => [
                            'melis_commerce_plugin_category_product_price_range_form' => [
                                'attributes' => [
                                    'name' => 'productPriceRangeForm',
                                    'method' => 'POST',
                                    'action' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_box_product_price_min',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_product_price_range_min',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_box_product_price_min',
                                                'class' => 'form-control',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_box_product_price_max',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_product_price_range_max',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_box_product_price_max',
                                                'class' => 'form-control',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'm_box_product_price_min' => [
                                        'name'     => 'm_box_product_price_min',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_product_price_min_price_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_box_product_price_max' => [
                                        'name'     => 'm_box_product_price_max',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_product_price_max_price_empty',
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