<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_documents_Documents',
                'rightsDisplay' => 'none',                
            ),
            'ressources' => array(
                'js' => array(),
                'css' => array(),
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_prices_tab' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_prices_tab',
                        'melisKey' => 'meliscommerce_prices_tab',
                        'name' => 'tr_meliscommerce_prices_tab',
                        'icon' => 'glyphicons euro',
                        'href' => 'id_meliscommerce_prices_tab_content',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComPrice',
                        'action' => 'render-prices-tab',
                    ),
                ),
                'meliscommerce_prices_tab_content' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_prices_tab_content',
                        'melisKey' => 'meliscommerce_prices_tab_content',
                        'name' => 'tr_meliscommerce_prices_tab_content',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComPrice',
                        'action' => 'render-prices-tab-content',
                    ),
                    'interface' => array(
                        'meliscommerce_prices_tab_header_container' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_prices_tab_header_container',
                                'melisKey' => 'meliscommerce_prices_tab_header_container',
                                'name' => 'tr_meliscommerce_prices_tab_header_container',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrice',
                                'action' => 'render-prices-tab-content-header-container',
                            ),
                            'interface' => array(
                                'meliscommerce_prices_tab_header_left' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_prices_tab_header_left',
                                        'melisKey' => 'meliscommerce_prices_tab_header_left',
                                        'name' => 'tr_meliscommerce_prices_tab_header_left',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-header-left',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_prices_tab_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_prices_tab_header',
                                                'melisKey' => 'meliscommerce_prices_tab_header',
                                                'name' => 'tr_meliscommerce_prices_tab_header',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-tab-content-header',
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_prices_tab_header_right' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_prices_tab_header_right',
                                        'melisKey' => 'meliscommerce_prices_tab_header_right',
                                        'name' => 'tr_meliscommerce_prices_tab_header_right',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-header-right',
                                    ),
                                    'interface' => array(
                                        
                                    ),
                                ),
                            ),
                        ),
                        'meliscommerce_prices_tab_contents' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_prices_tab_contents',
                                'melisKey' => 'meliscommerce_prices_tab_contents',
                                'name' => 'tr_meliscommerce_prices_tab_contents',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrice',
                                'action' => 'render-prices-tab-content-general-container',
                            ),
                            'interface' => array(
                                'meliscommerce_prices_tab_left_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_prices_tab_left_content',
                                        'melisKey' => 'meliscommerce_prices_tab_left_content',
                                        'name' => 'tr_meliscommerce_prices_tab_left_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-left-container',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_prices_tab_country_heading' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_prices_tab_country_heading',
                                                'melisKey' => 'meliscommerce_prices_tab_country_heading',
                                                'name' => 'tr_meliscommerce_prices_tab_country_heading',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-tab-sub-heading',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_prices_tab_country_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_prices_tab_country_header',
                                                        'melisKey' => 'meliscommerce_prices_tab_country_header',
                                                        'name' => 'tr_meliscommerce_prices_tab_country_header',
                                                        'icon' => 'fa fa-globe',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComPrice',
                                                        'action' => 'render-prices-tab-sub-header',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_prices_country_list' => array(
                                            'conf' => array(                   
                                                'id' => 'id_meliscommerce_prices_country_list',
                                                'melisKey' => 'meliscommerce_prices_country_list',
                                                'name' => 'tr_meliscommerce_prices_country_list',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-country-list',
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_prices_tab_right_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_prices_tab_right_content',
                                        'melisKey' => 'meliscommerce_prices_tab_right_content',
                                        'name' => 'tr_meliscommerce_prices_tab_right_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-right-container',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_prices_tab_country_form_heading' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_prices_tab_country_form_heading',
                                                'melisKey' => 'meliscommerce_prices_tab_country_form_heading',
                                                'name' => 'tr_meliscommerce_prices_tab_country_form_heading',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-tab-sub-heading',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_prices_tab_country_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_prices_tab_country_header',
                                                        'melisKey' => 'meliscommerce_prices_tab_country_header',
                                                        'name' => 'tr_meliscommerce_prices_tab_pricing_general',
                                                        'icon' => 'fa fa-dollar',
                                                        'class' => 'country-price-label',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-sub-header',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_prices_country_form' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_prices_country_form',
                                                'melisKey' => 'meliscommerce_prices_country_form',
                                                'name' => 'tr_meliscommerce_prices_country_form',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-form',
                                            ),
                                        ),
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