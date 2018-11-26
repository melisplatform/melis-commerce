<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutAddressesPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCheckout/checkout-addresses'),
                        'id' => 'checkoutAddresses',
                        
                        // site id
                        'm_add_site_id' => 1,
                        // Override submitted data existing data
                        'm_add_override_data' => false,
                        
                        // delivery form fields
                        'm_add_delivery_id' => '',
                        'm_add_delivery_type' => '',
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
                        'm_add_delivery_state' => '',
                        'm_add_delivery_country' => '',
                        'm_add_delivery_zipcode' => '',
                        'm_add_delivery_company' => '',
                        'm_add_delivery_phone_mobile' => '',
                        'm_add_delivery_phone_landline' => '',
                        'm_add_delivery_complementary' => '',
                        
                        // use same address flag
                        'm_add_use_same_address' => null,

                        /**
                         * This field is used to determine which address
                         * should the plugin will validate first (billing, delivery)
                         * If this field is not include inside the form or in
                         * ajax request, the default of this is delivery
                         *
                         * The value of this is either billing or delivery
                         */
                        'm_add_first_form_to_validate' => '',
                        
                        // billing form fields
                        'm_add_billing_id' => '',
                        'm_add_billing_type' => '',
                        'm_add_billing_address_name' => '',
                        'm_add_billing_civility' => '',
                        'm_add_billing_firstname' => '',
                        'm_add_billing_name' => '',
                        'm_add_billing_middle_name' => '',
                        'm_add_billing_num' => '',
                        'm_add_billing_street' => '',
                        'm_add_billing_building_name' => '',
                        'm_add_billing_stairs' => '',
                        'm_add_billing_city' => '',
                        'm_add_billing_state' => '',
                        'm_add_billing_country' => '',
                        'm_add_billing_zipcode' => '',
                        'm_add_billing_company' => '',
                        'm_add_billing_phone_mobile' => '',
                        'm_add_billing_phone_landline' => '',
                        'm_add_billing_complementary' => '',
                        
                        // flag true if a form is submitted
                        'm_add_is_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => array(
                            'delivery_address' => array(
                                'attributes' => array(
                                    'name' => 'delivery_address',
                                    'id' => '',
                                    'method' => 'POST',
                                    'action' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
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
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_id',
                                                'class' => 'form-control'
                                            ),
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_delivery_address_name',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_title',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_delivery_address_name',
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_select_deliver_address',
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
                                    'method' => 'POST',
                                    'action' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
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
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_id',
                                                'class' => 'form-control'
                                            ),
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_billing_address_name',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_address_title',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_billing_address_name',
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_select_billing_address',
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
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'ORDERS',
                            'title' => 'tr_meliscommerce_orders_Orders'
                        ),
                        'name' => 'tr_meliscommerce_plugin_checkout_addresses_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCheckoutAddressesPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_checkout_addresses_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                                '/MelisCommerce/plugins/js/checkout.js'
                            ),
                        ),
                        'js_initialization' => array(),
                        'modal_form' => array(
                            'melis_commerce_plugin_checkout_addresses_config' => array(
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
                                            'name' => 'm_add_site_id',
                                            'type' => 'MelisCoreSiteSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_site',
                                                'tooltip' => 'tr_meliscommerce_general_common_site tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_site_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_add_use_same_address',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_checkout_addresses_use_same_address',
                                                'tooltip' => 'tr_meliscommerce_plugin_checkout_addresses_use_same_address tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => array(
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                ),
                                                'disable_inarray_validator' => true
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_add_use_same_address',
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
                                    'm_add_site_id' => array(
                                        'name'     => 'm_add_site_id',
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
                                    'm_add_use_same_address' => array(
                                        'name'     => 'm_add_use_same_address',
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