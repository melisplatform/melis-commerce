<?php
// Orders plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCartAddPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceAddToCartShowPlugin/show-add-to-cart',
                        'm_v_id' => null,
                        'm_v_country' => 1,
                        'm_v_quantity' => 1,
                        'm_is_submit' => false,
                        'forms' => array(
                            'meliscommerce_add_to_cart_form' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => '',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_v_id',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_v_country',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_v_quantity',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_add_to_cart_quantity',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_v_quantity',
                                            )
                                        )
                                    ),
                                    
                                ),
                                'input_filter' => array(
                                    'm_v_id' => array(
                                        'name'     => 'm_v_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_id_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_v_country' => array(
                                        'name'     => 'm_v_country',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_country_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_v_quantity' => array(
                                        'name'     => 'm_v_quantity',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'Digits',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscommerce_add_to_cart_variant_quantity_invalid',
                                                        \Zend\Validator\Digits::STRING_EMPTY => '',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_quantity_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                ),
                            )
                        )
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceCheckoutPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out',
                        'm_checkout_step' => '',
                        'm_checkout_country_id' => 1,
                        'm_checkout_site_id' => 1,
                        'm_checkout_cart_plugin_param' => array(),
                        'm_checkout_addresses_plugin_param' => array(),
                        'm_checkout_summary_plugin_param' => array(),
                        'm_checkout_cart_link' => 'http://www.test.com',
                        'm_checkout_addresses_link' => 'http://www.test.com',
                        'm_checkout_summary_link' => 'http://www.test.com',
                        'm_login_page_link' => 'http://www.test.com',
                        'm_checkout_payment_url' => 'http://www.test.com',
                        'm_checkout_payment_notify_url' => 'http://www.test.com',
                        'm_checkout_confirmation_url' => 'http://www.test.com',
                    )
                ),
                'MelisCommerceCheckoutCartPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-cart',
                        'm_country_id' => null,
                        'm_site_id' => null,
                        'm_v_quantity' => array(),
                        'm_v_id_remove' => null,
                        'm_v_remove_link' => 'http://www.test.com'
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceCheckoutAddressesPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-address',
                        'm_site_id' => 1,
                        
                        'm_add_delivery_id' => '',
                        'm_add_delivery_address_name' => '',
                        'm_add_delivery_civility' => '',
                        'm_add_delivery_firstname' => '',
                        'm_add_delivery_name' => '',
                        'm_add_delivery_middle_name' => '',
                        'm_add_delivery_num' => '',
                        'm_add_delivery_street' => '',
                        'm_add_delivery_building_name' => '',
                        'm_add_delivery_stairs' => '',
                        'm_add_delivery_city' => '',
                        'm_add_delivery_country' => '',
                        'm_add_delivery_zipcode' => '',
                        'm_add_delivery_company' => '',
                        'm_add_delivery_phone_mobile' => '',
                        'm_add_delivery_phone_landline' => '',
                        'm_add_delivery_complementary' => '',
                        
                        // If the Delivery address and Billing address are the same
                        'm_add_use_same' => false,
                        
                        'm_add_billing_id' => '',
                        'm_add_billing_address_name' => '',
                        'm_add_billing_civility' => '',
                        'm_add_billing_firstname' => '',
                        'm_add_billing_name' => '',
                        'm_add_delivery_middle_name' => '',
                        'm_add_billing_num' => '',
                        'm_add_billing_street' => '',
                        'm_add_billing_building_name' => '',
                        'm_add_billing_stairs' => '',
                        'm_add_billing_city' => '',
                        'm_add_billing_country' => '',
                        'm_add_billing_zipcode' => '',
                        'm_add_billing_company' => '',
                        'm_add_billing_phone_mobile' => '',
                        'm_add_billing_phone_landline' => '',
                        'm_add_billing_complementary' => '',
                        
                        'm_add_is_submit' => false,
                        
                        'forms' => array(
                            'delivery_address' => array(
                                'attributes' => array(
                                    'name' => 'delivery_address',
                                    'id' => '',
                                    'method' => '',
                                    'action' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_checkout_step',
                                            'type' => 'hidden',
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_is_submit',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_type',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_id',
                                            'type' => 'EcomPluginDeliveryAddressSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_select_address',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_id',
                                            ),
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_address_name',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_address_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_civility',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_firstname',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_firstname',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_name',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_middle_name',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_mname',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_middle_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_num',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_num',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_num',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_street',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_street',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_building_name',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_building_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_building_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_stairs',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_stairs',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_stairs',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_city',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_city',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_city',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_state',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_state',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_state',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_country',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_country',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_country',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_zipcode',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_zipcode',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_company',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_company_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_company',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_phone_mobile',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_phone_mobile',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_phone_landline',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_phone_landline',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_complementary',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_complementary',
                                            )
                                        )
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_add_delivery_id' => array(
                                        'name'     => 'm_add_delivery_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_checkout_delivery_address_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters' => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_address_name' => array(
                                        'name'     => 'm_add_delivery_address_name',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_civility' => array(
                                        'name'     => 'm_add_delivery_civility',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_firstname' => array(
                                        'name'     => 'm_add_delivery_firstname',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_name' => array(
                                        'name'     => 'm_add_delivery_name',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_num' => array(
                                        'name'     => 'm_add_delivery_num',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 10,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_stairs' => array(
                                        'name'     => 'm_add_delivery_stairs',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 10,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_building_name' => array(
                                        'name'     => 'm_add_delivery_building_name',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_company' => array(
                                        'name'     => 'm_add_delivery_company',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 100,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_street' => array(
                                        'name'     => 'm_add_delivery_street',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_zipcode' => array(
                                        'name'     => 'm_add_delivery_zipcode',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 15,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_15',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_city' => array(
                                        'name'     => 'm_add_delivery_city',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 100,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_state' => array(
                                        'name'     => 'm_add_delivery_state',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 50,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_country' => array(
                                        'name'     => 'm_add_delivery_country',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 50,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_phone_mobile' => array(
                                        'name'     => 'm_add_delivery_phone_mobile',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_phone_landline' => array(
                                        'name'     => 'm_add_delivery_phone_landline',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_delivery_complementary' => array(
                                        'name'     => 'm_add_delivery_complementary',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                )
                            ),
                            'billing_address' => array(
                                'attributes' => array(
                                    'name' => 'billing_address',
                                    'id' => '',
                                    'method' => '',
                                    'action' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_checkout_step',
                                            'type' => 'hidden',
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_is_submit',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_type',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_id',
                                            'type' => 'EcomPluginBillingAddressSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_select_address',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_id',
                                            ),
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_address_name',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_address_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_civility',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_firstname',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_firstname',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_name',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_middle_name',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_mname',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_middle_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_num',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_num',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_num',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_street',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_street',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_building_name',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_building_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_building_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_stairs',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_stairs',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_stairs',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_city',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_city',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_city',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_state',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_state',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_state',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_country',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_country',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_country',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_zipcode',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_zipcode',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_company',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_company_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_company',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_phone_mobile',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_phone_mobile',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_phone_landline',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_phone_landline',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_complementary',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_complementary',
                                            )
                                        )
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_add_billing_id' => array(
                                        'name'     => 'm_add_billing_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_checkout_billing_address_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters' => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_address_name' => array(
                                        'name'     => 'm_add_billing_address_name',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_civility' => array(
                                        'name'     => 'm_add_billing_civility',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_firstname' => array(
                                        'name'     => 'm_add_billing_firstname',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_name' => array(
                                        'name'     => 'm_add_billing_name',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_num' => array(
                                        'name'     => 'm_add_billing_num',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 10,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_stairs' => array(
                                        'name'     => 'm_add_billing_stairs',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 10,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_building_name' => array(
                                        'name'     => 'm_add_billing_building_name',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_company' => array(
                                        'name'     => 'm_add_billing_company',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 100,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_street' => array(
                                        'name'     => 'm_add_billing_street',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_zipcode' => array(
                                        'name'     => 'm_add_billing_zipcode',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 15,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_15',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_city' => array(
                                        'name'     => 'm_add_billing_city',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 100,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_state' => array(
                                        'name'     => 'm_add_billing_state',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 50,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_country' => array(
                                        'name'     => 'm_add_billing_country',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 50,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_phone_mobile' => array(
                                        'name'     => 'm_add_billing_phone_mobile',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_phone_landline' => array(
                                        'name'     => 'm_add_billing_phone_landline',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 45,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_add_billing_complementary' => array(
                                        'name'     => 'm_add_billing_complementary',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'StringLength',
                                                'options' => array(
                                                    'encoding' => 'UTF-8',
                                                    'max'      => 255,
                                                    'messages' => array(
                                                        \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                )
                            )
                        ),
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceCheckoutSummaryPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-summary',
                        'm_country_id' => 1,
                        'm_site_id' => 1,
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceCheckoutCouponAddPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-coupon',
                        'm_coupon_code' => '',
                        'm_site_id' => 1,
                        'forms' => array(
                            'meliscommerce_checkout_coupon_form' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => '',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_coupon_code',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_order_checkout_variant_coupon_code',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_coupon_code',
                                            )
                                        )
                                    ),
                                ),
                            )
                        )
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceCartMenuPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCartMenuPlugin/show-cart-menu',
                    ),
                    'melis' => array(),
                ),
            ),
        ),
    ),
);