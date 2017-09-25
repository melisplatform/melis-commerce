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
                        
                        /**
                         * The country id where the products price retrieve, 
                         * by default price will get from general price with default currency
                         */
                        'm_country_id' => null,
                        /**
                         * Options for including products of the Category
                         * sub-categories products
                         */
                        'm_include_sub_category_products' => false,
                        
                        // Arrays of category id selected
                        'm_category_ids' => array(),
                        // Category sorting option
                        'm_category_sorter' => array(
                            /**
                             * Sorting, by default it will retrieve
                             * category by Category translations in Ascending order
                             */
                            'm_cat_col_name' => 'catt_name',
                            'm_cat_order'   => 'ASC',
                        ),
                        // Product sorting option
                        'm_product_sorter' => array(
                            /**
                             * Sorting, by default it will retrieve
                             * product by Product translations in Ascending order
                             */
                            'm_prd_col_name' => 'pcat_order',
                            'm_prd_order'   => 'ASC',
                        ),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CATEGORIES',
                            'title' => 'tr_MelisFrontSubcategoryPageCategory_Title'
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
                        'js_initialization' => array(),
                        'modal_form' => array(
                            'melis_commerce_plugin_category_product_list_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_category_product_list_config',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/category-product-list-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_country_id',
                                            'type' => 'EcomPluginCountriesSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_category_product_list_country',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_country_id',
                                                'class' => 'form-control',
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
                                                'label' => 'Column order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'cat_id' => 'Category Id',
                                                    'cat_date_creation' => 'Category Date Created',
                                                    'catt_name' => 'Category Text',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_cat_col_name',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_cat_order',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'Order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'ASC' => 'Ascending',
                                                    'DESC' => 'Descending',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_cat_order',
                                                'class' => 'form-control',
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
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select a order column name',
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
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select a order',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                            'melis_commerce_plugin_category_product_list_product_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_category_product_list_product',
                                'tab_icon'  => 'fa icon-shippment',
                                'tab_form_layout' => 'MelisCommerce/category-product-list-product-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_prd_col_name',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'Column order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'prd_id' => 'Product Id',
                                                    'prd_reference' => 'Product Reference',
                                                    'ptxt_field_short' => 'Product Short Text',
                                                    'pcat_order' => 'Category Product Order',
                                                    'prd_date_creation' => 'Product Date Created',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_prd_col_name',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_prd_order',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'Order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'ASC' => 'Ascending',
                                                    'DESC' => 'Descending',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_prd_order',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_prd_col_name' => array(
                                        'name'     => 'm_prd_col_name',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select a order column name',
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
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please select a order',
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