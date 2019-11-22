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
                        'ajaxCallback' => 'initCheckUsedCoupon(), melisCore.paginateDataTables()',
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
                                'text' => 'tr_meliscommerce_coupon_page_table_col_price',
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
                        'ajaxCallback' => 'initCouponClientTable()',
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
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-edit-client',
                            ),
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
                                'coupon-table-filter-limit-assigned' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ),
                            ),

                            'center' => array(
                                'order-table-filter-search-assigned' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ),
                            ),

                            'right' => array(
                                'coupon-table-filter-refresh-assigned' => array(
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
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-edit-client',
                            ),
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
                        'ajaxCallback' => 'melisCore.paginateDataTables()',
                        'filters' => array(
                            'left' => array(
                                'order-list-table-filter-limit-order' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-limit'
                                ),
                                'order-list-table-filter-date-order' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-date'
                                ),
                            ),

                            'center' => array(
                                'order-list-table-filter-search-order' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-search'
                                ),
                                'order-list-table-filter-bulk-order' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-bulk'
                                ),
                            ),

                            'right' => array(
                                'order-list-table-filter-refresh-order' => array(
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
                                'text' => 'tr_meliscommerce_coupon_page_table_col_price',
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
                'meliscommerce_coupon_product_assigned' => array(
                    'table' => array(
                        'target' => '#productListAssigned',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getCouponProductData',
                        'dataFunction' => 'initMelisCouponProduct',
                        'ajaxCallback' => 'melisCommerce.initTooltipTable()',
                        'filters' => array(
                            'left' => array(
                                'coupon-table-filter-limit-assigned-product' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ),
                            ),
                
                            'center' => array(
                                'order-table-filter-search-assigned-product' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ),
                            ),
                
                            'right' => array(
                                'coupon-table-filter-refresh-assigned-product' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ),
                            ),
                        ),
                
                        'columns' => array(
                            'prd_id' => array(
                                'text' => 'tr_meliscommerce_product_list_col_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),

                            'prd_status' => array(
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),

//                             'product_image' => array(
//                                 'text' => 'tr_meliscommerce_product_list_col_image',
//                                 'css' => array('width' => '5%', 'padding-right' => '0'),
//                                 'sortable' => false,
//                             ),
                            'product_name' => array(
                                'text' => 'tr_meliscommerce_product_list_col_name',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            
                            'product_categories' => array(
                                'text' => 'tr_meliscommerce_product_list_col_categories',
                                'css' => array('width' => '30%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                        ),
                
                        'searchables' => array(),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-edit'
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-delete-assigned-product'
                            ),
                        ),
                    ),
                ),
                'meliscommerce_coupon_product_assign' => array(
                    'table' => array(
                        'target' => '#productList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCoupon/getCouponProductData',
                        'dataFunction' => 'initMelisCouponProduct',
                        'ajaxCallback' => 'melisCommerce.initTooltipTable(), initCouponProductTable(), melisCore.paginateDataTables()',
                        'filters' => array(
                            'left' => array(
                                'coupon-table-filter-limit-assign-product' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-limit'
                                ),
                            ),
                
                            'center' => array(
                                'order-table-filter-search-assign-product' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-search'
                                ),
                            ),
                
                            'right' => array(
                                'coupon-table-filter-refresh-assign-product' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCoupon',
                                    'action' => 'render-coupon-content-filter-refresh'
                                ),
                            ),
                        ),
                
                        'columns' => array(
                            'prd_id' => array(
                                'text' => 'tr_meliscommerce_product_list_col_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),

                            'prd_status' => array(
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            
//                             'product_image' => array(
//                                 'text' => 'tr_meliscommerce_product_list_col_image',
//                                 'css' => array('width' => '5%', 'padding-right' => '0'),
//                                 'sortable' => false,
//                             ),
                            'product_name' => array(
                                'text' => 'tr_meliscommerce_product_list_col_name',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            
                            'product_categories' => array(
                                'text' => 'tr_meliscommerce_product_list_col_categories',
                                'css' => array('width' => '30%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                        ),
                
                        'searchables' => array(),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-edit'
                            ),
                            'add' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-content-action-assign-product'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
