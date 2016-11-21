<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => 'id_meliscommerce_currency',
                'name' => 'tr_meliscommerce_currency',
            ),  
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/ecomCurrency.tool.js',
                ),
                'css' => array(),
            ),
            'interface' => array(
                'meliscommerce_currency_lists' => array(
                    'interface' => array(
                        'meliscommerce_currency_left_menu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_currency_conf',
                                'melisKey' => 'meliscommerce_currency_conf',
                                'name' => 'tr_meliscommerce_currencies',
                                'icon' => 'fa fa-euro',
                                'rights_checkbox_disable' => true,
                            ),
                        ),
                        'meliscommerce_currency_conf' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_currency_conf',
                                'melisKey' => 'meliscommerce_currency_conf',
                                'name' => 'tr_meliscommerce_currency',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-container',
                            ),
                            'interface' => array(
                                'meliscommerce_currency_header'=> array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_currency_header',
                                        'melisKey' => 'meliscommerce_currency_header',
                                        'name' => 'tr_meliscommerce_currency_header',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCurrency',
                                        'action' => 'render-currency-header',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_currency_header_add'=> array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_currency_header_add',
                                                'melisKey' => 'meliscommerce_currency_header_add',
                                                'name' => 'tr_meliscommerce_currency_form_add',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCurrency',
                                                'action' => 'render-currency-header-add',
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_currency_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_currency_content',
                                        'melisKey' => 'meliscommerce_currency_content',
                                        'name' => 'tr_meliscommerce_currency_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCurrency',
                                        'action' => 'render-currency-content',
                                    ),
                                    'interface' => array(
                                        
                                    ),
                                ),
                                
                                'meliscommerce_currency_content_modal_container' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_currency_content_modal_container',
                                        'melisKey' => 'meliscommerce_currency_content_modal_container',
                                        'name' => 'tr_meliscommerce_currency_modal'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCurrency',
                                        'action' => 'render-currency-modal-container',
                                
                                    ),
                                    'interface' => array(
                                        'meliscommerce_currency_content_modal_form' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_currency_content_modal_form',
                                                'melisKey' => 'meliscommerce_currency_content_modal_form',
                                                'name' => 'tr_meliscommerce_currency_modal'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCurrency',
                                                'action' => 'render-currency-modal-form',
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