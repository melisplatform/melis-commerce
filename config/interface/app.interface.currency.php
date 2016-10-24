<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => 'id_meliscommerce_currency',
                'name' => 'tr_meliscommerce_currency',
            ),  
            'ressources' => array(
                
            ),
            'interface' => array(
                'meliscommerce_currency_lists' => array(
                    'interface' => array(
                        'meliscommerce_currency_left_menu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_currency_left_menu',
                                'melisKey' => 'meliscommerce_currency_conf',
                                'name' => 'tr_meliscommerce_currency',
                                'icon' => 'fa fa-globe',
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
                                'action' => 'container',
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
                                        'action' => 'header',
                                    ),
                                    'interface' => array(
                                        
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
                                        'action' => 'content',
                                    ),
                                    'interface' => array(
                                        
                                    ),
                                ),
                                'meliscommerce_currency_modal' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_currency_modal',
                                        'melisKey' => 'meliscommerce_currency_modal',
                                        'name' => 'tr_meliscommerce_currency_modal',
                                    ),
                                    'forward' => array(
                                    
                                    ),
                                    'interface' => array(
                                    
                                    ),
                                ),
                            ),
                            
                        ),
                    ),
                ),
            ),
        
        ),
    ),
);