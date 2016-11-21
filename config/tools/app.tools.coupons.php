<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(   
                'meliscommerce_coupon_list' => array(
                    'table' => array(                       
                        'target' => '#tableCouponList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCouponList/getCouponListData',
                        'dataFunction' => 'initMelisCommerceCouponTbl',
                        'ajaxCallback' => 'initCheckUsedCoupon()',
                        'filters' => array(
                            'left' => array(
                                'coupon-list-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCouponList',
                                    'action' => 'render-coupon-list-content-filter-limit'
                                ),
                            ),
                        
                            'center' => array(
                                'order-list-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCouponList',
                                    'action' => 'render-coupon-list-content-filter-search'
                                ),
                            ),
                        
                            'right' => array(
                                'coupon-list-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCouponList',
                                    'action' => 'render-coupon-list-content-filter-refresh'
                                ),
                            ),
                        ),
                        
                        'columns' => array(
                            'coup_id' => array(
                                'text' => 'tr_meliscommerce_coupon_list_col_id',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'coup_status' => array(
                                'text' => 'tr_meliscommerce_coupon_list_col_status',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                        
                            'coup_code' => array(
                                'text' => 'tr_meliscommerce_coupon_list_col_code',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                        
                            'coup_percentage' => array(
                                'text' => '<i class="fa fa-percent fa-lg commerce-coupon-percent"></i>',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            
                            'coup_discount_value' => array(
                                'text' => '<i class="fa fa-usd fa-lg commerce-coupon-value"></i>',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            
                            'coup_current_use_number' => array(
                                'text' => 'tr_meliscommerce_coupon_list_col_used',
                                'css' => array('width' => '30%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                        ),
                        
                        'searchables' => array(),
                        'actionButtons' => array(
                            'info' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCouponList',
                                'action' => 'render-coupon-list-content-action-info'
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCouponList',
                                'action' => 'render-coupon-list-content-action-delete'
                            ),
                        ),
                    ),  
                ),
                'meliscommerce_coupon' => array(
                    'table' => array(                       
                        'target' => '#clientList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getCouponClientData',
                        'dataFunction' => 'initCouponClient',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'coupon-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ),
                            ),
                        
                            'center' => array(
                                'order-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ),
                            ),
                        
                            'right' => array(
                                'coupon-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ),
                            ),
                        ),
                        
                        'columns' => array(
                            'cli_id' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'cli_status' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_status',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            
                            'assign' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_assigned',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                        
                            'civt_min_name' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_civility',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                        
                            'cper_firstname' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_name_first',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            
                            'cper_name' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_name_last',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            
                            'cper_email' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_email',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'ccomp_name' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_company',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),                                                        
                        ),
                        
                        'searchables' => array(),
                        'actionButtons' => array(                            
                            'add' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-assign'
                            ),
                        ),
                    ),  
                ),
                'meliscommerce_coupon_assigned' => array(
                    'table' => array(
                        'target' => '#clientListAssigned',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getAssignedCouponClientData',
                        'dataFunction' => 'initCouponClient',
                        'ajaxCallback' => 'initCheckClientUsedCoupon()',
                        'filters' => array(
                            'left' => array(
                                'coupon-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ),
                            ),
                
                            'center' => array(
                                'order-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ),
                            ),
                
                            'right' => array(
                                'coupon-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ),
                            ),
                        ),
                
                        'columns' => array(
                            'cli_id' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_id',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'cli_status' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_status',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
//                             'assign' => array(
//                                 'text' => 'tr_meliscommerce_coupon_page_table_col_assigned',
//                                 'css' => array('width' => '1%', 'padding-right' => '0'),
//                                 'sortable' => true,
//                             ),
                
                            'civt_min_name' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_civility',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
                            'cper_firstname' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_name_first',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
                            'cper_name' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_name_last',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
                            'cper_email' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_email',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'ccomp_name' => array(
                                'text' => 'tr_meliscommerce_coupon_page_table_col_company',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                        ),
                
                        'searchables' => array(),
                        'actionButtons' => array(
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-delete'
                            ),
                        ),
                    ),
                ),
                'meliscommerce_coupon_order' => array(
                    'table' => array(
                        'target' => '#tableOrderList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderList/getOrderListData',
                        'dataFunction' => 'initCouponOrder',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'order-list-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-limit'
                                ),
                                'order-list-table-filter-date' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-date'
                                ),
                            ),
                
                            'center' => array(
                                'order-list-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-search'
                                ),
                                'order-list-table-filter-bulk' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-bulk'
                                ),
                            ),
                
                            'right' => array(                                                            
                                'order-list-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-refresh'
                                ),
                            ),
                        ),
                
                        'columns' => array(
                            'ord_id' => array(
                                'text' => 'tr_meliscommerce_order_list_col_id',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'ord_reference' => array(
                                'text' => 'tr_meliscommerce_order_list_col_reference',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
                            'ord_status' => array(
                                'text' => 'tr_meliscommerce_order_list_col_status',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
                            'products' => array(
                                'text' => '<i class="fa icon-shippment fa-lg"></i>',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
                            'price' => array(
                                'text' => '<i class="fa fa-usd fa-lg"></i>',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
//                             'ccomp_name' => array(
//                                 'text' => 'tr_meliscommerce_order_list_col_company',
//                                 'css' => array('width' => '15%', 'padding-right' => '0'),
//                                 'sortable' => false,
//                             ),
                
                            'civt_min_name' => array(
                                'text' => 'tr_meliscommerce_order_list_col_civility',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                
                            'cper_firstname' => array(
                                'text' => 'tr_meliscommerce_order_list_col_firstname',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                
                            'cper_name' => array(
                                'text' => 'tr_meliscommerce_order_list_col_name',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                
                            'ord_date_creation' => array(
                                'text' => 'tr_meliscommerce_order_list_col_date',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                        ),
                
                        'searchables' => array(),
                        'actionButtons' => array(
                            'info' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-content-action-info'
                            ),
                        ),
                
                    ),
                ),
            ),
        ),
    ),
);
