<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(   
                'meliscommerce_categories' => array(   
                    'conf' => array(
                        'title' => 'tr_meliscommerce_categories_tool_title',
                        'id' => 'id_meliscommerce_categories',
                    ),
                    'table' => array(
                        // table ID
                        'target' => '#categoryProductListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCategory/getCategoryProductList',
                        'dataFunction' => 'initCategoryProducts',
                        'ajaxCallback' => 'initCategoryProductsImgs',
                        'filters' => array(
                            'left' => array(
                                'category-product-list-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-limit',
                                ),
//                                 'category-product-list-search' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComCategory',
//                                     'action' => 'render-category-product-list-filter-serach',
//                                 ),
                            ),
                            'center' => array(
                                
                            ),
                            'right' => array(
                                'category-product-list-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'pcat_order' => array(
                                'text' => 'tr_meliscommerce_categories_common_label_order',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'prd_id' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_id',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'prd_img' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_img',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'prd_status' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_status',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'prd_name' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_name',
                                'css' => array('width' => '30%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'prd_date_creation' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_date_creation',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                        ),
                        // define what columns can be used in searching
                        'searchables' => array(),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategory',
                                'action' => 'render-category-product-list-view',
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategory',
                                'action' => 'render-category-product-list-remove',
                            ),
                        )
                    ),
                ), // end 
            ),
        ),
    ),
);