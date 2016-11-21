<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_seo_Seo',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/seo.tool.js',
                ),
                'css' => array(
                ),
            ),
            'datas' => array(
            
            ),
            'interface' => array(
                'meliscommerce_seo_conf' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_seo',
                        'melisKey' => 'meliscommerce_seo',
                        'name' => 'tr_meliscommerce_seo_Seo',
                        'icon' => 'glyphicons search'
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComSeo',
                        'action' => 'render-seo-plugin',
                        'jscallback' => 'preSeoCharCounter();'
                    )
                ),
            ),
        ),
    ),
);