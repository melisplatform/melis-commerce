<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceFilterMenuProductSearchBoxPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/products-search-box',
                
                        // filtering
                        // Search box filter
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
            ),
        ),
     ),
);