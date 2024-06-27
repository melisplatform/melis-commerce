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
                'name' => 'tr_meliscommerce_prd_var_Duplication',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/duplication.tool.js',
                ],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_duplication_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_duplication_modal',
                        'melisKey' => 'meliscommerce_duplication_modal',
                        'name' => 'tr_meliscommerce_duplication_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComPrdVarDuplication',
                        'action' => 'render-duplicate-modal',
                    ],
                    'interface' => [
                        'meliscommerce_variant_duplication' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_variant_duplication',
                                'melisKey' => 'meliscommerce_variant_duplication',
                                'name' => 'tr_meliscommerce_variant_duplication',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-variant-duplication-form',
                            ]
                        ],
                        'meliscommerce_product_duplication' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_product_duplication',
                                'melisKey' => 'meliscommerce_product_duplication',
                                'name' => 'tr_meliscommerce_product_duplication',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-product-duplication-form',
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];