<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceFilterMenuCategoryListPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-list-filter',
                        // base parent id of the category
                        'parent_category_id' => NULL,
                        // returns selected category ids
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
            ),
        ),
     ),
);