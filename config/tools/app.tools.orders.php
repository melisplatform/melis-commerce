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
                'meliscommerce_order_list' => [
                    'table' => [
                        'target' => '#tableOrderList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderList/getOrderListData',
                        'dataFunction' => 'initOrderList',
                        'ajaxCallback' => 'initOrderListTitle();',
                        'filters' => [
                            'left' => [
                                'order-list-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-limit'
                                ],
                                'order-list-table-filter-date' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-date'
                                ],
                            ],
                        
                            'center' => [
                                'order-list-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-search'
                                ],
                                'order-list-table-filter-bulk' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-bulk'
                                ],
                            ],
                        
                            'right' => [
//                                 'order-list-table-filter-grid-view' => [
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComOrderList',
//                                     'action' => 'render-order-list-content-filter-grid-view'
//                                 ],
//                                 'product-list-table-filter-list-view' => [
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComOrderList',
//                                     'action' => 'render-order-list-content-filter-list-view'
//                                 ],
                                'order-list-table-filter-export' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-export',
                                ],
                                'order-list-table-filter-refresh' => [
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
                                'text' => 'tr_meliscommerce_order_list_col_price',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
//                             'ccomp_name' => [
//                                 'text' => 'tr_meliscommerce_order_list_col_company',
//                                 'css' => ['width' => '15%', 'padding-right' => '0'],
//                                 'sortable' => false,
//                             ],
//                             'civt_min_name' => [
//                                 'text' => 'tr_meliscommerce_order_list_col_civility',
//                                 'css' => ['width' => '10%', 'padding-right' => '0'],
//                                 'sortable' => false,
//                             ],
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
                            'ccomp_name' => [
                                'text' => 'tr_meliscommerce_order_list_col_company',
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
                'meliscommerce_order_basket_list' => [
                    'table' => [
                        'target' => '#tableOrderBasketList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrder/getBasketData',
                        'dataFunction' => 'initOrderBasket',
                        'ajaxCallback' => 'hideBasketButton(); melisCommerce.priceLogTooltip()',
                        'filters' => [
                            'left' => [
                                'order-basket-list-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrder',
                                    'action' => 'render-order-content-filter-limit'
                                ],
                            ],
                            'center' => [
                                'order-basket-list-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrder',
                                    'action' => 'render-order-content-filter-search'
                                ],
                            ],
                            'right' => [
                                'order-basket-list-table-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrder',
                                    'action' => 'render-order-content-filter-refresh'
                                ],
                            ],
                        ],
                        'columns' => [
                            
                            'obas_id' => [
                                'text' => 'tr_meliscommerce_order_list_col_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            
                            'image' => [
                                'text' => 'tr_meliscommerce_order_list_col_image',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            
                            'obas_product_name' => [
                                'text' => 'tr_meliscommerce_order_basket_list_name',
                                'css' => ['width' => '17%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            
                            'obas_sku' => [
                                'text' => 'tr_meliscommerce_order_basket_list_sku',
                                'css' => ['width' => '17%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            
                            'obas_quantity' => [
                                'text' => 'tr_meliscommerce_order_basket_list_qty',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            
                            'obas_price_net' => [
                                'text' => 'tr_meliscommerce_order_list_col_price',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            
                            'obas_category_name' => [
                                'text' => 'tr_meliscommerce_order_basket_list_category',
                                'css' => ['width' => '30%', 'padding-right' => '0'],
                                'sortable' => true,
                            ]
                        ],
                        'searchables' => [
                             
                        ],
                        'actionButtons' => [
                            'info' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrder',
                                'action' => 'render-order-content-action-info'
                            ],
                        ],
                    ],
                ],
                'meliscommerce_order_status' => [
                    'table' => [
                        'target' => '#tableOrderStatus',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderStatus/getOrderStatusData',
                        'dataFunction' => '',
                        'ajaxCallback' => 'initCheckPermStatus;',
                        'filters' => [
                            'left' => [
                                'order-status-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderStatus',
                                    'action' => 'render-order-status-content-filter-limit'
                                ],
                            ],
                            'center' => [
                                'order-status-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderStatus',
                                    'action' => 'render-order-status-content-filter-search'
                                ],
                            ],
                
                            'right' => [
                                'order-status-table-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderStatus',
                                    'action' => 'render-order-status-content-filter-refresh'
                                ],
                            ],
                        ],
                
                        'columns' => [
                            'osta_id' => [
                                'text' => 'tr_meliscommerce_order_list_col_id',
                                'css' => ['width' => '10', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'osta_status' => [
                                'text' => 'tr_meliscommerce_order_list_col_status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'ostt_status_name' => [
                                'text' => 'tr_meliscommerce_order_status_col_ord_status',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'osta_color_code' => [
                                'text' => 'tr_meliscommerce_order_status_col_color_code',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'color_preview' => [
                                'text' => 'tr_meliscommerce_order_status_col_color_preview',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                        ],
                
                        'searchables' => [],
                        'actionButtons' => [
                            'info' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderStatus',
                                'action' => 'render-order-status-content-action-info'
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderStatus',
                                'action' => 'render-order-status-content-action-delete'
                            ],
                        ],
                
                    ],
                ],
                'meliscommerce_order_product_return_list' => [
                    'table' => [
                        'target' => '#tableOrderProductReturnList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderProductReturn/getOrderProductReturnList',
                        'dataFunction' => 'initOrderBasket',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [

                            ],
                            'center' => [

                            ],
                            'right' => [

                            ],
                        ],
                        'columns' => [
                            'pretd_variant_id' => [
                                'text' => 'tr_meliscommerce_general_common_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'image' => [
                                'text' => 'tr_meliscommerce_order_list_col_image',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'product_name' => [
                                'text' => 'tr_meliscommerce_order_basket_list_name',
                                'css' => ['width' => '17%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'pretd_sku' => [
                                'text' => 'tr_meliscommerce_order_basket_list_sku',
                                'css' => ['width' => '17%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'pretd_quantity' => [
                                'text' => 'tr_meliscommerce_order_basket_list_qty',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'price' => [
                                'text' => 'tr_meliscommerce_order_list_col_price',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                        ],
                        'searchables' => [

                        ],
                        'actionButtons' => [

                        ],
                    ],
                ],
            ],
        ],
    ],
];