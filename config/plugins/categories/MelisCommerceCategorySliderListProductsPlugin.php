<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCategorySliderListProductsPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-slider-products',
                        
                        // arrays of category id selected
                        'm_box_filter_categories_ids_selected' => array(),
                        // retrieve specific categories by country id, by default the country id is set to null
                        'm_box_filter_country' => null,
                        // type of documents to fetch
                        'm_box_filter_docs' => array(),
                        // field type to fetch
                        'm_box_filter_field_type' => array('TITLE'),
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