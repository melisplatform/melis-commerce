<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCategoryListProductsPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-product-list',
                
                        // Sorting, by default it will retrieve products by id in descending order
                        'm_col_name' => 'prd_id',
                        'm_order'   => 'DESC',
                
                        // List filters
                        
                        //search box filter
                        'm_box_filter_search' => '',
                        //field type to be searched for, ex. ptxt_field_short / ptxt_field_long
                        'm_box_filter_field_type' => null,
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
                        'm_box_filter_only_valid' => true,
                
                        // pagination config
                        'm_pag_current' => 1,
                        'm_pag_nb_per_page' => null,
                        'm_pag_nb_page_before_after' => 3,
                        'priceColumn' => 'price_net',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                            ),
                        ),
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);