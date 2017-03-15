<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCategoryListProductsPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategoryListProductsPlugin\showlist',
                        'sort' => array(
                                'm_col_name' => 'prd_id',
                                'm_order' => 'DESC',
                        ),
                        // optional, filtering
                        'm_box_filter_search' => '',
                        'm_box_filter_categories_ids_selected' => array(),
                        'm_box_filter_price_min' => null,
                        'm_box_filter_price_max' => null,
                        'm_box_filter_attribute_values_ids_selected' => array(),
                        // optional, if found will add a pagination object
                        'pagination' => array(
                            'm_pag_current' => 1,
                            'm_pag_nb_per_page' => 9,
                            'm_pag_nb_page_before_after' => 3
                        ),
                    ),
                    'back' => array(
                    
                    ),
                ),
            ),
        ),
    )
);