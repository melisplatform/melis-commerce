<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceAccountPlugin' => array(
                    'front' => array(
                        // Template path
                        'template_path' => array('MelisCommerce/ClientAccount'),
                        'id' => 'userAccount',
                        // Parameters for the MelisCommerceProfilePlugin() 
                        'profile_parameter' => array(),
                        // Parameters for the MelisCommerceDeliveryAddressPlugin()
                        'delivery_address_parameter' => array(),
                        // Parameters for the MelisCommerceBillingAddressPlugin()
                        'billing_address_parameter' => array(),
                        // Parameters for the MelisCommerceCartPlugin()
                        'cart_parameter' => array(),
                        // Parameters for the MelisCommerceOrderHistoryPlugin()
                        'order_list_paremeter' => array(),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CLIENTS',
                            'title' => 'tr_meliscommerce_clients_Clients'
                        ),
                        'name' => 'tr_meliscommerce_plugin_account_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceAccountPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_account_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                            ),
                        ),
                        'modal_form' => array(
                            'melis_commerce_plugin_account_config' => array(
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
                                                'tooltip' => 'tr_melis_Plugins_Template tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
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
                        )
                    ),
                ),
            ),
        ),
     ),
);