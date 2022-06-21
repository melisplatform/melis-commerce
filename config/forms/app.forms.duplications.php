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
                'meliscommerce_duplications' => [
                    'meliscommerce_duplications_sku_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'var_sku',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_duplication_Var_sku',
                                    ],
                                    'attributes' => [
                                        'id' => 'var_sku',
                                    ]
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'var_id',
                                    'type' => 'hidden',
                                    'options' => [],
                                    'attributes' => [
                                        'id' => 'var_id',
                                    ]
                                ]
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
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_duplication_input_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_duplication_input_too_long_100',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ]
                    ],
                ]
            ]
        ]
    ]
];