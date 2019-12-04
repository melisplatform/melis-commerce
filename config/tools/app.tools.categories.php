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
                        'ajaxCallback' => 'initCategoryProductsImgs();',
                        'filters' => array(
                            'left' => array(),
                            'center' => array(),
                            'right' => array(
                                'category-product-list-export' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-export',
                                ),
                                'category-product-list-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'pcat_order' => array(
                                'text' => '<i class="fa fa-plus"> </i> ',
                                'css' => array('width' => '1%', 'visible' => false),
                                'sortable' => true,
                            ),
                            'prd_id' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_id',
                                'css' => array('width' => '1%'),
                                'sortable' => false,
                            ),
                            'prd_img' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_img',
                                'css' => array('width' => '5%'),
                                'sortable' => false,
                            ),
                            'prd_status' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_status',
                                'css' => array('width' => '1%'),
                                'sortable' => false,
                            ),
                            'prd_name' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_name',
                                'css' => array('width' => '30%'),
                                'sortable' => false,
                            ),
                            'prd_date_creation' => array(
                                'text' => 'tr_meliscommerce_categories_category_prd_date_creation',
                                'css' => array('width' => '1%'),
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