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
                'meliscommerce_currency' => [
                    'attributes' => [
                        'name' => '',
                        'id' => 'categoryTreeViewSearchForm',
                        'method' => '',
                        'action' => '',
                    ],
                    'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                    'elements' => [
                        [
                            'spec' => [
                                'name' => 'cur_id',
                                'type' => 'hidden',
                            ],
                        ],
                        [
                            'spec' => [
                                'name' => 'cur_name',
                                'type' => 'MelisText',
                                'options' => [
                                    'label' => 'tr_meliscommerce_seo_Page_id',
                                ],
                                'attributes' => [
                                    'id' => 'cur_name',
                                ],
                            ],
                        ],
                        [
                            'spec' => [
                                'name' => 'cur_code',
                                'type' => 'MelisText',
                                'options' => [
                                    'label' => 'tr_meliscommerce_seo_Page_id',
                                ],
                                'attributes' => [
                                    'id' => 'cur_code',
                                ],
                            ],
                        ],
                        [
                            'spec' => [
                                'name' => 'cur_symbol',
                                'type' => 'MelisText',
                                'options' => [
                                    'label' => 'tr_meliscommerce_seo_Page_id',
                                ],
                                'attributes' => [
                                    'id' => 'cur_symbol',
                                ],
                            ],
                        ],
                    ],
                    'input_filter' => [
                        'cur_name' => [
                            'name'     => 'cur_name',
                            'required' => true,
                            'validators' => [
                                [
                                    'name' => 'NotEmpty',
                                    'options' => [
                                        'messages' => [
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_seo_Seo_input_empty',
                                        ],
                                    ],
                                ],
                            ],
                            'filters'  => [
                                ['name' => 'StripTags'],
                                ['name' => 'StringTrim'],
                            ],
                        ],
                        'cur_code' => [
                            'name'     => 'cur_code',
                            'required' => true,
                            'validators' => [
                                [
                                    'name' => 'NotEmpty',
                                    'options' => [
                                        'messages' => [
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_seo_Seo_input_empty',
                                        ],
                                    ],
                                ],
                            ],
                            'filters'  => [
                                ['name' => 'StripTags'],
                                ['name' => 'StringTrim'],
                            ],
                        ],
                        'cur_symbol' => [
                            'name'     => 'cur_symbol',
                            'required' => true,
                            'validators' => [
                                [
                                    'name' => 'NotEmpty',
                                    'options' => [
                                        'messages' => [
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_seo_Seo_input_empty',
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
];