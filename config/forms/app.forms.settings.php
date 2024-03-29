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
            'forms' => [
                'meliscommerce_settings' => [
                    'meliscommerce_settings_alert_form' => [
                        'attributes' => [
                            'name' => 'settings_stock_alert',
                            'id' => 'settings_stock_alert',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'sea_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'value' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'sea_prd_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'value' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'sea_stock_level_alert',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_product_stock_low_field',
                                        'tooltip' => 'tr_meliscommerce_product_stock_low_field tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'sea_stock_level_alert' => [
                                'name'     => 'sea_stock_level_alert',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsInt',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_coupon_input_invalid_digit',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_10',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [

                                ],
                            ],
                        ],
                    ],
                    'meliscommerce_settings_accounts_form' => [
                        'attributes' => [
                            'name' => 'settingsAccountForm',
                            'id' => 'settingsAccountForm',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'sa_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'value' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'sa_type',
                                    'type' => 'Select',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_settings_type_account_name',
                                        'value_options' => [
                                            'manual_input' => 'tr_meliscommerce_settings_type_manual_input',
                                            'company_name' => 'tr_meliscommerce_settings_type_company_name',
                                            'contact_name' => 'tr_meliscommerce_settings_type_contact_name',
                                        ],
                                    ],
                                    'attributes' => [
                                        'id' => 'sa_type',
                                    ]
                                ]
                            ],
                        ],
                        'input_filter' => [

                        ],
                    ],
                ],
            ],
        ],
    ],
];
