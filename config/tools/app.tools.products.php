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
                'meliscommerce_products' => [
                    
                    'table' => [
                        'target' => '#tableProductVariantList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComVariantList/renderProductsVariantData',
                        'dataFunction' => 'initProductVariant',
                        'ajaxCallback' => 'melisCommerce.initTooltipVarTable();checkVarStatus();',
                        'filters' => [
                            'left' => [
                                'productvariant-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-limit'
                                ],
                            ],
                            'center' => [
                                'productvariant-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-search'
                                ],
                            ],
                            'right' => [
                                /* 'productvariant-grid' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-grid'
                                ],
                                'productvariant-list' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-list'
                                ], */
                                'productvariant-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-refresh'
                                ],
                            ],
                        ],
                        'columns' => [
                            /* 'select' => [
                                'text' => '',
                                'css' => ['width' => '3%', 'padding-right' => '0'],
                                'sortable' => false,                               
                            ], */
                            'var_id' => [
                                'text' => 'tr_meliscommerce_variant_list_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'var_main_variant' => [
                                'text' => 'tr_meliscommerce_variant_main_information_main_variant_label',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,                            
                            ],
                            'var_image' => [
                                'text' => 'tr_meliscommerce_variant_list_image',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'var_status' => [
                                'text' => 'tr_meliscommerce_variant_main_information_status_label',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'var_sku' => [
                                'text' => 'tr_meliscommerce_variant_list_sku',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'var_attributes' => [
                                'text' => 'tr_meliscommerce_variant_main_attributes_col',
                                'css' => ['width' => '40%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                    
                        ],
                        'searchables' => [],
                        'actionButtons' => [
                            'updateStatus' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComVariantList',
                                'action' => 'render-tool-variant-action-update-status',
                            ],
                            'duplicate' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-duplicate-variant-button',
                            ],
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComVariantList',
                                'action' => 'render-tool-variant-action-edit',
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComVariantList',
                                'action' => 'render-tool-variant-action-delete',
                            ],
                        ],
                    ],
                ],
                'meliscommerce_products_list' => [
                    'table' => [
                        'target' => '#tableProductList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComProductList/getProductsList',
                        'dataFunction' => '',
                        'ajaxCallback' => 'melisCommerce.initTooltipTable();',
                        'filters' => [
                            'left' => [
                                /* 'product-list-table-filter-bulk' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-bulk'
                                ], */
                                'product-list-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-limit'
                                ],
                            ],
                            
                            'center' => [
                                'product-list-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-search'
                                ],
                                /* 'product-list-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-search'
                                ],
                                'product-list-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-limit'
                                ], */
                            ],
                            
                            'right' => [
                                /* 'product-list-table-filter-grid-view' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-grid-view'
                                ],
                                'product-list-table-filter-list-view' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-list-view'
                                ], */
                                'product-list-table-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-refresh'
                                ],
                            ],
                        ],
                        
                        'columns' => [
                            /* 'product_table_checkbox' => [
                                'text' => '',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => false,
                            ], */
                            'prd_id' => [
                                'text' => 'tr_meliscommerce_product_list_col_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

                            'prd_status' => [
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

                            'product_image' => [
                                'text' => 'tr_meliscommerce_product_list_col_image',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'product_name' => [
                                'text' => 'tr_meliscommerce_product_list_col_name',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            
                            'product_categories' => [
                                'text' => 'tr_meliscommerce_product_list_col_categories',
                                'css' => ['width' => '30%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                        ],
                        
                        'searchables' => [],
                        'actionButtons' => [
                            'duplicate' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-duplicate-product-button',
                            ],
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-edit'
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-delete'
                            ],
                        ],
                    ]
                ],
                'forms' => [
                    
                ],
            ],
        ],
    ],
];