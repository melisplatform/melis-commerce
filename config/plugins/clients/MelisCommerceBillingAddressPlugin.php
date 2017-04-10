<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceBillingAddressPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientBillingAddress',
                        // enables user to add new addresses, select address to edit. if set to false retrieves the first address
                        'show_select_address_data' => false,
                        
                        // form fields
                        'cadd_address_name' => '',
                        'cadd_civility' => '',
                        'cadd_firstname' => '',
                        'cadd_name' => '',
                        'cadd_middle_name' => '',
                        'cadd_num' => '',
                        'cadd_street' => '',
                        'cadd_building_name' => '',
                        'cadd_stairs' => '',
                        'cadd_city' => '',
                        'cadd_country' => '',
                        'cadd_zipcode' => '',
                        'cadd_company' => '',
                        'cadd_phone_mobile' => '',
                        'cadd_phone_landline' => '',
                        'cadd_complementary' => '',
                        
                        // flag true if a form is submitted
                        'billing_add_is_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => array(
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
                                            'name' => 'cadd_id',
                                            'type' => 'hidden',
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'billing_add_is_submit',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cadd_address_name',
                                            'type' => 'text',
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
                                            'type' => 'EcomPluginCivilitySelect',
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
                                            'type' => 'Text',
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
                                            'type' => 'Text',
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
                                            'type' => 'Text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                            'type' => 'text',
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
                                    'cadd_id' => array(
                                        'name'     => 'cadd_id',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'IsInt',
                                            ),
                                        ),
                                        'filters' => array(
                                        ),
                                    ),
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
                                    'cadd_firstname' => array(
                                        'name'     => 'cadd_firstname',
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
                                    'cadd_name' => array(
                                        'name'     => 'cadd_name',
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
                            )
                        )
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);