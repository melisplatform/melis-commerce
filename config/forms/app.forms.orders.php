<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_order_list' => array(
                    'meliscommerce_order_list_status_form' => array(
                        'attributes' => array(
                            'name' => 'order-list-status',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'ord_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ord_status',
                                    'type' => 'EcomOrderStatusSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_status',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'ord_status' => array(
                                'name'     => 'ord_status',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => '\Zend\I18n\Validator\IsInt',
                                        'options' => array(
                                            'messages'=> array(
                                                \Zend\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_orders_invalid_status',
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
                    ),
                ),
                'meliscommerce_orders' => array(
                    'meliscommerce_order_information_form' => array(
                        'attributes' => array(
                            'name' => 'order',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(                            
                            array(
                                'spec' => array(
                                    'name' => 'ord_id',
                                    'type' => 'hidden',
                                    'options' => array(                                        
                                    ),
                                    'attributes' => array(
                                        'id' => '',                                        
                                    ),                                    
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ord_reference',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_code',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '100'
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(                            
                            'ord_id' => array(
                                'name'     => 'ord_id',
                                'required' => false,
                                'validators' => array(
                                    
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ord_reference' => array(
                                'name'     => 'ord_reference',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_100',
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
                    ),
                    'meliscommerce_order_address_form' => array(
                        'attributes' => array(
                            'name' => 'orderAddressForm',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'oadd_id',
                                    'type' => 'hidden',                                    
                                    'attributes' => array(),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_order_id',
                                    'type' => 'hidden',
                                    'attributes' => array(),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_type',
                                    'type' => 'hidden',
                                    'attributes' => array(),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_creation_date',
                                    'type' => 'hidden',
                                    'attributes' => array(),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_company',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_company',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '100'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_civility',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',                                        
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_firstname',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_fname',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '255'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_lname',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '255'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_middle_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_mname',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '255'
                                    ),
                                ),
                            ),
                           array(
                                'spec' => array(
                                    'name' => 'oadd_num',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_address_num',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '10'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_street',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_street',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '255'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_building_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_building',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '45'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_stairs',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_floor',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '10'
                                    ),
                                ),
                            ),                          
                            array(
                                'spec' => array(
                                    'name' => 'oadd_city',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_city',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '100'
                                    ),
                                ),
                            ),                            
                            array(
                                'spec' => array(
                                    'name' => 'oadd_state',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_state',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '50'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_country',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_country',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '50'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_zipcode',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_zip',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '15'
                                    ),
                                ),
                            ),                            
                            array(
                                'spec' => array(
                                    'name' => 'oadd_phone_mobile',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_mobile',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '45'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_phone_landline',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_phone',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '45'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oadd_complementary',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_address_comments',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 255,
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'oadd_civility' => array(
                                'name'     => 'oadd_civility',
                                'required' => false,
                                'validators' => array(),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_name' => array(
                                'name'     => 'oadd_name',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_255',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),                            
                            'oadd_middle_name' => array(
                                'name'     => 'oadd_middle_name',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_255',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_firstname' => array(
                                'name'     => 'oadd_firstname',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_255',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_num' => array(
                                'name'     => 'oadd_num',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_10',
                                            ),
                                        ),
                                    ),                                   
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_stairs' => array(
                                'name'     => 'oadd_stairs',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_10',
                                            ),
                                        ),
                                    ),                                    
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_building_name' => array(
                                'name'     => 'oadd_building_name',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_45',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_company' => array(
                                'name'     => 'oadd_company',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_100',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_street' => array(
                                'name'     => 'oadd_street',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_255',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),                            
                            'oadd_city' => array(
                                'name'     => 'oadd_city',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_100',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_state' => array(
                                'name'     => 'oadd_state',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_50',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_country' => array(
                                'name'     => 'oadd_country',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_50',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_zipcode' => array(
                                'name'     => 'oadd_zipcode',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 15,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_15',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'regex', false,
                                        'options' => array(
                                            'pattern' => '/^([0-9\(\)\/\+ \-]*)$/',
                                            'messages'=> array(\Zend\Validator\Regex::NOT_MATCH => 'tr_meliscommerce_orders_invalid_number'),
                                        ),
                                    ),                                    
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_complementary' => array(
                                'name'     => 'oadd_complementary',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_255',
                                            ),
                                        ),
                                    ),                                    
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_phone_mobile' => array(
                                'name'     => 'oadd_phone_mobile',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_45',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'regex', false,
                                        'options' => array(
                                            'pattern' => '/^([0-9\(\)\/\+ \-]*)$/',
                                            'messages'=> array(\Zend\Validator\Regex::NOT_MATCH => 'tr_meliscommerce_orders_invalid_phone'),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oadd_phone_landline' => array(
                                'name'     => 'oadd_phone_landline',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_45',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'regex', false,
                                        'options' => array(
                                            'pattern' => '/^([0-9\(\)\/\+ \-]*)$/',
                                            'messages'=> array(\Zend\Validator\Regex::NOT_MATCH => 'tr_meliscommerce_orders_invalid_phone'),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        ),
                    ),
                    'meliscommerce_order_shipping_form' => array(
                        'attributes' => array(
                            'name' => 'orderShippingForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'oship_id',
                                    'type' => 'hidden',
                                    'attributes' => array(                                       
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oship_tracking_code',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_tracking_code',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => 100,
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'oship_content',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_tracking_details',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 1200,
                                    ),
                                ),
                            ),                            
                            array(
                                'spec' => array(
                                    'name' => 'oship_date_sent',
                                    'type' => 'EcomDateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_tracking_date',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'shippingDate',
                                        'dateLabel' => 'tr_meliscommerce_orders_tracking_date',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'oship_tracking_code' => array(
                                'name'     => 'oship_tracking_code',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_100',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oship_content' => array(
                                'name'     => 'oship_content',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 1200,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_1200',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'oship_date_sent' => array(
                                'name'     => 'oship_date_sent',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => '\Zend\Validator\NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
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
                    ),
                    'meliscommerce_order_message_form' => array(
                        'attributes' => array(
                            'name' => 'orderMessagesForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(                            
                            array(
                                'spec' => array(
                                    'name' => 'omsg_message',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_message_your',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 1200,
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'omsg_message' => array(
                                'name'     => 'omsg_message',
                                'required' => true,
                                'validators' => array(                                    
                                    array(
                                        'name' => '\Zend\Validator\NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 1200,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_1200',
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
                    ),
                ),
            ),            
        ),
    ),
);
