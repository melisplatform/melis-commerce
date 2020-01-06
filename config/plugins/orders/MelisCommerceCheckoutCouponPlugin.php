<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutCouponPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCheckout/checkout-coupon'),
                        'id' => 'checkoutCoupon',
                        
                        'm_coupon_code' => '',
                        'm_coupon_multiple' => false,
                        'm_coupon_site_id' => 1,
                        'm_coupon_is_submit' => false,
                        
                        'm_coupon_remove' => null,
                        
                        'forms' => array(
                            'meliscommerce_checkout_coupon_form' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => '',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_coupon_is_submit',
                                            'type' => 'hidden',
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_coupon_code',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_order_checkout_variant_coupon_code',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_coupon_code',
                                                'class' => 'form-control',
                                            )
                                        )
                                    ),
                                ),
                            )
                        )
                    ),
                    'melis' => array(
                        /*
                        * if set this plugin will belong to a specific marketplace section,
                        * if not it will go directly to ( Others ) section
                        *  - available section for templating plugins as of 2019-05-16
                        *    - MelisCms
                        *    - MelisMarketing
                        *    - MelisSite
                        *    - MelisCommerce
                        *    - Others
                        *    - CustomProjects
                        */
                        'section' => 'MelisCommerce',
                        'subcategory' => array(
                            'id' => 'ORDERS',
                            'title' => 'tr_meliscommerce_orders_Orders'
                        ),
                        'name' => '\tr_meliscommerce_plugin_checkout_coupon_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCheckoutCouponPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_checkout_coupon_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                            ),
                        ),
                        'js_initialization' => array(),
                        'modal_form' => array(
                            'melis_commerce_plugin_checkout_coupon_config' => array(
                                'tab_title' => 'tr_meliscommerce_general_plugin_properties_title',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_template_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => true,
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_coupon_site_id',
                                            'type' => 'MelisCoreSiteSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_site',
                                                'tooltip' => 'tr_meliscommerce_general_common_site tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_coupon_site_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_coupon_multiple',
                                            'type' => 'Checkbox',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_checkout_coupon_multiple',
                                                'tooltip' => 'tr_meliscommerce_plugin_checkout_coupon_multiple tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => array(
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_coupon_multiple',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'template_path' => array(
                                        'name'     => 'template_path',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_coupon_site_id' => array(
                                        'name'     => 'm_coupon_site_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_coupon_multiple' => array(
                                        'name'     => 'm_coupon_multiple',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                        ),
                    ),
                ),
            ),
        ),
     ),
);