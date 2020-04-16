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
                'meliscommerce_variants' => [
                    'meliscommerce_variants_information_form' => [
                        'attributes' => [
                            'name' => 'variant',
                            'id' => '',
                            'method' => 'POST',
                            'action' => 'melis/MelisCommerce/MelisComVariant/saveVariantForm',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'var_sku',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_main_information_main_variant_input_label',
                                        'tooltip' => 'tr_meliscommerce_variant_main_information_main_variant_input_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'var_sku' => [
                                'name'     => 'var_sku',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_variants_error_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_variant_main_information_main_variant_long_err',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ],
                    ],
                    'meliscommerce_variants_stocks_form' => [
                        'attributes' => [
                            'name' => 'stockForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => 'melis/MelisCommerce/MelisComVariant/saveStocksForm',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'stock_id',
                                    'type' => 'Hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                    ],
                                ],
                            ],
//                             [
//                                 'spec' => [
//                                     'name' => 'stock_org_qty',
//                                     'type' => 'Hidden',
//                                     'options' => [
//                                     ],
//                                     'attributes' => [
//                                     ],
//                                 ],
//                             ],
                            [
                                'spec' => [
                                    'name' => 'stock_country_id',
                                    'type' => 'Hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'stock_quantity',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_stocks_quantity_label',
                                        'tooltip' => 'tr_meliscommerce_variant_stocks_quantity_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'stock_next_fill_up',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_tab_stocks_fill_up',
                                        'tooltip' => 'tr_meliscommerce_variant_tab_stocks_fill_up tooltip',
                                        'class' => 'd-flex flex-row justify-content-between'
                                    ],
                                    'attributes' => [
                                        'dateId' => 'stocksDate',
                                        'dateLabel' => 'tr_meliscommerce_variant_tab_stocks_fill_up'
                                    ],
                                ],
                            ],

                        ],
                        'input_filter' => [
                            'stock_id' => [
                                'name'     => 'stock_id',
                                'required' => false,
                                'validators' => [],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'stock_country_id' => [
                                'name'     => 'stock_country_id',
                                'required' => false,
                                'validators' => [],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'stock_quantity' => [
                                'name'     => 'stock_quantity',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsInt',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_variant_prices_variant_price_digit_invalid',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => '\Laminas\Validator\GreaterThan',
                                        'options' => [
                                            'min' => -1,
                                            'messages' => [
                                                \Laminas\Validator\GreaterThan::NOT_GREATER => 'tr_meliscommerce_variant_prices_variant_price_digit_greater',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ],
    ],
];
