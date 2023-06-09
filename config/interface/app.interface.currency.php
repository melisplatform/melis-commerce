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
            'conf' => [
                'id' => 'id_meliscommerce_currency',
                'name' => 'tr_meliscommerce_currency',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/ecomCurrency.tool.js',
                ],
                'css' => [],
            ],
            'interface' => [
                'meliscommerce_currency_lists' => [
                    'interface' => [
                        'meliscommerce_currency_left_menu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_currency_conf',
                                'melisKey' => 'meliscommerce_currency_conf',
                                'name' => 'tr_meliscommerce_currencies',
                                'icon' => 'fa fa-euro',
                                'rights_checkbox_disable' => true,
                            ],
                            'interface' => [
                                'meliscommerce_currency_conf' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_currency_lists/interface/meliscommerce_currency_conf',
                                        
                                    ],
                                ]
                            ]
                        ],
                        'meliscommerce_currency_conf' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_currency_conf',
                                'melisKey' => 'meliscommerce_currency_conf',
                                'name' => 'tr_meliscommerce_currency',
                                'icon' => 'fa fa-euro',

                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-container',
                            ],
                            'interface' => [
                                'meliscommerce_currency_header'=> [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_currency_header',
                                        'melisKey' => 'meliscommerce_currency_header',
                                        'name' => 'tr_meliscommerce_currency_header',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCurrency',
                                        'action' => 'render-currency-header',
                                    ],
                                    'interface' => [
                                        'meliscommerce_currency_header_add'=> [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_currency_header_add',
                                                'melisKey' => 'meliscommerce_currency_header_add',
                                                'name' => 'tr_meliscommerce_currency_form_add',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCurrency',
                                                'action' => 'render-currency-header-add',
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_currency_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_currency_content',
                                        'melisKey' => 'meliscommerce_currency_content',
                                        'name' => 'tr_meliscommerce_currency_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCurrency',
                                        'action' => 'render-currency-content',
                                    ],
                                    'interface' => [
                                        
                                    ],
                                ],
                                
                                'meliscommerce_currency_content_modal_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_currency_content_modal_container',
                                        'melisKey' => 'meliscommerce_currency_content_modal_container',
                                        'name' => 'tr_meliscommerce_currency_modal'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCurrency',
                                        'action' => 'render-currency-modal-container',
                                
                                    ],
                                    'interface' => [
                                        'meliscommerce_currency_content_modal_form' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_currency_content_modal_form',
                                                'melisKey' => 'meliscommerce_currency_content_modal_form',
                                                'name' => 'tr_meliscommerce_currency_modal'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCurrency',
                                                'action' => 'render-currency-modal-form',
                                                'jscallback' => 'initProductSwitch();'
                                            ],
                                        ]
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];