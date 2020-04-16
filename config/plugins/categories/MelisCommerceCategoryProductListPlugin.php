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
                'MelisCommerceCategoryProductListPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceCategory/category-product-list'],
                        'id' => 'categoryProductList',
                        // Category option
                        'm_category_option' => [
                            // Arrays of category id selected
                            'm_category_ids' => [],
                            /**
                             * Sorting, by default it will retrieve
                             * category by Category translations in Ascending order
                             */
                            'm_cat_col_name' => 'catt_name',
                            'm_cat_order'   => 'ASC',
                            
                            /**
                             * Options for including products of the Category
                             * sub-categories products
                             */
                            'm_include_sub_category_products' => null,
                        ],
                        // Product option
                        'm_product_option' => [
                            /**
                             * The country id where the products price retrieve,
                             * by default price will get from general price with default currency
                             */
                            'm_country_id' => -1,
                            /**
                             * Sorting, by default it will retrieve
                             * product by Product translations in Ascending order
                             */
                            'm_prd_col_name' => 'prd_id',
                            'm_prd_order'   => 'ASC',
                            // Include products with no prices
                            /* 'm_prd_include_price' => true */
                            'm_prd_limit'   => null,
                        ],
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
                            'id' => 'CATEGORIES',
                            'title' => 'tr_meliscommerce_plugin_sub_categories_title'
                        ],
                        'name' => '\tr_meliscommerce_plugin_category_product_list_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCategoryProductListPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_category_product_list_description',
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
                            'melis_commerce_plugin_category_product_list_config' => [
                                'tab_title' => 'tr_melis_Plugins_Template',
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
                            'melis_commerce_plugin_category_product_list_tree_config' => [
                                'tab_title' => 'tr_meliscommerce_plugin_category_product_list_category',
                                'tab_icon'  => 'fa fa-book',
                                'tab_form_layout' => 'MelisCommerce/category-product-list-tree-config',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_include_sub_category_products',
                                            'type' => 'Select',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_category_product_list_include_sub_cat_prds',
                                                'tooltip' => 'tr_meliscommerce_plugin_category_product_list_include_sub_cat_prds tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => [
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                ],
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_include_sub_category_products',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_cat_col_name',
                                            'type' => 'Select',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_column_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_column_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => [
                                                    'cat_id' => 'tr_meliscommerce_plugin_category_product_list_cat_order_cat_id',
                                                    'catt_name' => 'tr_meliscommerce_plugin_category_product_list_cat_order_catt_name',
                                                    'cat_order' => 'tr_meliscommerce_plugin_category_product_list_cat_order_cat_order',
                                                    'cat_date_creation' => 'tr_meliscommerce_plugin_category_product_list_cat_order_cat_date_creation',
                                                ],
                                            ],
                                            'attributes' => [
                                                'id' => 'm_cat_col_name',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_cat_order',
                                            'type' => 'Select',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => [
                                                    'ASC' => 'tr_meliscommerce_general_common_ascending',
                                                    'DESC' => 'tr_meliscommerce_general_common_descending',
                                                ],
                                            ],
                                            'attributes' => [
                                                'id' => 'm_cat_order',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'm_cat_col_name' => [
                                        'name'     => 'm_cat_col_name',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_column_order',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_cat_order' => [
                                        'name'     => 'm_cat_order',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_order',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_include_sub_category_products' => [
                                        'name'     => 'm_include_sub_category_products',
                                        'required' => false,
                                        'validators' => [
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                ]
                            ],
                            'melis_commerce_plugin_category_product_list_product_config' => [
                                'tab_title' => 'tr_meliscommerce_plugin_category_product_list_product',
                                'tab_icon'  => 'fa icon-shippment',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_country_id',
                                            'type' => 'EcomPluginPriceCountriesSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_category_product_list_country',
                                                'tooltip' => 'tr_meliscommerce_plugin_category_product_list_country tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_country_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_prd_col_name',
                                            'type' => 'Select',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_column_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_column_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => [
                                                    'prd_id' => 'tr_meliscommerce_products_plugins_prd_id',
                                                    'prd_reference' => 'tr_meliscommerce_products_plugins_prd_reference',
                                                    'ptxt_field_short' => 'tr_meliscommerce_products_plugins_ptxt_field_short',
                                                    'pcat_order' => 'tr_meliscommerce_products_plugins_pcat_order',
                                                    'prd_date_creation' => 'tr_meliscommerce_products_plugins_prd_date_creation',
                                                ],
                                            ],
                                            'attributes' => [
                                                'id' => 'm_prd_col_name',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_prd_order',
                                            'type' => 'Select',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => [
                                                    'ASC' => 'tr_meliscommerce_general_common_ascending',
                                                    'DESC' => 'tr_meliscommerce_general_common_descending',
                                                ],
                                            ],
                                            'attributes' => [
                                                'id' => 'm_prd_order',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_prd_limit',
                                            'type' => 'MelisText',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_limit',
                                                'tooltip' => 'tr_meliscommerce_general_common_limit tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_prd_limit',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'm_country_id' => [
                                        'name'     => 'm_country_id',
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
                                    'm_prd_col_name' => [
                                        'name'     => 'm_prd_col_name',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_column_order',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_prd_order' => [
                                        'name'     => 'm_prd_order',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_order',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_prd_limit' => [
                                        'name'     => 'm_prd_limit',
                                        'required' => false,
                                        'validators' => [
                                            [
                                                'name' => 'Digits',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscommerce_plugin_limit_num_only',
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