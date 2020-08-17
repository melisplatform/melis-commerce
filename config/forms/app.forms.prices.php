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
                'meliscommerce_prices' => [
                    'meliscommerce_prices_form' => [
                        'attributes' => [
                            'name' => 'priceForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => 'melis/MelisCommerce/MelisComVariant/savePricesForm',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'price_id',
                                    'type' => 'Hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'price_country_id',
                                    'type' => 'Hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'price_currency',
                                    'type' => 'Hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'price_net',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_label',
                                        'tooltip' => 'tr_meliscommerce_variant_prices_variant_price_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '10'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'price_gross',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_gross_label',
                                        'tooltip' => 'tr_meliscommerce_variant_prices_variant_price_gross_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '10'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'price_vat_percent',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_vat_percent_label',
                                        'tooltip' => 'tr_meliscommerce_variant_prices_variant_price_vat_percent_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '10'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'price_vat_price',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_vat_price_label',
                                        'tooltip' => 'tr_meliscommerce_variant_prices_variant_price_vat_price_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '10'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'price_other_tax_price',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_other_tax_price_label',
                                        'tooltip' => 'tr_meliscommerce_variant_prices_variant_price_other_tax_price_label tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'maxlength' => '10'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'price_id' => [
                                'name'     => 'price_id',
                                'required' => false,
                                'validators' => [],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'price_country_id' => [
                                'name'     => 'price_country_id',
                                'required' => false,
                                'validators' => [],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'price_currency' => [
                                'name'     => 'price_currency',
                                'required' => false,
                                'validators' => [],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'price_net' => [
                                'name'     => 'price_net',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => [
                                            'messages' => [
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_gross' => [
                                'name'     => 'price_gross',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => [
                                            'messages' => [
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_vat_percent' => [
                                'name'     => 'price_vat_percent',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => [
                                            'messages' => [
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_vat_price' => [
                                'name'     => 'price_vat_price',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => [
                                            'messages' => [
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_other_tax_price' => [
                                'name'     => 'price_other_tax_price',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => [
                                            'messages' => [
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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