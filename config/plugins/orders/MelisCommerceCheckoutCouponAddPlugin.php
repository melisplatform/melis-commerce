<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutCouponAddPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-coupon',
                        'm_coupon_code' => '',
                        'm_coupon_multiple' => false,
                        'm_is_submit' => false,
                        'm_site_id' => 1,
                        'forms' => array(
                            'meliscommerce_checkout_coupon_form' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => '',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_is_submit',
                                            'type' => 'hidden',
                                            'attributes' => array(
                                                'value' => '1',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_coupon_code',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_order_checkout_variant_coupon_code',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_coupon_code',
                                            )
                                        )
                                    ),
                                ),
                            )
                        )
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);