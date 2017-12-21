<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProductListPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerce/product-list' ),
                        'id' => 'productList',
                        'm_box_category_tree_ids_selected' => array(),
                        
                        'sorter' => array(
                            /**
                             * Sorting, by default it will retrieve
                             * products by Product Reference in Ascending order
                             */
                            'm_col_name' => 'prd_id',
                            'm_order'   => 'ASC',
                        ),
                        'm_box_filter_price_column' => null,// X

                        // List filters
                        'filters' => array(
                            //search box filter
                            'm_box_product_search' => '',
                            /**
                             * Product text type to be searched for,
                             * ex. TITLE, DESCRIPTION etc...
                             * if empty all types are included for search criteria
                             */
                            'm_box_product_field_type' => array(),
                            //array of categories selected
                            'm_box_category_tree_ids_selected' => array(),
                            // minimum price
                            'm_box_product_price_min' => null,
                            // maximum price
                            'm_box_product_price_max' => null,
                            // array of attribute ids selected
                            'm_box_product_attribute_values_ids_selected' => array(),
                            // country id
                            'm_box_product_country' => null,
                            // language filter
                            'm_box_product_lang' => null,
                            // product validity
                            'm_box_product_only_valid' => false,
                        ),
                        
                        // pagination config
                        'pagination' => array(
                            'm_page_current' => 1,
                            'm_page_nb_per_page' => 10,
                            'm_page_nb_page_before_after' => 3,

                        ),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'PRODUCTS',
                            'title' => 'tr_meliscommerce_products_Products'
                        ),
                        'name' => 'tr_meliscommerce_plugin_product_list_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceProductListPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_product_list_description',
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
                            'melis_commerce_plugin_product_list_config' => array(
                                'tab_title' => 'tr_meliscommerce_general_common_configuration',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_template_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_col_name',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_list_column_to_order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'prd_reference' => 'tr_meliscommerce_plugin_product_list_column_product_ref',
                                                    'ptxt_field_short' => 'tr_meliscommerce_plugin_product_list_column_product_text_title',
                                                    'price' => 'tr_meliscommerce_plugin_product_list_column_product_price',
                                                ),
                                                'tooltip' => 'tr_meliscommerce_plugin_product_list_column_to_order_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_col_name',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_order',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_list_result_order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'ASC' => 'Ascending',
                                                    'DESC' => 'Descending',
                                                 ),
                                                'tooltip' => 'tr_meliscommerce_plugin_product_list_result_order_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_order',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_country',
                                            'type' => 'EcomPluginCountriesSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_category_product_list_country',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_product_list_country_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_product_country',
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
                                    'm_col_name' => array(
                                        'name'     => 'm_col_name',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_order' => array(
                                        'name'     => 'm_order',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_box_product_country' => array(
                                        'name'     => 'm_box_product_country',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_product_list_no_country',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                            'melis_commerce_plugin_product_list_price_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_product_list_price_config',
                                'tab_icon'  => 'fa fa-money',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_filter_price_column',
                                            'type' => 'select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_price_column',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_product_price_column_tooltip',
                                                'value_options' => array(
                                                    'price_net' => 'tr_meliscommerce_plugin_product_price_column_price_net',
                                                    'price_gross' => 'tr_meliscommerce_plugin_product_price_column_price_gross',
                                                    'price_vat_price' => 'tr_meliscommerce_plugin_product_price_column_price_vat_price',
                                                    'price_other_tax_price' => 'tr_meliscommerce_plugin_product_price_column_price_other_tax_price',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'price_type_id',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_price_min',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_price_range_min',
                                                'tooltip' => 'tr_meliscommerce_plugin_product_price_column_min_price_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_product_price_min',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_price_max',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_price_range_max',
                                                'tooltip' => 'tr_meliscommerce_plugin_product_price_column_max_price_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_product_price_max',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'melis_commerce_plugin_product_list_tree_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_product_list_category_config',
                                'tab_icon'  => 'fa fa-book',
                                'tab_form_layout' => 'MelisCommerce/product-list-tree-config',
                            ),
                            'melis_commerce_plugin_product_list_text_types_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_product_list_product_search_config',
                                'tab_icon'  => 'fa fa-search',
                                'tab_form_layout' => 'MelisCommerce/product-list-text-types-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_search',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_list_search',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_product_search',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'melis_commerce_plugin_product_list_attributes_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_product_list_attributes_config',
                                'tab_icon'  => 'fa fa-cubes',
                                'tab_form_layout' => 'MelisCommerce/product-list-attributes-config',
                            ),
                            'melis_commerce_plugin_product_list_pagination_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_product_list_pagination_config',
                                'tab_icon'  => 'fa fa-fast-forward',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_page_current',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_list_default_page_number',
                                                'tooltip' => 'tr_meliscommerce_plugin_product_list_pagination_default_page_number_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_page_current',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_page_nb_per_page',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_list_product_per_page',
                                                'tooltip' => 'tr_meliscommerce_plugin_product_list_product_per_page_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_page_nb_per_page',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_page_nb_page_before_after',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_list_number_of_pages',
                                                'tooltip' => 'tr_meliscmsnews_plugin_pagination_nbPageBeforeAfter tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_page_nb_page_before_after',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    )
                                )
                            ),
                        )
                    ),
                ),
            ),
        ),
     ),
);