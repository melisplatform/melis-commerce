<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_order_checkout' => array(
                    'meliscommerce_order_checkout_billing_address_form' => array(
                        'attributes' => array(
                            'id' => 'EcomCheckoutBillingAddressForm'
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cadd_id',
                                    'type' => 'EcomCheckoutBillingAddressSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_order_checkout_address_billing_Select',
                                        'empty_option' => 'tr_meliscommerce_order_checkout_common_chooose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'orderCheckoutBillingSelect',
                                    )
                                )
                            ),
                        )
                    ),
                    'meliscommerce_order_checkout_delivery_address_form' => array(
                        'attributes' => array(
                            'id' => 'EcomCheckoutDeliveryAddressForm'
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cadd_id',
                                    'type' => 'EcomCheckoutDeliveryAddressSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_order_checkout_address_delivery_Select',
                                        'empty_option' => 'tr_meliscommerce_order_checkout_common_chooose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'orderCheckoutDeliverySelect',
                                    )
                                )
                            ),
                        )
                    ),
                    'meliscommerce_order_checkout_address_form' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cadd_client_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_client_person',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_type',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_address_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_address_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_civility',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_civility',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_firstname',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_fname',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_firstname',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_middle_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_mname',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_middle_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_num',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_street_num',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_num',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_street',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_street_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_street',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_building_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_building_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_building_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_stairs',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_stairs',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_stairs',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_city',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_city',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_city',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_state',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_state',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_state',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_country',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_country',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_country',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_zipcode',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_zipcode',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_company',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_company',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_phone_mobile',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_phone_mobile',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_phone_landline',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_phone_landline',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_complementary',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_complementary',
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(
                            'cadd_address_name' => array(
                                'name'     => 'cadd_address_name',
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
                            'cadd_civility' => array(
                                'name'     => 'cadd_civility',
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
                            'cadd_name' => array(
                                'name'     => 'cadd_name',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
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
                            'cadd_firstname' => array(
                                'name'     => 'cadd_firstname',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
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
                            'cadd_num' => array(
                                'name'     => 'cadd_num',
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
                            'cadd_stairs' => array(
                                'name'     => 'cadd_stairs',
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
                            'cadd_building_name' => array(
                                'name'     => 'cadd_building_name',
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
                            'cadd_company' => array(
                                'name'     => 'cadd_company',
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
                            'cadd_street' => array(
                                'name'     => 'cadd_street',
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
                            'cadd_zipcode' => array(
                                'name'     => 'cadd_zipcode',
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
                            'cadd_city' => array(
                                'name'     => 'cadd_city',
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
                            'cadd_state' => array(
                                'name'     => 'cadd_state',
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
                            'cadd_country' => array(
                                'name'     => 'cadd_country',
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
                            'cadd_phone_mobile' => array(
                                'name'     => 'cadd_phone_mobile',
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
                            'cadd_phone_landline' => array(
                                'name'     => 'cadd_phone_landline',
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
                            'cadd_complementary' => array(
                                'name'     => 'cadd_complementary',
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
                )
            )
        )
    )
);