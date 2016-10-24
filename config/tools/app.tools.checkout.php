<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(   
                'meliscommerce_order_checkout_product_list' => array(   
                    'table' => array(
                        'target' => '#orderCheckoutProductListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderCheckout/getProductList',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'meliscommerce-order-checkout-product-list-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-limit',
                                ),
                            ),
                            'center' => array(
                                'meliscommerce-order-checkout-product-list-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-search',
                                ),
                            ),
                            'right' => array(
                                'meliscommerce-order-checkout-product-list-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'product_id' => array(
                                'text' => 'tr_meliscommerce_order_checkout_product_id',
                                'css' => array('width' => '5%'),
                                'sortable' => true,
                                'responsivepriority' => false,
                            ),
                            'product_image' => array(
                                'text' => 'tr_meliscommerce_order_checkout_product_img',
                                'css' => array('width' => '20%'),
                                'sortable' => false,
                            ),
                            'product_name' => array(
                                'text' => 'tr_meliscommerce_order_checkout_product_name',
                                'css' => array('width' => '50%'),
                                'sortable' => false,
                            ),
                        ),
                        'searchables' => array(),
                        'actionButtons' => array( 
                            'view' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-product-list-view-variant',
                            ),
                        )
                    ),
                ),
                'meliscommerce_order_checkout_client_list' => array(   
                    'table' => array(
                        'target' => '#orderCheckoutClientListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComClientList/getClientList',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'meliscommerce-order-checkout-client-list-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-client-list-limit',
                                ),
                            ),
                            'center' => array(
                                'meliscommerce-order-checkout-client-list-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-client-list-search',
                                ),
                            ),
                            'right' => array(
                                'meliscommerce-order-checkout-client-list-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-client-list-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'cli_id' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_id',
                                'css' => array('width' => '5%'),
                                'sortable' => true,
                            ),
                            'cli_status' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_status',
                                'css' => array('width' => '5%'),
                                'sortable' => false,
                            ),
                            'cli_person' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_person',
                                'css' => array('width' => '35%'),
                                'sortable' => false,
                            ),
                            'cli_company' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_company',
                                'css' => array('width' => '20%'),
                                'sortable' => false,
                            ),
                            'cli_num_orders' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_num_orders',
                                'css' => array('width' => '10%'),
                                'sortable' => false,
                            ),
                            'cli_last_order' => array(
                                'text' => 'tr_meliscommerce_clients_table_Client_last_order',
                                'css' => array('width' => '15%'),
                                'sortable' => false,
                            ),
                        ),
                        // define what columns can be used in searching
                        'searchables' => array(),
                        'actionButtons' => array(
                            'action' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-client-list-select',
                            ),
                        )
                    ),
                ),
            )
        )
    )
);