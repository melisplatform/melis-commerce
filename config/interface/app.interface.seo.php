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
                'name' => 'tr_meliscommerce_seo_Seo',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/seo.tool.js',
                ],
                'css' => [
                ],
            ],
            'datas' => [
            
            ],
            'interface' => [
                'meliscommerce_seo_conf' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_seo',
                        'melisKey' => 'meliscommerce_seo',
                        'name' => 'tr_meliscommerce_seo_Seo',
                        'icon' => 'glyphicons search'
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComSeo',
                        'action' => 'render-seo-plugin',
                        'jscallback' => 'preSeoCharCounter();'
                    ]
                ],
            ],
        ],
    ],
];