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
                'meliscommerce_order_checkout_product_list' => [
                    'table' => [
                        'target' => '#orderCheckoutProductListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderCheckout/getProductList',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'meliscommerce-order-checkout-product-list-countries' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-countries',
                                ],
                            ],
                            'center' => [
                                'meliscommerce-order-checkout-product-list-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-limit',
                                ],
                                
                            ],
                            'right' => [
                                'meliscommerce-order-checkout-product-list-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-product-list-search',
                                ],
//                                 'meliscommerce-order-checkout-product-list-refresh' => [
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComOrderCheckout',
//                                     'action' => 'render-order-checkout-product-list-refresh',
//                                 ],
                            ],
                        ],
                        'columns' => [
                            'prd_id' => [
                                'text' => 'tr_meliscommerce_order_checkout_product_id',
                                'css' => ['width' => '5%'],
                                'sortable' => true,
                                'responsivepriority' => false,
                            ],
                            'prd_image' => [
                                'text' => 'tr_meliscommerce_order_checkout_product_img',
                                'css' => ['width' => '20%'],
                                'sortable' => false,
                            ],
                            'prd_name' => [
                                'text' => 'tr_meliscommerce_order_checkout_product_name',
                                'css' => ['width' => '50%'],
                                'sortable' => false,
                            ],
                        ],
                        'searchables' => [],
                        'actionButtons' => [
                            'view' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-product-list-view-variant',
                            ],
                        ]
                    ],
                ],
                'meliscommerce_order_checkout_contact_list' => [
                    'table' => [
                        'target' => '#orderCheckoutContactListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderCheckout/getContactList',
                        'dataFunction' => 'initClientIdForNewOrder',
                        'ajaxCallback' => 'initCheckoutSelectContactTable();',
                        'filters' => [
                            'left' => [
                                'meliscommerce-order-checkout-contact-list-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-contact-list-limit',
                                ],
                            ],
                            'center' => [
                                'meliscommerce-order-checkout-contact-list-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-contact-list-search',
                                ],
                            ],
                            'right' => [
                                'meliscommerce-order-checkout-contact-list-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderCheckout',
                                    'action' => 'render-order-checkout-contact-list-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'cper_id' => [
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_id',
                                'sortable' => true,
                            ],
                            'cper_status' => [
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_status',
                                'sortable' => true,
                            ],
                            'cper_contact' => [
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_contact',
                                'sortable' => true,
                            ],
                            'cgroup_name' => [
                                'text' => 'tr_meliscommerce_checkout_tbl_group',
                                'sortable' => true,
                            ],
                            'cper_email' => [
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_email',
                                'sortable' => true,
                            ],
                            'cper_num_orders' => [
                                'text' => '<i class="fa fa fa-cart-plus fa-lg checkoutSelectContactOrderHeader"></i>',
                                'sortable' => false,
                            ],
                            'cper_last_order' => [
                                'text' => 'tr_meliscommerce_checkout_tbl_cper_last_order',
                                'sortable' => true,
                            ],
                        ],
                        // define what columns can be used in searching
                        'searchables' => ['cper_id', 'cper_status', 'cper_email', 'cper_firstname', 'cper_name'],
                        'actionButtons' => [
                            'action' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-contact-list-select',
                            ],
                        ]
                    ],
                ],
            ]
        ]
    ]
];