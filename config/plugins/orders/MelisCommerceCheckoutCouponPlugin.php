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
            'plugins' => [
                'MelisCommerceCheckoutCouponPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceCheckout/checkout-coupon'],
                        'id' => 'checkoutCoupon',
                        
                        'm_coupon_code' => '',
                        'm_coupon_multiple' => false,
                        'm_coupon_site_id' => 1,
                        'm_coupon_is_submit' => false,
                        
                        'm_coupon_remove' => null,
                        
                        'forms' => [
                            'meliscommerce_checkout_coupon_form' => [
                                'attributes' => [
                                    'name' => '',
                                    'id' => '',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_coupon_is_submit',
                                            'type' => 'hidden',
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_coupon_code',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_order_checkout_variant_coupon_code',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_coupon_code',
                                                'class' => 'form-control',
                                            ]
                                        ]
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'melis' => [
                        /*
                        * if set this plugin will belong to a specific marketplace section,
                        * if not it will go directly to ( Others ] section
                        *  - available section for templating plugins as of 2019-05-16
                        *    - MelisCms
                        *    - MelisMarketing
                        *    - MelisSite
                        *    - MelisCommerce
                        *    - Others
                        *    - CustomProjects
                        */
                        'section' => 'MelisCommerce',
                        'subcategory' => [
                            'id' => 'ORDERS',
                            'title' => 'tr_meliscommerce_orders_Orders'
                        ],
                        'name' => '\tr_meliscommerce_plugin_checkout_coupon_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCheckoutCouponPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_checkout_coupon_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => [
                            'css' => [
                            ],
                            'js' => [
                            ],
                        ],
                        'js_initialization' => [],
                        'modal_form' => [
                            'melis_commerce_plugin_checkout_coupon_config' => [
                                'tab_title' => 'tr_meliscommerce_general_plugin_properties_title',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => [
                                                'label' => 'tr_melis_Plugins_Template',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_template_tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => true,
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_coupon_site_id',
                                            'type' => 'MelisCoreSiteSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_site',
                                                'tooltip' => 'tr_meliscommerce_general_common_site tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_coupon_site_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_coupon_multiple',
                                            'type' => 'Checkbox',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_checkout_coupon_multiple',
                                                'tooltip' => 'tr_meliscommerce_plugin_checkout_coupon_multiple tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => [
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                ]
                                            ],
                                            'attributes' => [
                                                'id' => 'm_coupon_multiple',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'template_path' => [
                                        'name'     => 'template_path',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_coupon_site_id' => [
                                        'name'     => 'm_coupon_site_id',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                    'm_coupon_multiple' => [
                                        'name'     => 'm_coupon_multiple',
                                        'required' => false,
                                        'validators' => [
                                        ],
                                        'filters'  => [
                                        ],
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