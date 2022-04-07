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
                'meliscommerce_coupon' => [
                    'meliscommerce_coupon_general_data' => [
                        'attributes' => [
                            'name' => 'coupon',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'coup_code',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_code',
                                        'tooltip' => 'tr_meliscommerce_coupon_code tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'coup_code',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'coup_date_valid_start',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_date_start',
                                        'tooltip' => 'tr_meliscommerce_coupon_date_start tooltip',
                                        'class' => 'd-flex flex-row justify-content-between'
                                    ],
                                    'attributes' => [
                                        'dateId' => 'couponStart',
                                        'dateLabel' => 'tr_meliscommerce_coupon_date_start',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'coup_id',
                                    'type' => 'hidden',
                                    'options' => [
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'coup_date_valid_end',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_date_end',
                                        'tooltip' => 'tr_meliscommerce_coupon_date_end tooltip',
                                        'class' => 'd-flex flex-row justify-content-between'
                                    ],
                                    'attributes' => [
                                        'dateId' => 'couponEnd',
                                        'dateLabel' => 'tr_meliscommerce_coupon_date_end',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'coup_code' => [
                                'name'     => 'coup_code',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
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
                    'meliscommerce_coupon_values' => [
                        'attributes' => [
                            'name' => 'couponValues',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'coup_percentage',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_percent',
                                        'tooltip' => 'tr_meliscommerce_coupon_percent tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'coup_discount_value',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_discount',
                                        'tooltip' => 'tr_meliscommerce_coupon_discount tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'coup_current_use_number',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_used',
                                        'tooltip' => 'tr_meliscommerce_coupon_used tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'coup_max_use_number',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_coupon_max',
                                        'tooltip' => 'tr_meliscommerce_coupon_max tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'coup_percentage' => [
                                'name'     => 'coup_percentage',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsFloat',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsFloat::NOT_FLOAT  => 'tr_meliscommerce_coupon_input_invalid_decimal'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'coup_discount_value' => [
                                'name'     => 'coup_discount_value',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => '\Laminas\I18n\Validator\IsFloat',
                                        'options' => [
                                            'messages'=> [
                                                \Laminas\I18n\Validator\IsFloat::NOT_FLOAT  => 'tr_meliscommerce_coupon_input_invalid_decimal'
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'coup_current_use_number' => [
                                'name'     => 'coup_current_use_number',
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
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'coup_max_use_number' => [
                                'name'     => 'coup_max_use_number',
                                'required' => true,
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
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
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
