<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(   
                'meliscommerce_order_list' => array(
                    'table' => array(                       
                        'target' => '#tableOrderList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderList/getOrderListData',
                        'dataFunction' => 'initOrderList',
                        'ajaxCallback' => 'initOrderListTitle()',
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
//                                 'order-list-table-filter-grid-view' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComOrderList',
//                                     'action' => 'render-order-list-content-filter-grid-view'
//                                 ),
//                                 'product-list-table-filter-list-view' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComOrderList',
//                                     'action' => 'render-order-list-content-filter-list-view'
//                                 ),
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
                'meliscommerce_order_basket_list' => array(
                    'table' => array(
                        'target' => '#tableOrderBasketList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrder/getBasketData',
                        'dataFunction' => 'initOrderBasket',
                        'ajaxCallback' => '',                        
                        'filters' => array(
                            'left' => array(
                                'order-basket-list-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrder',
                                    'action' => 'render-order-content-filter-limit'
                                ),
                            ),                        
                            'center' => array(
                                'order-basket-list-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrder',
                                    'action' => 'render-order-content-filter-search'
                                ),
                            ),                        
                            'right' => array(
                                'order-basket-list-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrder',
                                    'action' => 'render-order-content-filter-refresh'
                                ),
                            ),
                        ),                        
                        'columns' => array(                          
                            
                            'obas_id' => array(
                                'text' => 'tr_meliscommerce_order_list_col_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            
                            'image' => array(
                                'text' => 'tr_meliscommerce_order_list_col_image',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            
                            'obas_product_name' => array(
                                'text' => 'tr_meliscommerce_order_basket_list_name',
                                'css' => array('width' => '17%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            
                            'obas_sku' => array(
                                'text' => 'tr_meliscommerce_order_basket_list_sku',
                                'css' => array('width' => '17%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            
                            'obas_quantity' => array(
                                'text' => 'tr_meliscommerce_order_basket_list_qty',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            
                            'obas_price_net' => array(
                                'text' => '<i class="fa fa-usd fa-lg"></i>',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,
                            ), 
                            
                            'obas_category_name' => array(
                                'text' => 'tr_meliscommerce_order_basket_list_category',
                                'css' => array('width' => '30%', 'padding-right' => '0'),
                                'sortable' => true,
                            )                            
                        ),                        
                        'searchables' => array(
                             
                        ),
                        'actionButtons' => array(
//                             'info' => array(
//                                 'module' => 'MelisCommerce',
//                                 'controller' => 'MelisComOrderList',
//                                 'action' => 'render-order-list-content-action-info'
//                             ),
                        ),
                    ),                   
                ),
            ),
        ),
    ),
);