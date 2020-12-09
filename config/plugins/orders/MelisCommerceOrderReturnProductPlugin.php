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
                'MelisCommerceOrderReturnProductPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceOrder/order-return-product'],
                        'id' => 'orderProductReturn',
                        // Order id
                        'm_rp_order_id' => null,
                        'm_rp_data' => [],//array if variant id and it's quantity
                        /**
                         * ex foramt:
                         * [
                         *      1 => 10, // 1 is the variant id while 10 is the quantity to return
                         *      2 => 20
                         * ]
                         */


                        'omsg_message' => null,
                        'm_rp_is_submit' => 0,

                        'files' => [
                            'css' => [
                                '/MelisCommerce/plugins/css/def-return-product.css'
                            ],
                            'js' => [
                                '/MelisCommerce/plugins/js/def-return-product.js'
                            ],
                        ],
                        'forms' => [
                            'meliscommerce_order_return_product_form' => [
                                'attributes' => [
                                    'name' => 'returnProductForm',
                                    'id' => 'returnProductForm',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_rp_is_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'omsg_message',
                                            'type' => 'Textarea',
                                            'options' => [
                                                'label' => 'Explanation',
                                                'label_attributes' => [
                                                    'class' => 'col-form-label col-md-4'
                                                ],
                                            ],
                                            'attributes' => [
                                                'id' => 'omsg_message',
                                            ],
                                        ]
                                    ],
                                ],
                                'input_filter' => [

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
                        'name' => '\tr_meliscommerce_plugin_order_return_product_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceOrderPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_order_return_product_description',
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
                            'melis_commerce_plugin_order_return_product_config' => [
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
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ],
     ],
];