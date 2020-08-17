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
            'conf' => [
                'id' => '',
                'name' => 'tr_meliscommerce_order_checkout',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/checkout.tool.js',
                ],
            ],
            'datas' => [

            ],
            'interface' => [
                'meliscommerce_order_checkout' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_order_checkout',
                        'name' => 'tr_meliscommerce_order_checkout',
                        'melisKey' => 'meliscommerce_order_checkout',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrderCheckout',
                        'action' => 'render-order-checkout-page',
                    ],
                    'interface' => [
                        'meliscommerce_order_checkout_header' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_checkout_header',
                                'name' => 'tr_meliscommerce_order_checkout_header',
                                'melisKey' => 'meliscommerce_order_checkout_header',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-header',
                            ],
                            'interface' => [

                            ]
                        ],
                        'meliscommerce_order_checkout_content' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_checkout_content',
                                'name' => 'tr_meliscommerce_order_checkout_content',
                                'melisKey' => 'meliscommerce_order_checkout_content',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-content',
                            ],
                            'interface' => [
                                'meliscommerce_order_checkout_choose_product_step' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_checkout_choose_product_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Choose_product',
                                        'melisKey' => 'meliscommerce_order_checkout_choose_product_step',
                                        'icon' => 'glyphicons shopping_bag'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-choose-product',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_checkout_choose_product_step_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_choose_product_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Choose_product',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_product_step_header',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-product-header',
                                            ],
                                        ],
                                        'meliscommerce_order_checkout_choose_product_step_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_choose_product_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_choose_product_step_header',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_product_step_header',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-product-content',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_checkout_product_list' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_product_list',
                                                        'name' => 'tr_meliscommerce_order_checkout_product_list',
                                                        'melisKey' => 'meliscommerce_order_checkout_product_list',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-product-list',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_order_checkout_product_variant_list' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_order_checkout_product_variant_list',
                                                                'name' => 'tr_meliscommerce_order_checkout_product_variant_list',
                                                                'melisKey' => 'meliscommerce_order_checkout_product_variant_list',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrderCheckout',
                                                                'action' => 'render-order-checkout-product-variant-list',
                                                            ],
                                                        ]
                                                    ]
                                                ],
                                                'meliscommerce_order_checkout_product_bakset' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_product_bakset',
                                                        'name' => 'tr_meliscommerce_order_checkout_product_bakset',
                                                        'melisKey' => 'meliscommerce_order_checkout_product_bakset',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-product-basket',
                                                        'jscallback' => 'productNextButtonState();'
                                                    ],
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'meliscommerce_order_checkout_choose_contact_step' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_checkout_choose_contact_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Choose_contact',
                                        'melisKey' => 'meliscommerce_order_checkout_choose_contact_step',
                                        'icon' => 'glyphicons user'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-choose-contact',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_checkout_choose_contact_step_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_choose_contact_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Choose_contact',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_contact_step_header',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-contact-header',
                                            ],
                                        ],
                                        'meliscommerce_order_checkout_choose_contact_step_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_choose_contact_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_choose_contact_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_contact_step_content',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-contact-content',
                                            ],
                                        ]
                                    ]
                                ],
                                'meliscommerce_order_checkout_select_addresses_step' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_checkout_select_addresses_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Select_addresses',
                                        'melisKey' => 'meliscommerce_order_checkout_select_addresses_step',
                                        'icon' => 'glyphicons google_maps'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-select-addresses',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_checkout_select_addresses_step_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_select_addresses_step',
                                                'name' => 'tr_meliscommerce_order_checkout_Select_addresses',
                                                'melisKey' => 'meliscommerce_order_checkout_select_addresses_step',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-select-addresses-header',
                                            ],
                                        ],
                                        'meliscommerce_order_checkout_select_addresses_step_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_select_addresses_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_select_addresses_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_select_addresses_step_content',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-select-addresses-content',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_checkout_delivery_address' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_delivery_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_delivery_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_delivery_address',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-delivery-address',
                                                    ],
                                                ],
                                                'meliscommerce_order_checkout_billing_address' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_billing_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_billing_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_billing_address',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-billing-address',
                                                    ],
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'meliscommerce_order_checkout_summary_step' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_checkout_summary_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Summary',
                                        'melisKey' => 'meliscommerce_order_checkout_summary_step',
                                        'icon' => 'glyphicons notes'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-summary',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_checkout_summary_step_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_summary_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Summary',
                                                'melisKey' => 'meliscommerce_order_checkout_summary_step_header',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-summary-header',
                                            ],
                                        ],
                                        'meliscommerce_order_checkout_summary_step_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_summary_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_summary_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_summary_step_content',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-summary-content',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_checkout_summary_basket' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_summary_basket',
                                                        'name' => 'tr_meliscommerce_order_checkout_summary_basket',
                                                        'melisKey' => 'meliscommerce_order_checkout_summary_basket',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-summary-basket',
                                                    ],
                                                ],
                                                'meliscommerce_order_checkout_summary_delivery_address' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_summary_delivery_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_summary_delivery_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_summary_delivery_address',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-summary-delivery-address',
                                                    ],
                                                ],
                                                'meliscommerce_order_checkout_summary_billing_address' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_summary_billing_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_summary_billing_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_summary_billing_address',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-summary-billing-address',
                                                    ],
                                                ],
                                            ]
                                        ]
                                    ]
                                ],
                                'meliscommerce_order_checkout_payment_step' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_checkout_payment_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Payment',
                                        'melisKey' => 'meliscommerce_order_checkout_payment_step',
                                        'icon' => 'glyphicons credit_card'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-payment',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_checkout_payment_step_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_payment_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Payment',
                                                'melisKey' => 'meliscommerce_order_checkout_payment_step_header',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-payment-header',
                                            ],
                                        ],
                                        'meliscommerce_order_checkout_payment_step_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_checkout_payment_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_payment_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_payment_step_content',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-payment-content',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_checkout_payment_iframe' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_payment_iframe',
                                                        'name' => 'tr_meliscommerce_order_checkout_payment_iframe',
                                                        'melisKey' => 'meliscommerce_order_checkout_payment_iframe',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-payment-iframe',
                                                    ],
                                                    'interface' => [

                                                    ]
                                                ],
                                                'meliscommerce_order_checkout_payment_done' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_checkout_payment_done',
                                                        'name' => 'tr_meliscommerce_order_checkout_payment_done',
                                                        'melisKey' => 'meliscommerce_order_checkout_payment_done',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-payment-done',
                                                    ],
                                                    'interface' => [

                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'meliscommerce_order_checkout_confirmation_step' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_checkout_confirmation_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Confirmation',
                                        'melisKey' => 'meliscommerce_order_checkout_confirmation_step',
                                        'icon' => 'glyphicons circle_ok'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-confirmation',
                                    ],
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];