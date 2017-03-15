<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array( 
                'MelisCommerceCategorySliderListProductsPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-slider-products',
                    
                        // Sorting
                        'm_col_name' => 'prd_id',
                        'm_order'   => 'DESC',
                    
                        // filtering
                        'm_box_filter_search' => '',
                        'm_box_filter_field_type' => null,
                        'm_box_filter_categories_ids_selected' => array(),
                        'm_box_filter_price_min' => null,
                        'm_box_filter_price_max' => null,
                        'm_box_filter_attribute_values_ids_selected' => array(),
                        'm_box_filter_country' => null,
                        'm_box_filter_lang' => null,
                        'm_box_filter_only_valid' => true,
                    
                        // pagination
                        'm_pag_current' => 1,
                        'm_pag_nb_per_page' => 10,
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
                
                // Category products display
                'MelisCommerceCategoryListProductsPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-product-list',
                        
                        // Sorting
                        'm_col_name' => 'prd_id',
                        'm_order'   => 'DESC',                        
                        
                        // filtering
                        'm_box_filter_search' => '',
                        'm_box_filter_field_type' => null,
                        'm_box_filter_categories_ids_selected' => array(),
                        'm_box_filter_price_min' => null,
                        'm_box_filter_price_max' => null,
                        'm_box_filter_attribute_values_ids_selected' => array(),
                        'm_box_filter_country' => null,
                        'm_box_filter_lang' => null,
                        'm_box_filter_only_valid' => true,
                        
                        // pagination
                        'm_pag_current' => 1,
                        'm_pag_nb_per_page' => 10,
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
                
                // Category Filter
                'MelisCommerceFilterMenuCategoryListPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-list-filter',
                        'parent_category_id' => NULL,
                        'm_box_filter_categories_ids_selected' => array(),
                        // only active categories will be filtered
                        'm_box_filter_categories_active' => 1,
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
                
                // Search Filter
                'MelisCommerceFilterMenuProductSearchBoxPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/products-search-box',
                
                        // filtering
                        'm_box_filter_search' => '',
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
                
                // Price Filter
                'MelisCommerceFilterMenuPriceValueBoxPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-price-filter',
                        
                        // filtering
                        'm_box_filter_categories_ids_selected' => array(),
                        'm_box_filter_price_min' => null,
                        'm_box_filter_price_max' => null,
                        
                        // Optional parameters
                        'type' => 'variant',
                        // price type are : price_net, price_gross
                        'price_type' => 'price_net',
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
                
                // Attributes Filter
                'MelisCommerceFilterMenuAttributeValueBoxPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-attribute-filter', 
                        'attribute_id' => null,
                        // filtering
                        'm_box_filter_attribute_values_ids_selected' => array(),
                    ),
                    'melis' => array(),
                ),
             ),
        ),
     ),
);