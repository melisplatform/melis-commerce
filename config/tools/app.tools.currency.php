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
            'tools' => [
                'meliscommerce_currency' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_currencies',  
                    ],
                    'table' => [
                        'target' => '#tableComCurrencyList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCurrency/getComCurrencyData',
                        'dataFunction' => '',
                        'ajaxCallback' => 'reInitTableEcomCurrency();',
                        'filters' => [
                            'left' => [
                                'country-table-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCurrency',
                                    'action' => 'render-currency-table-filter-limit',
                                ],
                            ],
                            'center' => [
                                'country-table-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCurrency',
                                    'action' => 'render-currency-table-filter-search',
                                ],
                            ],
                            'right' => [
                                'country-table-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCurrency',
                                    'action' => 'render-currency-table-filter-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'cur_id' => [
                                'text' => 'tr_meliscommerce_currency_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                        
                            ],
                            'cur_default' => [
                                'text' => 'tr_meliscommerce_currency_default',
                                'css' => ['width' => '3%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'cur_status' => [
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'cur_symbol' => [
                                'text' => 'tr_meliscommerce_currency_symbol',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cur_code' => [
                                'text' => 'tr_meliscommerce_currency_code',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cur_name' => [
                                'text' => 'tr_meliscommerce_currency_name',
                                'css' => ['width' => '49%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],

                        ],
                        'searchables' => ['cur_id', 'cur_name', 'cur_code', 'cur_symbol'],
                        'actionButtons' => [
                            'default' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-content-action-default',
                            ],
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-content-action-edit',
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-content-action-delete',
                            ],
                        ],
                    ],
                    'modals' => [],
                    'forms' => [
                        'meliscommerce_currency_form' => [
                            'attributes' => [
                                'name' => 'ecomCurrencyForm',
                                'id' => 'ecomCurrencyForm',
                                'method' => 'POST',
                                'action' => '',
                            ],
                            'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                            'elements' => [
                                [
                                    'spec' => [
                                        'name' => 'cur_id',
                                        'type' => 'hidden',
                                        'options' => [
                                        ],
                                        'attributes' => [
                                            'id' => 'id_cur_id',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'tmp_cur_code',
                                        'type' => 'hidden',
                                        'options' => [
                                        ],
                                        'attributes' => [
                                            'id' => 'id_tmp_cur_code',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'cur_code',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_currency_code',
                                            'tooltip' => 'tr_meliscommerce_currency_code tooltip',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_cur_name',
                                            'value' => '',
                                            'required' => 'required',
                                        ],
                                    ],
                                ],
                                
                                [
                                    'spec' => [
                                        'name' => 'cur_name',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_currency_name',
                                            'tooltip' => 'tr_meliscommerce_currency_name tooltip',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_cur_name',
                                            'value' => '',
                                            'required' => 'required',
                                        ],
                                    ],
                                ],
                                
                                [
                                    'spec' => [
                                        'name' => 'cur_symbol',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_currency_symbol',
                                            'tooltip' => 'tr_meliscommerce_currency_symbol tooltip',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_cur_symbol',
                                            'value' => '',
                                            'required' => 'required',
                                        ],
                                    ],
                                ],

                            ],
                            'input_filter' => [
                                'cur_code' => [
                                    'name'     => 'cur_code',
                                    'required' => true,
                                    'validators' => [
                                        [
                                            'name'    => 'StringLength',
                                            'options' => [
                                                'encoding' => 'UTF-8',
                                                'max'      => 8,
                                                'messages' => [
                                                    \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_currency_code_long',
                                                ],
                                            ],
                                        ],
                                        [
                                            'name' => 'NotEmpty',
                                            'options' => [
                                                'messages' => [
                                                    \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_currency_code_empty',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'filters'  => [
                                        ['name' => 'StripTags'],
                                        ['name' => 'StringTrim'],
                                    ],
                                ],
                                'cur_name' => [
                                    'name'     => 'cur_name',
                                    'required' => true,
                                    'validators' => [
                                        [
                                            'name'    => 'StringLength',
                                            'options' => [
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => [
                                                    \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_currency_name_long',
                                                ],
                                            ],
                                        ],
                                        [
                                            'name' => 'NotEmpty',
                                            'options' => [
                                                'messages' => [
                                                    \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_currency_name_empty',
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
                                            'name'    => 'StringLength',
                                            'options' => [
                                                'encoding' => 'UTF-8',
                                                'max'      => 5,
                                                'messages' => [
                                                    \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_currency_symbol_long',
                                                ],
                                            ],
                                        ],
                                        [
                                            'name' => 'NotEmpty',
                                            'options' => [
                                                'messages' => [
                                                    \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_currency_symbol_empty',
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
    ],
];