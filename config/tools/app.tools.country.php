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
                'meliscommerce_country' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_countries',
                    ],
                    'table' => [
                        'target' => '#tableComCountryList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCountry/getComCountryData',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'country-table-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCountry',
                                    'action' => 'render-country-list-page-table-filter-limit',
                                ],
                            ],
                            'center' => [
                                'country-table-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCountry',
                                    'action' => 'render-country-list-page-table-filter-search',
                                ],
                            ],
                            'right' => [
                                'country-table-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCountry',
                                    'action' => 'render-country-list-page-table-filter-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'ctry_id' => [
                                'text' => 'tr_meliscommerce_country_ctry_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],
                            'ctry_flag' => [
                                'text' => 'tr_meliscommerce_country_ctry_flag',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,

                            ],
                            'ctry_status' => [
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'ctry_name' => [
                                'text' => 'tr_meliscommerce_country_ctry_name',
                                'css' => ['width' => '40%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],
                            'cur_name' => [
                                'text' => 'tr_meliscommerce_country_ctry_currency_id',
                                'css' => ['width' => '40%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],
                        ],
                        'searchables' => ['melis_ecom_country.ctry_id', 'melis_ecom_country.ctry_name', 'melis_ecom_currency.cur_name', 'melis_ecom_currency.cur_symbol'],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCountry',
                                'action' => 'render-country-list-page-content-action-edit',
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCountry',
                                'action' => 'render-country-list-page-content-action-delete',
                            ],
                        ],
                    ],
                    'modals' => [],
                    'forms' => [
                        'meliscommerce_country_form' => [
                            'attributes' => [
                                'name' => 'ecomCountryform',
                                'id' => 'ecomCountryform',
                                'method' => 'POST',
                                'action' => '',
                            ],
                            'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                            'elements' => [
                                [
                                    'spec' => [
                                        'name' => 'ctry_id',
                                        'type' => 'hidden',
                                        'options' => [
                                        ],
                                        'attributes' => [
                                            'id' => 'id_lang_id',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'tmp_ctry_name',
                                        'type' => 'hidden',
                                        'options' => [
                                        ],
                                        'attributes' => [
                                            'id' => 'id_tmp_ctry_name',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'ctry_flag',
                                        'type' => 'file',
                                        'options' => [
                                            //'label' => 'tr_meliscommerce_country_ctry_flag',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_ctry_flag',
                                            'value' => '',
                                            'placeholder' => 'tr_meliscommerce_country_ctry_flag_choose',
                                            'data-buttonText' => 'Select Flag',
                                            'class' => 'filestyle',
                                            'onchange' => 'imagePreview("#imgCountryFlag", this);',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'ctry_name',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_country_ctry_name',
                                            'tooltip' => 'tr_meliscommerce_country_ctry_name tooltip',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_lang_name',
                                            'value' => '',
                                            'required' => 'required',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'ctry_currency_id',
                                        'type' => 'EcomCurrencyAllStatusSelect',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_country_ctry_select_empty',
                                            'tooltip' => 'tr_meliscommerce_country_ctry_select_empty tooltip',
                                            'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                            'disable_inarray_validator' => true,
                                        ],
                                        'attributes' => [
                                            'id' => 'id_lang_locale',
                                            'value' => '',
                                        ],
                                    ],
                                ],
                            ],
                            'input_filter' => [
                                'ctry_name' => [
                                    'name'     => 'ctry_name',
                                    'required' => true,
                                    'validators' => [
                                        [
                                            'name'    => 'StringLength',
                                            'options' => [
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => [
                                                    \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_country_ctry_name_long',
                                                ],
                                            ],
                                        ],
                                        [
                                            'name' => 'NotEmpty',
                                            'options' => [
                                                'messages' => [
                                                    \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_country_ctry_name_empty',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'filters'  => [
                                        ['name' => 'StripTags'],
                                        ['name' => 'StringTrim'],
                                    ],
                                ],
                                'ctry_currency_id' => [
                                    'name'     => 'ctry_currency_id',
                                    'required' => false,
                                    'validators' => [
                                        [
                                            'name'    => 'StringLength',
                                            'options' => [
                                                'encoding' => 'UTF-8',
                                                'max'      => 11,
                                                'messages' => [
                                                    \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_country_ctry_currency_id_long',
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