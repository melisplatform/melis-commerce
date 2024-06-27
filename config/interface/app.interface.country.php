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
                'id' => '',
                'name' => 'tr_meliscommerce_country',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/ecomCountry.tool.js',
                ],
                'css' => [],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_country_list' => [
                    'interface' => [
                        'meliscommerce_country_list_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_country_list_container',
                                'melisKey' => 'meliscommerce_country_list_container',
                                'name' => 'tr_meliscommerce_countries',
                                'icon' => 'fa fa-globe',
                            ],
                        ],
			            // country TOOL
			            'meliscommerce_country_list_container' => [
			                'conf' => [
			                    'id' => 'id_meliscommerce_country_list_container',
			                    'name' => 'tr_meliscommerce_country',
			                    'melisKey' => 'meliscommerce_country_list_container',
			                    'icon' => 'fa-country',
			                    'rights_checkbox_disable' => true,
			                ],
			                'forward' => [
			                    'module' => 'MelisCommerce',
			                    'controller' => 'MelisComCountry',
			                    'action' => 'render-country-list-page',
			                    'jscallback' => '',
			                    'jsdatas' => []
			                ],
			                'interface' => [
			                    'meliscommerce_country_list_page_header' => [
			                        'conf' => [
			                            'id' => 'id_meliscommerce_country_list_page_header',
			                            'name' => 'tr_meliscommerce_country_header',
			                            'melisKey' => 'meliscommerce_country_list_page_header',
			                        ],
			                        'forward' => [
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComCountry',
			                            'action' => 'render-country-list-page-header',
			                            'jscallback' => '',
			                            'jsdatas' => []
			                        ],
			                        'interface' => [
			                            'meliscommerce_country_list_page_header_add' => [
			                                'conf' => [
			                                    'id' => 'id_meliscommerce_country_list_page_header_add',
			                                    'name' => 'tr_meliscommerce_country_add_country',
			                                    'melisKey' => 'meliscommerce_country_list_page_header_add',
			                                ],
			                                'forward' => [
			                                    'module' => 'MelisCommerce',
			                                    'controller' => 'MelisComCountry',
			                                    'action' => 'render-country-list-page-header-add',
			                                    'jscallback' => '',
			                                    'jsdatas' => []
			                                ],
			                            ],
			                        ],
			                    ],
			                    'meliscommerce_country_list_page_content' => [
			                        'conf' => [
			                            'id' => 'id_meliscommerce_country_list_page_content',
			                            'name' => 'tr_meliscommerce_country_content',
			                            'melisKey' => 'meliscommerce_country_list_page_content',
			                        ],
			                        'forward' => [
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComCountry',
			                            'action' => 'render-country-list-page-content',
			                            'jscallback' => '',
			                            'jsdatas' => []
			                        ],
			                        'interface' => [
			                             
			                        ],
			                    ],
			                    
			                    'meliscommerce_country_list_page_content_modal_container' => [
			                        'conf' => [
			                            'id' => 'id_meliscommerce_country_list_page_content_modal_container',
			                            'melisKey' => 'meliscommerce_country_list_page_content_modal_container',
			                            'name' => 'tr_meliscommerce_country_modal'
			                        ],
			                        'forward' => [
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComCountry',
			                            'action' => 'render-country-list-page-modal-container',
			                             
			                        ],
			                        'interface' => [
			                            'meliscommerce_country_list_page_content_modal_form' => [
			                                'conf' => [
			                                    'id' => 'id_meliscommerce_country_list_page_content_modal_form',
			                                    'melisKey' => 'meliscommerce_country_list_page_content_modal_form',
			                                    'name' => 'tr_meliscommerce_country_modal'
			                                ],
			                                'forward' => [
			                                    'module' => 'MelisCommerce',
			                                    'controller' => 'MelisComCountry',
			                                    'action' => 'render-country-list-page-modal-form',
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