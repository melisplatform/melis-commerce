<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutConfirmPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCheckout/checkout-confirm'),
                        'id' => 'checkoutConfirmation',
                        
                        // Order id
                        'm_conf_order_id' => '',
                        
                        'address_parameters' => array(),
                        
                        // Sub plugins
                        'sub_plugins_params' => array(
                            'address_parameters',
                        )
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'ORDERS',
                            'title' => 'tr_meliscommerce_orders_Orders'
                        ),
                        'name' => 'tr_meliscommerce_plugin_checkout_confirmation_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCheckoutConfirmPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_checkout_confirmation_description',
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
                            'melis_commerce_plugin_checkout_confirm_config' => array(
                                'tab_title' => 'tr_front_plugin_common_tab_properties',
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
                                )
                            ),
                        ),
                    ),
                )
            )
        )
    )
);