<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_prd_var_Duplication',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/duplication.tool.js',
                ),
                'css' => array(
                    '/MelisCommerce/css/duplication.css',
                ),
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_duplication_modal' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_duplication_modal',
                        'melisKey' => 'meliscommerce_duplication_modal',
                        'name' => 'tr_meliscommerce_duplication_modal',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComPrdVarDuplication',
                        'action' => 'render-duplicate-modal',
                    ),
                    'interface' => array(
                        'meliscommerce_variant_duplication' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_variant_duplication',
                                'melisKey' => 'meliscommerce_variant_duplication',
                                'name' => 'tr_meliscommerce_variant_duplication',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-variant-duplication-form',
                            )
                        ),
                        'meliscommerce_product_duplication' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_product_duplication',
                                'melisKey' => 'meliscommerce_product_duplication',
                                'name' => 'tr_meliscommerce_product_duplication',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-product-duplication-form',
                            )
                        ),
                    )
                )
            )
        )
    )
);