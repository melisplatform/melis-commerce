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
                'meliscommerce_language' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_language',
                    ],
                    'table' => [
                        'target' => '#tableComLanguageList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComLanguage/getComLangData',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'language-table-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComLanguage',
                                    'action' => 'render-language-list-page-table-filter-limit',
                                ],
                            ],
                            'center' => [
                                'language-table-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComLanguage',
                                    'action' => 'render-language-list-page-table-filter-search',
                                ],
                            ],
                            'right' => [
                                'language-table-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComLanguage',
                                    'action' => 'render-language-list-page-table-filter-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'elang_id' => [
                                'text' => 'tr_meliscommerce_language_elang_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],
                            'elang_flag' => [
                                'text' => 'tr_meliscommerce_country_ctry_flag',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'elang_status' => [
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'elang_locale' => [
                                'text' => 'tr_meliscommerce_language_elang_locale',
                                'css' => ['width' => '40%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],
                            'elang_name' => [
                                'text' => 'tr_meliscommerce_language_elang_name',
                                'css' => ['width' => '40%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],
                        ],
                        'searchables' => ['elang_id', 'elang_locale', 'elang_name'],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComLanguage',
                                'action' => 'render-language-list-page-content-action-edit',
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComLanguage',
                                'action' => 'render-language-list-page-content-action-delete',
                            ],
                        ],
                    ],
                    'modals' => [],
                    'forms' => [
                        'meliscommerce_language_form' => [
                            'attributes' => [
                                'name' => 'ecomlanguageform',
                                'id' => 'ecomlanguageform',
                                'method' => 'POST',
                                'action' => '',
                            ],
                            'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                            'elements' => [
                                [
                                    'spec' => [
                                        'name' => 'elang_id',
                                        'type' => 'hidden',
                                        'options' => [
                                            //'label' => 'tr_meliscommerce_language_elang_id',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_lang_id',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'elang_flag',
                                        'type' => 'file',
                                        'options' => [
                                            //'label' => 'tr_meliscommerce_country_ctry_flag',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_elang_flag',
                                            'value' => '',
                                            'placeholder' => 'tr_meliscommerce_country_ctry_flag_choose',
                                            'data-buttonText' => 'Select Flag',
                                            'class' => 'filestyle',
                                            'onchange' => 'imagePreview("#imgLangFlag", this);',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'elang_name',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_language_elang_name',
                                            'tooltip' => 'tr_meliscommerce_language_elang_name tooltip',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_lang_name',
                                            'value' => '',
                                            'required' => 'required'
                                        ],
                                    ],
                                ],

                                [
                                    'spec' => [
                                        'name' => 'tmp_elang_name',
                                        'type' => 'hidden',
                                        'options' => [
                                        ],
                                        'attributes' => [
                                            'id' => 'id_tmp_elang_name',
                                            'value' => '',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'elang_locale',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_language_elang_locale',
                                            'tooltip' => 'tr_meliscommerce_language_elang_locale tooltip',
                                        ],
                                        'attributes' => [
                                            'id' => 'id_lang_locale',
                                            'value' => '',
                                            'required' => 'required'
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'tmp_elang_locale',
                                        'type' => 'hidden',
                                        'options' => [
                                        ],
                                        'attributes' => [
                                            'id' => 'id_tmp_lang_locale',
                                            'value' => '',
                                        ],
                                    ],
                                ],
                            ],
                            'input_filter' => [
                                'elang_locale' => [
                                    'name'     => 'elang_locale',
                                    'required' => true,
                                    'validators' => [
                                        [
                                            'name'    => 'StringLength',
                                            'options' => [
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => [
                                                    \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscore_tool_language_lang_locale_long',
                                                ],
                                            ],
                                        ],
                                        [
                                            'name' => 'NotEmpty',
                                            'options' => [
                                                'messages' => [
                                                    \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscore_tool_language_lang_locale_empty',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'filters'  => [
                                        ['name' => 'StripTags'],
                                        ['name' => 'StringTrim'],
                                    ],
                                ],
                                'elang_name' => [
                                    'name'     => 'elang_name',
                                    'required' => true,
                                    'validators' => [
                                        [
                                            'name'    => 'StringLength',
                                            'options' => [
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => [
                                                    \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscore_tool_language_lang_name_long',
                                                ],
                                            ],
                                        ],
                                        [
                                            'name' => 'NotEmpty',
                                            'options' => [
                                                'messages' => [
                                                    \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscore_tool_language_lang_name_empty',
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