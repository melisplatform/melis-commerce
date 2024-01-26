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
                'meliscommerce_coupon_list' => [
                    'table' => [
                        'target' => '#tableCouponList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCouponList/getCouponListData',
                        'dataFunction' => 'initMelisCommerceCouponTbl',
                        'ajaxCallback' => 'initCheckUsedCoupon()',
                        'filters' => [
                            'left' => [
                                'coupon-list-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCouponList',
                                    'action' => 'render-coupon-list-content-filter-limit'
                                ],
                            ],
                        
                            'center' => [
                                'order-list-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCouponList',
                                    'action' => 'render-coupon-list-content-filter-search'
                                ],
                            ],
                        
                            'right' => [
                                'coupon-list-table-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCouponList',
                                    'action' => 'render-coupon-list-content-filter-refresh'
                                ],
                            ],
                        ],
                        
                        'columns' => [
                            'coup_id' => [
                                'text' => 'tr_meliscommerce_coupon_list_col_id',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'coup_status' => [
                                'text' => 'tr_meliscommerce_coupon_list_col_status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        
                            'coup_code' => [
                                'text' => 'tr_meliscommerce_coupon_list_col_code',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        
                            'coup_percentage' => [
                                'text' => '<i class="fa fa-percent fa-lg commerce-coupon-percent"></i>',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            
                            'coup_discount_value' => [
                                'text' => 'tr_meliscommerce_coupon_page_table_col_price',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],

                            'coup_current_use_number' => [
                                'text' => 'tr_meliscommerce_coupon_list_col_used',
                                'css' => ['width' => '30%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                        ],

                        'searchables' => [],
                        'actionButtons' => [
                            'info' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCouponList',
                                'action' => 'render-coupon-list-content-action-info'
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCouponList',
                                'action' => 'render-coupon-list-content-action-delete'
                            ],
                        ],
                    ],
                ],
                'meliscommerce_coupon' => [
                    'table' => [
                        'target' => '#clientList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getCouponClientData',
                        'dataFunction' => 'initCouponClient',
                        'ajaxCallback' => 'initCouponClientTable()',
                        'filters' => [
                            'left' => [
                                'coupon-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ],
                            ],

                            'center' => [
                                'order-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ],
                            ],

                            'right' => [
                                'coupon-table-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ],
                            ],
                        ],

                        'columns' => [
                            'cli_id' => [
                                'text' => 'tr_meliscommerce_coupon_page_table_col_id',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

                            'cli_name' => [
                                'text' => 'tr_meliscommerce_client_account_name',
                                'css' => ['width' => '30%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

//                            'cper_name' => [
//                                'text' => 'tr_meliscommerce_coupon_page_table_col_name_last',
//                                'css' => ['width' => '15%', 'padding-right' => '0'],
//                                'sortable' => true,
//                            ],
//
//                            'cper_email' => [
//                                'text' => 'tr_meliscommerce_coupon_page_table_col_email',
//                                'css' => ['width' => '15%', 'padding-right' => '0'],
//                                'sortable' => true,
//                            ],
                            'ccomp_name' => [
                                'text' => 'tr_meliscommerce_coupon_page_table_col_company',
                                'css' => ['width' => '25%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],

                        'searchables' => [
                            'cli_id',
                            'cli_name',
                            'ccomp_name'
                        ],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-edit-client',
                            ],
                            'add' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-assign'
                            ],
                        ],
                    ],
                ],
                'meliscommerce_coupon_assigned' => [
                    'table' => [
                        'target' => '#clientListAssigned',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getAssignedCouponClientData',
                        'dataFunction' => 'initCouponClient',
                        'ajaxCallback' => 'initCheckClientUsedCoupon()',
                        'filters' => [
                            'left' => [
                                'coupon-table-filter-limit-assigned' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ],
                            ],

                            'center' => [
                                'order-table-filter-search-assigned' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ],
                            ],

                            'right' => [
                                'coupon-table-filter-refresh-assigned' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ],
                            ],
                        ],

                        'columns' => [
                            'cli_id' => [
                                'text' => 'tr_meliscommerce_coupon_page_table_col_id',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_name' => [
                                'text' => 'tr_meliscommerce_client_account_name',
                                'css' => ['width' => '30%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

//                            'cper_name' => [
//                                'text' => 'tr_meliscommerce_coupon_page_table_col_name_last',
//                                'css' => ['width' => '15%', 'padding-right' => '0'],
//                                'sortable' => true,
//                            ],
//
//                            'cper_email' => [
//                                'text' => 'tr_meliscommerce_coupon_page_table_col_email',
//                                'css' => ['width' => '15%', 'padding-right' => '0'],
//                                'sortable' => true,
//                            ],
                            'ccomp_name' => [
                                'text' => 'tr_meliscommerce_coupon_page_table_col_company',
                                'css' => ['width' => '25%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],

                        'searchables' => [
                            'cli_id',
                            'cli_name',
                            'ccomp_name'
                        ],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-edit-client',
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-delete'
                            ],
                        ],
                    ],
                ],
                'meliscommerce_coupon_order' => [
                    'table' => [
                        'target' => '#tableOrderList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderList/getOrderListData',
                        'dataFunction' => 'initCouponOrder',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'order-list-table-filter-limit-order' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-limit'
                                ],
                                'order-list-table-filter-date-order' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-date'
                                ],
                            ],

                            'center' => [
                                'order-list-table-filter-search-order' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-search'
                                ],
                                'order-list-table-filter-bulk-order' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-bulk'
                                ],
                            ],

                            'right' => [
                                'order-list-table-filter-refresh-order' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-refresh'
                                ],
                            ],
                        ],

                        'columns' => [
                            'ord_id' => [
                                'text' => 'tr_meliscommerce_order_list_col_id',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'ord_reference' => [
                                'text' => 'tr_meliscommerce_order_list_col_reference',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

                            'ord_status' => [
                                'text' => 'tr_meliscommerce_order_list_col_status',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

                            'products' => [
                                'text' => '<i class="fa icon-shippment fa-lg"></i>',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],

                            'price' => [
                                'text' => 'tr_meliscommerce_coupon_page_table_col_price',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                
                            /* 'ccomp_name' => [
                                'text' => 'tr_meliscommerce_order_list_col_company',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => false,
                            ], */
                
                            'civt_min_name' => [
                                'text' => 'tr_meliscommerce_order_list_col_civility',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                
                            'cper_firstname' => [
                                'text' => 'tr_meliscommerce_order_list_col_firstname',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                
                            'cper_name' => [
                                'text' => 'tr_meliscommerce_order_list_col_name',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                
                            'ord_date_creation' => [
                                'text' => 'tr_meliscommerce_order_list_col_date',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],
                
                        'searchables' => [],
                        'actionButtons' => [
                            'info' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-content-action-info'
                            ],
                        ],
                
                    ],
                ],
                'meliscommerce_coupon_product_assigned' => [
                    'table' => [
                        'target' => '#productListAssigned',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getCouponProductData',
                        'dataFunction' => 'initMelisCouponProduct',
                        'ajaxCallback' => 'melisCommerce.initTooltipTable()',
                        'filters' => [
                            'left' => [
                                'coupon-table-filter-limit-assigned-product' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ],
                            ],
                
                            'center' => [
                                'order-table-filter-search-assigned-product' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ],
                            ],
                
                            'right' => [
                                'coupon-table-filter-refresh-assigned-product' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ],
                            ],
                        ],
                
                        'columns' => [
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

                            /* 'product_image' => [
                                'text' => 'tr_meliscommerce_product_list_col_image',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ], */
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
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-edit'
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-delete-assigned-product'
                            ],
                        ],
                    ],
                ],
                'meliscommerce_coupon_product_assign' => [
                    'table' => [
                        'target' => '#productList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getCouponProductData',
                        'dataFunction' => 'initMelisCouponProduct',
                        'ajaxCallback' => 'melisCommerce.initTooltipTable(), initCouponProductTable()',
                        'filters' => [
                            'left' => [
                                'coupon-table-filter-limit-assign-product' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ],
                            ],
                
                            'center' => [
                                'order-table-filter-search-assign-product' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ],
                            ],
                
                            'right' => [
                                'coupon-table-filter-refresh-assign-product' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ],
                            ],
                        ],
                
                        'columns' => [
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
                            
                            /* 'product_image' => [
                                'text' => 'tr_meliscommerce_product_list_col_image',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ], */
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
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-edit'
                            ],
                            'add' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-assign-product'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
