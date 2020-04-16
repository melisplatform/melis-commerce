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
                'meliscommerce_categories' => [
                    'meliscommerce_categories_search_input' => [
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
                                    'name' => 'categoryTreeViewSearchInput',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => '',
                                    ],
                                    'attributes' => [
                                        'id' => 'categoryTreeViewSearchInput',
                                        'placeholder' => 'tr_meliscommerce_categories_list_tree_view_search_input',
                                    ]
                                ]
                            ],
                        ]
                    ],
                    'meliscommerce_categories_category_information_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => 'categoryMainInformationForm',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'catt_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'catt_lang_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'catt_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_categories_category_information_form_cat_name',
                                        'tooltip' => 'tr_meliscommerce_categories_category_information_form_cat_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'catt_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'catt_description',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_categories_category_information_form_cat_desc',
                                        'tooltip' => 'tr_meliscommerce_categories_category_information_form_cat_desc tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'catt_description',
                                        'class' => 'form-control editme',
                                        'rows' => 10
                                    ]
                                ]
                            ],
                        ],
                        'input_filter' => [
                            'catt_lang_id' => [
                                'name'     => 'catt_lang_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_categories_input_empty',
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
                    'meliscommerce_categories_date_validty_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cat_date_valid_start',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_categories_category_valid_from',
                                        'tooltip' => 'tr_meliscommerce_categories_category_valid_from tooltip',
                                        'class' => 'd-flex flex-row justify-content-between'
                                    ],
                                    'attributes' => [
                                        'dateId' => 'categoryValidateDates',
                                        'dateLabel' => 'tr_meliscommerce_categories_category_valid_from',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cat_date_valid_end',
                                    'type' => 'DateField',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_categories_category_valid_to',
                                        'tooltip' => 'tr_meliscommerce_categories_category_valid_to tooltip',
                                        'class' => 'd-flex flex-row justify-content-between'
                                    ],
                                    'attributes' => [
                                        'dateId' => 'categoryValidateDates',
                                        'dateLabel' => 'tr_meliscommerce_categories_category_valid_to',
                                    ]
                                ]
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],
];
