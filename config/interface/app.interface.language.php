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
                'name' => 'tr_meliscommerce_language',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/ecomLanguage.tool.js',
                ],
                'css' => [],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_language_list' => [
                    'interface' => [
                        'meliscommerce_language_list_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_language_list_container',
                                'melisKey' => 'meliscommerce_language_list_container',
                                'name' => 'tr_meliscommerce_language',
                                'icon' => 'fa fa-language',
                            ],
                        ],
			            // LANGUAGE TOOL
			            'meliscommerce_language_list_container' => [
			                'conf' => [
			                    'id' => 'id_meliscommerce_language_list_container',
			                    'name' => 'tr_meliscommerce_language',
			                    'melisKey' => 'meliscommerce_language_list_container',
			                    'icon' => 'fa-language',
			                    'rights_checkbox_disable' => true,
			                ],
			                'forward' => [
			                    'module' => 'MelisCommerce',
			                    'controller' => 'MelisComLanguage',
			                    'action' => 'render-language-list-page',
			                    'jscallback' => '',
			                    'jsdatas' => []
			                ],
			                'interface' => [
			                    'meliscommerce_language_list_page_header' => [
			                        'conf' => [
			                            'id' => 'id_meliscommerce_language_list_page_header',
			                            'name' => 'tr_meliscommerce_language_header',
			                            'melisKey' => 'meliscommerce_language_list_page_header',
			                        ],
					                'forward' => [
        			                    'module' => 'MelisCommerce',
        			                    'controller' => 'MelisComLanguage',
        			                    'action' => 'render-language-list-page-header',
					                    'jscallback' => '',
					                    'jsdatas' => []
					                ],
			                        'interface' => [
			                             'meliscommerce_language_list_page_header_add' => [
			                                 'conf' => [
			                                     'id' => 'id_meliscommerce_language_list_page_header_add',
			                                     'name' => 'tr_meliscommerce_language_header_add',
			                                     'melisKey' => 'meliscommerce_language_list_page_header_add',
			                                 ],
			                                 'forward' => [
			                                     'module' => 'MelisCommerce',
			                                     'controller' => 'MelisComLanguage',
			                                     'action' => 'render-language-list-page-header-add',
			                                     'jscallback' => '',
			                                     'jsdatas' => []
			                                 ],
			                             ],
			                        ],
			                    ],

			                    'meliscommerce_language_list_page_content' => [
			                        'conf' => [
			                            'id' => 'id_meliscommerce_language_list_page_content',
			                            'name' => 'tr_meliscommerce_language_content',
			                            'melisKey' => 'render-language-list-page-content',
			                        ],
			                        'forward' => [
                                         'module' => 'MelisCommerce',
                                         'controller' => 'MelisComLanguage',
                                         'action' => 'render-language-list-page-content',
                                         'jscallback' => '',
                                         'jsdatas' => []
			                        ],
			                    ],

			                    'meliscommerce_language_list_page_content_modal_container' => [
			                        'conf' => [
			                            'id' => 'id_meliscommerce_language_list_page_content_modal_container',
			                            'melisKey' => 'meliscommerce_language_list_page_content_modal_container',
			                            'name' => 'tr_meliscommerce_language_modal'
			                        ],
			                        'forward' => [
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComLanguage',
			                            'action' => 'render-language-list-page-modal-container',

			                        ],
			                        'interface' => [
			                            'meliscommerce_language_list_page_content_modal_form' => [
			                                'conf' => [
			                                    'id' => 'id_meliscommerce_language_list_page_content_modal_form',
			                                    'melisKey' => 'meliscommerce_language_list_page_content_modal_form',
			                                    'name' => 'tr_meliscommerce_language_modal'
			                                ],
			                                'forward' => [
			                                    'module' => 'MelisCommerce',
			                                    'controller' => 'MelisComLanguage',
			                                    'action' => 'render-language-list-page-modal-form',
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