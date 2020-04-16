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
                'name' => 'tr_meliscommerce_documents_Documents',
                'rightsDisplay' => 'none',                
            ],
            'ressources' => [
                'js' => [],
                'css' => [],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_prices_tab' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_prices_tab',
                        'melisKey' => 'meliscommerce_prices_tab',
                        'name' => 'tr_meliscommerce_prices_tab',
                        'icon' => 'glyphicons euro',
                        'href' => 'id_meliscommerce_prices_tab_content',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComPrice',
                        'action' => 'render-prices-tab',
                    ],
                ],
                'meliscommerce_prices_tab_content' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_prices_tab_content',
                        'melisKey' => 'meliscommerce_prices_tab_content',
                        'name' => 'tr_meliscommerce_prices_tab_content',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComPrice',
                        'action' => 'render-prices-tab-content',
                    ],
                    'interface' => [
                        'meliscommerce_prices_tab_header_container' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_prices_tab_header_container',
                                'melisKey' => 'meliscommerce_prices_tab_header_container',
                                'name' => 'tr_meliscommerce_prices_tab_header_container',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrice',
                                'action' => 'render-prices-tab-content-header-container',
                            ],
                            'interface' => [
                                'meliscommerce_prices_tab_header_left' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_prices_tab_header_left',
                                        'melisKey' => 'meliscommerce_prices_tab_header_left',
                                        'name' => 'tr_meliscommerce_prices_tab_header_left',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-header-left',
                                    ],
                                    'interface' => [
                                        'meliscommerce_prices_tab_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_prices_tab_header',
                                                'melisKey' => 'meliscommerce_prices_tab_header',
                                                'name' => 'tr_meliscommerce_prices_tab_header',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-tab-content-header',
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_prices_tab_header_right' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_prices_tab_header_right',
                                        'melisKey' => 'meliscommerce_prices_tab_header_right',
                                        'name' => 'tr_meliscommerce_prices_tab_header_right',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-header-right',
                                    ],
                                    'interface' => [
                                        
                                    ],
                                ],
                            ],
                        ],
                        'meliscommerce_prices_tab_contents' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_prices_tab_contents',
                                'melisKey' => 'meliscommerce_prices_tab_contents',
                                'name' => 'tr_meliscommerce_prices_tab_contents',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrice',
                                'action' => 'render-prices-tab-content-general-container',
                            ],
                            'interface' => [
                                'meliscommerce_prices_tab_left_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_prices_tab_left_content',
                                        'melisKey' => 'meliscommerce_prices_tab_left_content',
                                        'name' => 'tr_meliscommerce_prices_tab_left_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-left-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_prices_tab_country_heading' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_prices_tab_country_heading',
                                                'melisKey' => 'meliscommerce_prices_tab_country_heading',
                                                'name' => 'tr_meliscommerce_prices_tab_country_heading',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-tab-sub-heading',
                                            ],
                                            'interface' => [
                                                'meliscommerce_prices_tab_country_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_prices_tab_country_header',
                                                        'melisKey' => 'meliscommerce_prices_tab_country_header',
                                                        'name' => 'tr_meliscommerce_prices_tab_country_header',
                                                        'icon' => 'fa fa-globe',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComPrice',
                                                        'action' => 'render-prices-tab-sub-header',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_prices_country_list' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_prices_country_list',
                                                'melisKey' => 'meliscommerce_prices_country_list',
                                                'name' => 'tr_meliscommerce_prices_country_list',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-country-list',
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_prices_tab_right_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_prices_tab_right_content',
                                        'melisKey' => 'meliscommerce_prices_tab_right_content',
                                        'name' => 'tr_meliscommerce_prices_tab_right_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComPrice',
                                        'action' => 'render-prices-tab-content-right-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_prices_tab_country_form_heading' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_prices_tab_country_form_heading',
                                                'melisKey' => 'meliscommerce_prices_tab_country_form_heading',
                                                'name' => 'tr_meliscommerce_prices_tab_country_form_heading',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-tab-sub-heading',
                                            ],
                                            'interface' => [
                                                'meliscommerce_prices_tab_country_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_prices_tab_country_header',
                                                        'melisKey' => 'meliscommerce_prices_tab_country_header',
                                                        'name' => 'tr_meliscommerce_prices_tab_pricing_general',
                                                        'icon' => 'fa fa-dollar',
                                                        'class' => 'country-price-label',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-sub-header',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_prices_country_form' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_prices_country_form',
                                                'melisKey' => 'meliscommerce_prices_country_form',
                                                'name' => 'tr_meliscommerce_prices_country_form',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComPrice',
                                                'action' => 'render-prices-form',
                                            ],
                                        ],
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