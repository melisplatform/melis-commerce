<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCategoryProductListPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCategory/category-product-list'),
                        'id' => 'categoryProductList',
                        // Category option
                        'm_category_option' => array(
                            // Arrays of category id selected
                            'm_category_ids' => array(),
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
                            'm_include_sub_category_products' => false,
                        ),
                        // Product option
                        'm_product_option' => array(
                            /**
                             * The country id where the products price retrieve,
                             * by default price will get from general price with default currency
                             */
                            'm_country_id' => null,
                            /**
                             * Sorting, by default it will retrieve
                             * product by Product translations in Ascending order
                             */
                            'm_prd_col_name' => 'pcat_order',
                            'm_prd_order'   => 'ASC',
                            // Include products with no prices
                            /* 'm_prd_include_price' => true */
                            'm_prd_limit'   => null,
                        ),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CATEGORIES',
                            'title' => 'tr_meliscommerce_plugin_sub_categories_title'
                        ),
                        'name' => 'tr_meliscommerce_plugin_category_product_list_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCategoryProductListPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_category_product_list_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                            ),
                        ),
                        'modal_form' => array(
                            'melis_commerce_plugin_category_product_list_config' => array(
                                'tab_title' => 'tr_melis_Plugins_Template',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'tooltip' => 'tr_melis_Plugins_Template tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'template_path' => array(
                                        'name'     => 'template_path',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                            'melis_commerce_plugin_category_product_list_tree_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_category_product_list_category',
                                'tab_icon'  => 'fa fa-book',
                                'tab_form_layout' => 'MelisCommerce/category-product-list-tree-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_cat_col_name',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_column_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_column_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'cat_id' => 'tr_meliscommerce_plugin_category_product_list_cat_order_cat_id',
                                                    'catt_name' => 'tr_meliscommerce_plugin_category_product_list_cat_order_catt_name',
                                                    'cat_order' => 'tr_meliscommerce_plugin_category_product_list_cat_order_cat_order',
                                                    'cat_date_creation' => 'tr_meliscommerce_plugin_category_product_list_cat_order_cat_date_creation',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_cat_col_name',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_cat_order',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'ASC' => 'tr_meliscommerce_general_common_ascending',
                                                    'DESC' => 'tr_meliscommerce_general_common_descending',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_cat_order',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_include_sub_category_products',
                                            'type' => 'Checkbox',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_category_product_list_include_sub_cat_prds',
                                                'tooltip' => 'tr_meliscommerce_plugin_category_product_list_include_sub_cat_prds tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => array(
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_include_sub_category_products',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_cat_col_name' => array(
                                        'name'     => 'm_cat_col_name',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_column_order',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_cat_order' => array(
                                        'name'     => 'm_cat_order',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_order',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_include_sub_category_products' => array(
                                        'name'     => 'm_include_sub_category_products',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                            'melis_commerce_plugin_category_product_list_product_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_category_product_list_product',
                                'tab_icon'  => 'fa icon-shippment',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_country_id',
                                            'type' => 'EcomPluginPriceCountriesSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_category_product_list_country',
                                                'tooltip' => 'tr_meliscommerce_plugin_category_product_list_country tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_country_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_prd_col_name',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_column_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_column_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'prd_id' => 'tr_meliscommerce_products_plugins_prd_id',
                                                    'prd_reference' => 'tr_meliscommerce_products_plugins_prd_reference',
                                                    'ptxt_field_short' => 'tr_meliscommerce_products_plugins_ptxt_field_short',
                                                    'pcat_order' => 'tr_meliscommerce_products_plugins_pcat_order',
                                                    'prd_date_creation' => 'tr_meliscommerce_products_plugins_prd_date_creation',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_prd_col_name',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_prd_order',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_order',
                                                'tooltip' => 'tr_meliscommerce_general_common_order tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'ASC' => 'tr_meliscommerce_general_common_ascending',
                                                    'DESC' => 'tr_meliscommerce_general_common_descending',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_prd_order',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_prd_limit',
                                            'type' => 'MelisText',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_limit',
                                                'tooltip' => 'tr_meliscommerce_general_common_limit tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_prd_limit',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_country_id' => array(
                                        'name'     => 'm_country_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_category_product_list_no_country',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_prd_col_name' => array(
                                        'name'     => 'm_prd_col_name',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_column_order',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_prd_order' => array(
                                        'name'     => 'm_prd_order',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_no_order',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                        )
                    ),
                ),
            ),
        ),
     ),
);