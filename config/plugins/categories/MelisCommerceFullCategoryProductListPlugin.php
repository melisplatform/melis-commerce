<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceFullCategoryProductListPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCategory/full-category-product-list'),
                        'id' => 'fullCategoryProductList',
                        
                        'sorter' => array(
                            /**
                             * Sorting, by default it will retrieve
                             * products by Product Reference in Ascending order
                             */
                            'm_col_name' => 'prd_id',
                            'm_order'   => 'ASC',
                        ),
                        
                        //'priceColumn' => 'price_net',// X
                        
                        // List filters
                        'filters' => array(
                            //search box filter
                            'm_box_filter_search' => '',
                            /**
                             * Product text type to be searched for,
                             * ex. TITLE, DESCRIPTION etc...
                             * if empty all types are included for search criteria
                             */
                            'm_box_filter_field_type' => array(),
                            //array of categories selected
                            'm_box_filter_categories_ids_selected' => array(),
                            // minimum price
                            'm_box_filter_price_min' => null,
                            // maximum price
                            'm_box_filter_price_max' => null,
                            // array of attribute ids selected
                            'm_box_filter_attribute_values_ids_selected' => array(),
                            // country id
                            'm_box_filter_country' => null,
                            // language filter
                            'm_box_filter_lang' => null,
                            // product validity
                            'm_box_filter_only_valid' => false,
                        ),
                        
                        // pagination config
                        'pagination' => array(
                            'm_pag_current' => 1,
                            'm_pag_nb_per_page' => 10,
                            'm_pag_nb_page_before_after' => 3,
                        ),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CATEGORIES',
                            'title' => 'tr_MelisFrontSubcategoryPageCategory_Title'
                        ),
                        'name' => 'tr_meliscommerce_plugin_full_category_product_list_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceFullCategoryProductListPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_full_category_product_list_description',
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
                            'melis_commerce_plugin_full_category_product_list_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_full_category_product_list_config',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/full-category-product-list-config',
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
                                            'name' => 'm_col_name',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'Default Column order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'prd_reference' => 'Product Reference',
                                                    'ptxt_field_short' => 'Product Text Title',
                                                    'price' => 'Product Price',
                                                ),
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
                                                'label' => 'Default Column order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'ASC' => 'Ascending',
                                                    'DESC' => 'Descending',
                                                 ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_order',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_filter_price_min',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'Min Price',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_filter_price_min',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_filter_price_max',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'Max Price',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_filter_price_max',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_filter_country',
                                            'type' => 'EcomPluginCountriesSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_category_product_list_country',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_filter_country',
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
                                    'm_box_filter_country' => array(
                                        'name'     => 'm_box_filter_country',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_full_category_product_list_no_country',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                            'melis_commerce_plugin_full_category_product_list_tree_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_full_category_product_list_category',
                                'tab_icon'  => 'fa fa-book',
                                'tab_form_layout' => 'MelisCommerce/full-category-product-list-tree-config',
                            ),
                            'melis_commerce_plugin_full_category_product_list_pagination_config' => array(
                                'tab_title' => 'Pagination',
                                'tab_icon'  => 'fa fa-fast-forward',
                                'tab_form_layout' => 'MelisCommerce/full-category-product-list-pagination-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_pag_current',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'Default page number to show',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_pag_current',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_pag_nb_per_page',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'Number of Products per page',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_pag_nb_per_page',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_pag_nb_page_before_after',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'Number of pages in paginator before and after the current page',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_pag_nb_page_before_after',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    )
                                )
                            ),
                            'melis_commerce_plugin_full_category_product_list_text_types_config' => array(
                                'tab_title' => 'Product Search',
                                'tab_icon'  => 'fa fa-search',
                                'tab_form_layout' => 'MelisCommerce/full-category-product-list-text-types-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_filter_search',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'Search',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_filter_search',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'melis_commerce_plugin_full_category_product_list_attributes_config' => array(
                                'tab_title' => 'Attributes',
                                'tab_icon'  => 'fa fa-cubes',
                                'tab_form_layout' => 'MelisCommerce/full-category-product-list-attributes-config',
                            ),
                        )
                    ),
                ),
            ),
        ),
     ),
);