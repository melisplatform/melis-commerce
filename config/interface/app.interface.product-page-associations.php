<?php

/**
 * Melis Technology (http://www.melistechnology.com]
 *
 * @copyright Copyright (c] 2016 Melis Technology (http://www.melistechnology.com]
 *
 */

return [
    'plugins' => [
        'meliscommerce' => [
            'interface' => [
                'meliscommerce_page_association' => [
                    'interface' => [
                        'meliscommerce_page_association' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_page_association',
                                'melisKey' => 'meliscommerce_page_association',
                                'name' => 'tr_meliscommerce_page_association',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProduct',
                                'action' => 'render-page-associations',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];