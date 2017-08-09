<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_settings' => array(
                    'meliscommerce_settings_alert_form' => array(
                        'attributes' => array(
                            'name' => 'settings_stock_alert',
                            'id' => 'settings_stock_alert',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'sea_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'sea_prd_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'sea_stock_level_alert',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_product_stock_low_field',
                                        'tooltip' => 'tr_meliscommerce_product_stock_low_field tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'sea_stock_level_alert' => array(
                                'name'     => 'sea_stock_level_alert',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => '\Zend\I18n\Validator\IsInt',
                                        'options' => array(
                                            'messages'=> array(
                                                \Zend\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_coupon_input_invalid_digit',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_10',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                     
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
