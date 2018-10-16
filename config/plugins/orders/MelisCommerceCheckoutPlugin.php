<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCheckout/checkout'),
                        'id' => 'checkout',
                        
                        // checkout steps
                        'm_checkout_step' => '',
                        // country id
                        'm_checkout_country_id' => 1,
                        // site id
                        'm_checkout_site_id' => 1,
                        // page link
                        'm_checkout_page_link' => 'http://www.test.com',
                        // page link ro reroute user if not logged in
                        'm_login_page_link' => 'http://www.test.com',
                        
                        // Sub plugin paramaters
                        'checkout_cart_parameters' => array(),
                        'checkout_addresses_parameters' => array(),
                        'checkout_summary_parameters' => array(),
                        'checkout_confirm_summary_parameters' => array(),
                        'checkout_confirm_parameters' => array(),
                        
                        // Sub plugins
                        'sub_plugins_params' => array(
                            'checkout_cart_parameters',
                            'checkout_addresses_parameters',
                            'checkout_summary_parameters',
                            'checkout_confirm_summary_parameters',
                            'checkout_confirm_parameters',
                        )
                    ),
                    'melis' => array(
                        
                        'subcategory' => array(
                            'id' => 'ORDERS',
                            'title' => 'tr_meliscommerce_orders_Orders'
                        ),
                        'name' => 'tr_meliscommerce_plugin_checkout_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCheckoutPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_checkout_description',
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
                            'melis_commerce_plugin_checkout_config' => array(
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
                                    array(
                                        'spec' => array(
                                            'name' => 'm_checkout_step',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'Step',
                                                'tooltip' => 'tr_meliscommerce_plugin_checkout_step tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'checkout-cart' => 'Cart',
                                                    'checkout-addresses' => 'Delivery and billing addresses',
                                                    'checkout-summary' => 'Summary',
                                                    'checkout-confirm-summary' => 'Summary confirmation',
                                                    'checkout-payment' => 'Payment',
                                                    'checkout-confirm' => 'Checkout confirmation',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_checkout_step',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_checkout_country_id',
                                            'type' => 'EcomPluginPriceCountriesSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_country',
                                                'tooltip' => 'tr_meliscommerce_general_common_country tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_checkout_country_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_checkout_site_id',
                                            'type' => 'MelisCoreSiteSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_site',
                                                'tooltip' => 'tr_meliscommerce_general_common_site tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_checkout_site_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_checkout_page_link',
                                            'type' => 'MelisText',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_destination_page_link',
                                                'tooltip' => 'tr_meliscommerce_general_common_destination_page_link tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_checkout_page_link',
                                                'class' => 'melis-input-group-button',
                                                'data-button-icon' => 'fa fa-sitemap',
                                                'data-button-id' => 'meliscms-site-selector',
                                                'data-callback' => '',
                                                'required' => 'required'
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
                                    'm_checkout_step' => array(
                                        'name'     => 'm_checkout_step',
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
                                    'm_checkout_country_id' => array(
                                        'name'     => 'm_checkout_country_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_category_product_list_no_country',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_checkout_site_id' => array(
                                        'name'     => 'm_checkout_site_id',
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
                ),
            ),
        ),
    ),
);