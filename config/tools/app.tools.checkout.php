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
                                'meliscommerce-order-checkout-product-list-countries' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-countries',
                                ),
                            ),
                            'center' => array(
                                'meliscommerce-order-checkout-product-list-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-limit',
                                ),
                                
                            ),
                            'right' => array(
                                'meliscommerce-order-checkout-product-list-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-search',
                                ),
//                                 'meliscommerce-order-checkout-product-list-refresh' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComOrderCheckout',
//                                     'action' => 'render-order-checkout-product-list-refresh',
//                                 ),
                            ),
                        ),
                        'columns' => array(
                            'prd_id' => array(
                                'text' => 'tr_meliscommerce_order_checkout_product_id',
                                'css' => array('width' => '5%'),
                                'sortable' => true,
                                'responsivepriority' => false,
                            ),
                            'prd_image' => array(
                                'text' => 'tr_meliscommerce_order_checkout_product_img',
                                'css' => array('width' => '20%'),
                                'sortable' => false,
                            ),
                            'prd_name' => array(
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
                'meliscommerce_order_checkout_contact_list' => array(   
                    'table' => array(
                        'target' => '#orderCheckoutContactListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderCheckout/getContactList',
                        'dataFunction' => '',
                        'ajaxCallback' => 'initCheckoutSelectContactTable();',
                        'filters' => array(
                            'left' => array(
                                'meliscommerce-order-checkout-contact-list-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-contact-list-limit',
                                ),
                            ),
                            'center' => array(
                                'meliscommerce-order-checkout-contact-list-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-contact-list-search',
                                ),
                            ),
                            'right' => array(
                                'meliscommerce-order-checkout-contact-list-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-contact-list-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'cper_id' => array(
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_id',
                                'css' => array('width' => '5%'),
                                'sortable' => true,
                            ),
                            'cper_status' => array(
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_status',
                                'css' => array('width' => '5%'),
                                'sortable' => true,
                            ),
                            'cper_contact' => array(
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_contact',
                                'css' => array('width' => '25%'),
                                'sortable' => true,
                            ),
                            'cper_email' => array(
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_email',
                                'css' => array('width' => '25%'),
                                'sortable' => true,
                            ),
                            'cper_num_orders' => array(
                                'text' => '<i class="fa fa fa-cart-plus fa-lg checkoutSelectContactOrderHeader"></i>',
                                'css' => array('width' => '10%'),
                                'sortable' => false,
                            ),
                            'cper_last_order' => array(
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_last_order',
                                'css' => array('width' => '15%'),
                                'sortable' => true,
                            ),
                        ),
                        // define what columns can be used in searching
                        'searchables' => array('cper_id', 'cper_status', 'cper_email', 'cper_firstname', 'cper_name'),
                        'actionButtons' => array(
                            'action' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-contact-list-select',
                            ),
                        )
                    ),
                ),
            )
        )
    )
);