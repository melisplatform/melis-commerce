<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_country',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/ecomCountry.tool.js',
                ),
                'css' => array(),
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_country_list' => array(
                    'interface' => array(
                        'meliscommerce_country_list_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_country_list_container',
                                'melisKey' => 'meliscommerce_country_list_container',
                                'name' => 'tr_meliscommerce_countries',
                                'icon' => 'fa fa-globe',
                            ),
                        ),
			            // country TOOL
			            'meliscommerce_country_list_container' => array(
			                'conf' => array(
			                    'id' => 'id_meliscommerce_country_list_container',
			                    'name' => 'tr_meliscommerce_country',
			                    'melisKey' => 'meliscommerce_country_list_container',
			                    'icon' => 'fa-country',
			                    'rights_checkbox_disable' => true,
			                ),
			                'forward' => array(
			                    'module' => 'MelisCommerce',
			                    'controller' => 'MelisComcountry',
			                    'action' => 'render-country-list-page',
			                    'jscallback' => '',
			                    'jsdatas' => array()
			                ),
			                'interface' => array(
			                    'meliscommerce_country_list_page_header' => array(
			                        'conf' => array(
			                            'id' => 'id_meliscommerce_country_list_page_header',
			                            'name' => 'tr_meliscommerce_country_header',
			                            'melisKey' => 'meliscommerce_country_list_page_header',
			                        ),
			                        'forward' => array(
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComcountry',
			                            'action' => 'render-country-list-page-header',
			                            'jscallback' => '',
			                            'jsdatas' => array()
			                        ),
			                        'interface' => array(
			                            'meliscommerce_country_list_page_header_add' => array(
			                                'conf' => array(
			                                    'id' => 'id_meliscommerce_country_list_page_header_add',
			                                    'name' => 'tr_meliscommerce_country_add_country',
			                                    'melisKey' => 'meliscommerce_country_list_page_header_add',
			                                ),
			                                'forward' => array(
			                                    'module' => 'MelisCommerce',
			                                    'controller' => 'MelisComcountry',
			                                    'action' => 'render-country-list-page-header-add',
			                                    'jscallback' => '',
			                                    'jsdatas' => array()
			                                ),
			                            ),
			                        ),
			                    ),
			                    'meliscommerce_country_list_page_content' => array(
			                        'conf' => array(
			                            'id' => 'id_meliscommerce_country_list_page_content',
			                            'name' => 'tr_meliscommerce_country_content',
			                            'melisKey' => 'meliscommerce_country_list_page_content',
			                        ),
			                        'forward' => array(
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComcountry',
			                            'action' => 'render-country-list-page-content',
			                            'jscallback' => '',
			                            'jsdatas' => array()
			                        ),
			                        'interface' => array(
			                             
			                        ),
			                    ),
			                    
			                    'meliscommerce_country_list_page_content_modal_container' => array(
			                        'conf' => array(
			                            'id' => 'id_meliscommerce_country_list_page_content_modal_container',
			                            'melisKey' => 'meliscommerce_country_list_page_content_modal_container',
			                            'name' => 'tr_meliscommerce_country_modal'
			                        ),
			                        'forward' => array(
			                            'module' => 'MelisCommerce',
			                            'controller' => 'MelisComcountry',
			                            'action' => 'render-country-list-page-modal-container',
			                             
			                        ),
			                        'interface' => array(
			                            'meliscommerce_country_list_page_content_modal_form' => array(
			                                'conf' => array(
			                                    'id' => 'id_meliscommerce_country_list_page_content_modal_form',
			                                    'melisKey' => 'meliscommerce_country_list_page_content_modal_form',
			                                    'name' => 'tr_meliscommerce_country_modal'
			                                ),
			                                'forward' => array(
			                                    'module' => 'MelisCommerce',
			                                    'controller' => 'MelisComcountry',
			                                    'action' => 'render-country-list-page-modal-form',
                                                'jscallback' => 'initProductSwitch();'
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