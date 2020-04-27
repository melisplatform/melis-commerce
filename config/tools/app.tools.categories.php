<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'plugins' => [
        'meliscommerce' => [
            'tools' => [
                'meliscommerce_categories' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_categories_tool_title',
                        'id' => 'id_meliscommerce_categories',
                    ],
                    'table' => [
                        // table ID
                        'target' => '#categoryProductListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCategory/getCategoryProductList',
                        'dataFunction' => 'initCategoryProducts',
                        'ajaxCallback' => 'initCategoryProductsImgs();',
                        'filters' => [
                            'left' => [],
                            'center' => [],
                            'right' => [
                                'category-product-list-export' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-export',
                                ],
                                'category-product-list-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'pcat_order' => [
                                'text' => '<i class="fa fa-plus"> </i> ',
                                'css' => ['width' => '1%', 'visible' => false],
                                'sortable' => true,
                            ],
                            'prd_id' => [
                                'text' => 'tr_meliscommerce_categories_category_prd_id',
                                'css' => ['width' => '1%'],
                                'sortable' => false,
                            ],
                            'prd_img' => [
                                'text' => 'tr_meliscommerce_categories_category_prd_img',
                                'css' => ['width' => '5%'],
                                'sortable' => false,
                            ],
                            'prd_status' => [
                                'text' => 'tr_meliscommerce_categories_category_prd_status',
                                'css' => ['width' => '1%'],
                                'sortable' => false,
                            ],
                            'prd_name' => [
                                'text' => 'tr_meliscommerce_categories_category_prd_name',
                                'css' => ['width' => '30%'],
                                'sortable' => false,
                            ],
                            'prd_date_creation' => [
                                'text' => 'tr_meliscommerce_categories_category_prd_date_creation',
                                'css' => ['width' => '1%'],
                                'sortable' => false,
                            ],
                        ],
                        // define what columns can be used in searching
                        'searchables' => [],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategory',
                                'action' => 'render-category-product-list-view',
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategory',
                                'action' => 'render-category-product-list-remove',
                            ],
                        ]
                    ],
                ], // end
            ],
        ],
    ],
];