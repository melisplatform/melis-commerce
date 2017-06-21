<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(   
                'meliscommerce_clients_list' => array(   
                    'conf' => array(
                        'title' => 'tr_meliscommerce_clients_list',
                        'id' => 'id_meliscommerce_clients_list',
                    ),
                    'table' => array(
                        // table ID
                        'target' => '#clientListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComClientList/getClientList',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'meliscommerce-clients-list-tbl-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-limit',
                                ),
                            ),
                            'center' => array(
                                'meliscommerce-clients-list-tbl-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-search',
                                ),
                            ),
                            'right' => array(
                                'meliscommerce-clients-list-tbl-export' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-export',
                                ),
                                'meliscommerce-clients-list-tbl-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'cli_id' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_id',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'cli_status' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_status',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'cli_person' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_person',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'cli_company' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_company',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'cli_num_orders' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_num_orders',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'cli_last_order' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_last_order',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'cli_date_creation' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_date_created',
                                'css' => array('width' => '15%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                        ),
                        // define what columns can be used in searching
                        'searchables' => array(),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-table-view',
                            ),
                        )
                    ),
                ),
                'meliscommerce_client_order_list' => array(
                    'table' => array(
                        'target' => '',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderList/getOrderListData',
                        'dataFunction' => 'initClientOrderList',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'meliscommerce-clients-tbl-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClient',
                                    'action' => 'render-client-table-limit',
                                ),
                                'order-list-table-filter-date' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-date'
                                ),
                            ),
                            'center' => array(
                                'meliscommerce-clients-tbl-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClient',
                                    'action' => 'render-client-table-search',
                                ),
                                'order-list-table-filter-bulk' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-bulk'
                                ),
                            ),
                            'right' => array(
                                'meliscommerce-clients-tbl-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClient',
                                    'action' => 'render-client-table-refresh',
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
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'products' => array(
                                'text' => '<i class="fa icon-shippment fa-lg"></i>',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'price' => array(
                                'text' => '<i class="fa fa-usd fa-lg"></i>',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
//                             'ccomp_name' => array(
//                                 'text' => 'tr_meliscommerce_order_list_col_company',
//                                 'css' => array('width' => '15%', 'padding-right' => '0'),
//                                 'sortable' => false,
//                             ),
//                             'civt_min_name' => array(
//                                 'text' => 'tr_meliscommerce_order_list_col_civility',
//                                 'css' => array('width' => '10%', 'padding-right' => '0'),
//                                 'sortable' => false,
//                             ),
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
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-table-view'
                            ),
                        ),
                
                    ),
                ),
            )
        )
    )
);