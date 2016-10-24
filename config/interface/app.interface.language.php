<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_language',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/ecomLanguage.tool.js',
                ),
                'css' => array(),
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_language_list' => array(
                    'interface' => array(
                        'meliscommerce_language_list_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_language_list_container',
                                'melisKey' => 'meliscommerce_language_list_container',
                                'name' => 'tr_meliscommerce_language',
                                'icon' => 'fa fa-language',
                            ),
                        ),
			            // LANGUAGE TOOL
			            'meliscommerce_language_list_container' => array(
			                'conf' => array(
			                    'id' => 'id_meliscommerce_language_list_container',
			                    'name' => 'tr_meliscommerce_language',
			                    'melisKey' => 'meliscommerce_language_list_container',
			                    'icon' => 'fa-language',
			                    'rights_checkbox_disable' => true,
			                ),
			                'forward' => array(
			                    'module' => 'MelisCommerce',
			                    'controller' => 'MelisComLanguage',
			                    'action' => 'render-language-list-page',
			                    'jscallback' => '',
			                    'jsdatas' => array()
			                ),
			                'interface' => array(
			                    'meliscommerce_language_list_page_header' => array(
			                        'conf' => array(
			                            'id' => 'id_meliscommerce_language_list_page_header',
			                            'name' => 'tr_meliscommerce_language_header',
			                            'melisKey' => 'meliscommerce_language_list_page_header',
			                        ),
					                'forward' => array(
        			                    'module' => 'MelisCommerce',
        			                    'controller' => 'MelisComLanguage',
        			                    'action' => 'render-language-list-page-header',
					                    'jscallback' => '',
					                    'jsdatas' => array()
					                ),
			                        'interface' => array(
			                             'meliscommerce_language_list_page_header_add' => array(
			                                 'conf' => array(
			                                     'id' => 'id_meliscommerce_language_list_page_header_add',
			                                     'name' => 'tr_meliscommerce_language_header_add',
			                                     'melisKey' => 'meliscommerce_language_list_page_header_add',
			                                 ),
			                                 'forward' => array(
			                                     'module' => 'MelisCommerce',
			                                     'controller' => 'MelisComLanguage',
			                                     'action' => 'render-language-list-page-header-add',
			                                     'jscallback' => '',
			                                     'jsdatas' => array()
			                                 ),
			                             ),
			                        ),
			                    ),
			                    
			                    'meliscommerce_language_list_page_content' => array(
			                        'conf' => array(
			                            'id' => 'id_meliscommerce_language_list_page_content',
			                            'name' => 'tr_meliscommerce_language_content',
			                            'melisKey' => 'render-language-list-page-content',
			                        ),
			                        'forward' => array(
                                         'module' => 'MelisCommerce',
                                         'controller' => 'MelisComLanguage',
                                         'action' => 'render-language-list-page-content',
                                         'jscallback' => '',
                                         'jsdatas' => array()
			                        ),
			                    ),
			                    
			                    'meliscommerce_language_list_page_content_modal_container' => array(
			                        'conf' => array(
			                            'id' => 'id_meliscommerce_language_list_page_content_modal_container',
			                            'melisKey' => 'meliscommerce_language_list_page_content_modal_container',
			                            'name' => 'tr_meliscommerce_language_modal'
			                        ),
			                        'forward' => array(
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComLanguage',
			                            'action' => 'render-language-list-page-modal-container',
			                    
			                        ),
			                        'interface' => array(
			                            'meliscommerce_language_list_page_content_modal_form' => array(
			                                'conf' => array(
			                                    'id' => 'id_meliscommerce_language_list_page_content_modal_form',
			                                    'melisKey' => 'meliscommerce_language_list_page_content_modal_form',
			                                    'name' => 'tr_meliscommerce_language_modal'
			                                ),
			                                'forward' => array(
			                                    'module' => 'MelisCommerce',
			                                    'controller' => 'MelisComLanguage',
			                                    'action' => 'render-language-list-page-modal-form',
			                                ),
			                            )
			                        )
			                    ),
			                ),
			            ),
                    ),
                ),
            ),
        ),
    ),
);