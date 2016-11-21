<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_order_checkout',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/checkout.tool.js',
                ),
                'css' => array(
                    '/MelisCommerce/css/checkout.css',
                ),
            ),
            'datas' => array(
            
            ),
            'interface' => array(
                'meliscommerce_order_checkout' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_order_checkout',
                        'name' => 'tr_meliscommerce_order_checkout',
                        'melisKey' => 'meliscommerce_order_checkout',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrderCheckout',
                        'action' => 'render-order-checkout-page',
                    ),
                    'interface' => array(
                        'meliscommerce_order_checkout_header' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_checkout_header',
                                'name' => 'tr_meliscommerce_order_checkout_header',
                                'melisKey' => 'meliscommerce_order_checkout_header',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-header',
                            ),
                            'interface' => array(
                                
                            )
                        ),
                        'meliscommerce_order_checkout_content' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_checkout_content',
                                'name' => 'tr_meliscommerce_order_checkout_content',
                                'melisKey' => 'meliscommerce_order_checkout_content',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderCheckout',
                                'action' => 'render-order-checkout-content',
                            ),
                            'interface' => array(
                                'meliscommerce_order_checkout_choose_product_step' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_checkout_choose_product_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Choose_product',
                                        'melisKey' => 'meliscommerce_order_checkout_choose_product_step',
                                        'icon' => 'glyphicons shopping_bag'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-choose-product',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_checkout_choose_product_step_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_choose_product_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Choose_product',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_product_step_header',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-product-header',
                                            ),
                                        ),
                                        'meliscommerce_order_checkout_choose_product_step_content' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_choose_product_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_choose_product_step_header',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_product_step_header',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-product-content',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_checkout_product_list' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_product_list',
                                                        'name' => 'tr_meliscommerce_order_checkout_product_list',
                                                        'melisKey' => 'meliscommerce_order_checkout_product_list',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-product-list',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_order_checkout_product_variant_list' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_order_checkout_product_variant_list',
                                                                'name' => 'tr_meliscommerce_order_checkout_product_variant_list',
                                                                'melisKey' => 'meliscommerce_order_checkout_product_variant_list',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrderCheckout',
                                                                'action' => 'render-order-checkout-product-variant-list',
                                                            ),
                                                        )
                                                    )
                                                ),
                                                'meliscommerce_order_checkout_product_bakset' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_product_bakset',
                                                        'name' => 'tr_meliscommerce_order_checkout_product_bakset',
                                                        'melisKey' => 'meliscommerce_order_checkout_product_bakset',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-product-basket',
                                                        'jscallback' => 'productNextButtonState();'
                                                    ),
                                                )
                                            )
                                        )
                                    )
                                ),
                                'meliscommerce_order_checkout_choose_contact_step' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_checkout_choose_contact_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Choose_contact',
                                        'melisKey' => 'meliscommerce_order_checkout_choose_contact_step',
                                        'icon' => 'glyphicons user'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-choose-contact',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_checkout_choose_contact_step_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_choose_contact_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Choose_contact',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_contact_step_header',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-contact-header',
                                            ),
                                        ),
                                        'meliscommerce_order_checkout_choose_contact_step_content' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_choose_contact_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_choose_contact_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_choose_contact_step_content',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-choose-contact-content',
                                            ),
                                        )
                                    )
                                ),
                                'meliscommerce_order_checkout_select_addresses_step' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_checkout_select_addresses_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Select_addresses',
                                        'melisKey' => 'meliscommerce_order_checkout_select_addresses_step',
                                        'icon' => 'glyphicons google_maps'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-select-addresses',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_checkout_select_addresses_step_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_select_addresses_step',
                                                'name' => 'tr_meliscommerce_order_checkout_Select_addresses',
                                                'melisKey' => 'meliscommerce_order_checkout_select_addresses_step',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-select-addresses-header',
                                            ),
                                        ),
                                        'meliscommerce_order_checkout_select_addresses_step_content' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_select_addresses_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_select_addresses_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_select_addresses_step_content',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-select-addresses-content',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_checkout_billing_address' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_billing_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_billing_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_billing_address',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-billing-address',
                                                    ),
                                                ),
                                                'meliscommerce_order_checkout_delivery_address' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_delivery_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_delivery_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_delivery_address',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-delivery-address',
                                                    ),
                                                )
                                            )
                                        )
                                    )
                                ),
                                'meliscommerce_order_checkout_summary_step' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_checkout_summary_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Summary',
                                        'melisKey' => 'meliscommerce_order_checkout_summary_step',
                                        'icon' => 'glyphicons notes'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-summary',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_checkout_summary_step_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_summary_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Summary',
                                                'melisKey' => 'meliscommerce_order_checkout_summary_step_header',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-summary-header',
                                            ),
                                        ),
                                        'meliscommerce_order_checkout_summary_step_content' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_summary_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_summary_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_summary_step_content',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-summary-content',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_checkout_summary_basket' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_summary_basket',
                                                        'name' => 'tr_meliscommerce_order_checkout_summary_basket',
                                                        'melisKey' => 'meliscommerce_order_checkout_summary_basket',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-summary-basket',
                                                    ),
                                                ),
                                                'meliscommerce_order_checkout_summary_billing_address' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_summary_billing_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_summary_billing_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_summary_billing_address',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-summary-billing-address',
                                                    ),
                                                ),
                                                'meliscommerce_order_checkout_summary_delivery_address' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_summary_delivery_address',
                                                        'name' => 'tr_meliscommerce_order_checkout_summary_delivery_address',
                                                        'melisKey' => 'meliscommerce_order_checkout_summary_delivery_address',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-summary-delivery-address',
                                                    ),
                                                ),
                                            )
                                        )
                                    )
                                ),
                                'meliscommerce_order_checkout_payment_step' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_checkout_payment_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Payment',
                                        'melisKey' => 'meliscommerce_order_checkout_payment_step',
                                        'icon' => 'glyphicons credit_card'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-payment',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_checkout_payment_step_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_payment_step_header',
                                                'name' => 'tr_meliscommerce_order_checkout_Payment',
                                                'melisKey' => 'meliscommerce_order_checkout_payment_step_header',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-payment-header',
                                            ),
                                        ),
                                        'meliscommerce_order_checkout_payment_step_content' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_checkout_payment_step_content',
                                                'name' => 'tr_meliscommerce_order_checkout_payment_step_content',
                                                'melisKey' => 'meliscommerce_order_checkout_payment_step_content',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderCheckout',
                                                'action' => 'render-order-checkout-payment-content',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_checkout_payment_iframe' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_payment_iframe',
                                                        'name' => 'tr_meliscommerce_order_checkout_payment_iframe',
                                                        'melisKey' => 'meliscommerce_order_checkout_payment_iframe',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-payment-iframe',
                                                    ),
                                                    'interface' => array(
                                                
                                                    )
                                                ),
                                                'meliscommerce_order_checkout_payment_done' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_checkout_payment_done',
                                                        'name' => 'tr_meliscommerce_order_checkout_payment_done',
                                                        'melisKey' => 'meliscommerce_order_checkout_payment_done',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderCheckout',
                                                        'action' => 'render-order-checkout-payment-done',
                                                    ),
                                                    'interface' => array(
                                                
                                                    )
                                                )
                                            )
                                        )
                                    )
                                ),
                                'meliscommerce_order_checkout_confirmation_step' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_checkout_confirmation_step',
                                        'name' => 'tr_meliscommerce_order_checkout_Confirmation',
                                        'melisKey' => 'meliscommerce_order_checkout_confirmation_step',
                                        'icon' => 'glyphicons circle_ok'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderCheckout',
                                        'action' => 'render-order-checkout-confirmation',
                                    ),
                                ),
                            )
                        )
                    )
                )
            )
        )
    )
);