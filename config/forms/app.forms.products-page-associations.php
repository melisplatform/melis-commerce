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
            'tools' => [
                'meliscommerce_products' => [
                    'forms' => [
                        'meliscommerce_products_page_associations_form' => [
                            'attributes' => [
                                'name' => 'meliscommerce_products_page_associations_form',
                                'id' => 'meliscommerce_products_page_associations_form',
                                'method' => 'POST',
                                'action' => '',
                            ],
                            'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                            'elements' => [
                                [
                                    'spec' => [
                                        'name' => 'plink_id',
                                        'type' => 'hidden',
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'plink_link_1',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'Associated page 1',
                                            'tooltip' => 'tr_melis_cms_sites_site_main_page_id tooltip',
                                            'button' => 'fa fa-sitemap',
                                            'button-id' => 'prod_page_assoc1_btn',
                                        ],
                                        'attributes' => [
                                            'id' => 'prod_page_assoc_1',
                                            'value' => '',
                                            'placeholder' => ''
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'plink_link_2',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'Associated page 2',
                                            'tooltip' => 'tr_melis_cms_sites_site_main_page_id tooltip',
                                            'button' => 'fa fa-sitemap',
                                            'button-id' => 'prod_page_assoc2_btn',
                                        ],
                                        'attributes' => [
                                            'id' => 'prod_page_assoc_2',
                                            'value' => '',
                                            'placeholder' => ''
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'name' => 'plink_link_3',
                                        'type' => 'MelisText',
                                        'options' => [
                                            'label' => 'Associated page 3',
                                            'tooltip' => 'tr_melis_cms_sites_site_main_page_id tooltip',
                                            'button' => 'fa fa-sitemap',
                                            'button-id' => 'prod_page_assoc3_btn',
                                        ],
                                        'attributes' => [
                                            'id' => 'prod_page_assoc_3',
                                            'value' => '',
                                            'placeholder' => ''
                                        ],
                                    ],
                                ]
                            ],
                            'input_filter' => [
                                'plink_link_1' => [
                                    'name' => 'plink_link_1',
                                    'required' => false,
                                    'validators' => [],
                                    'filters' => [
                                        ['name' => 'StripTags'],
                                        ['name' => 'StringTrim'],
                                    ],
                                ],
                                'plink_link_2' => [
                                    'name' => 'plink_link_2',
                                    'required' => false,
                                    'validators' => [],
                                    'filters' => [
                                        ['name' => 'StripTags'],
                                        ['name' => 'StringTrim'],
                                    ],
                                ],
                                'plink_link_3' => [
                                    'name' => 'plink_link_3',
                                    'required' => false,
                                    'validators' => [],
                                    'filters' => [
                                        ['name' => 'StripTags'],
                                        ['name' => 'StringTrim'],
                                    ],
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];