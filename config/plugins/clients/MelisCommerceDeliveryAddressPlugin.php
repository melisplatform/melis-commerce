<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceDeliveryAddressPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerce/ClientDeliveryAddress'),
                        'id' => 'userDeliveryAddress',
                        
                        // enables user to add new addresses, select address to edit
                        'show_select_address_data' => false,
                        'select_delivery_addresses' => '',
                        'select_delivery_addresses_submit' => false,
                        
                        // form fields
                        'cadd_id' => '',
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
                        'delivery_address_save_submit' => false,
                        'delivery_address_delete_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => array(
                            'select_delivery_address' => array(
                                'attributes' => array(
                                    'name' => 'select_delivery_address',
                                    'method' => 'POST',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'select_delivery_addresses_submit',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'select_delivery_addresses',
                                            'type' => 'EcomPluginDeliveryAddressSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_select_address',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'select_delivery_addresses',
                                                'data-selectaddress' => 'select-address',
                                                'class' => 'form-control',
                                                'onchange' => 'this.form.submit()'
                                            )
                                        )
                                    ),
                                ),
                                'input_filter' => array(
                                    'select_delivery_addresses' => array(
                                        'name'     => 'select_delivery_addresses',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters' => array(
                                        ),
                                    ),
                                )
                            ),
                            'delivery_address' => array(
                                'attributes' => array(
                                    'name' => 'delivery_address',
                                    'method' => 'POST',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'delivery_address_save_submit',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cadd_id',
                                            'type' => 'hidden',
                                        ),
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                            'id' => 'CLIENTS',
                            'title' => 'tr_meliscommerce_clients_Clients'
                        ),
                        'name' => 'tr_meliscommerce_plugin_delivery_address_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceDeliveryAddressPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_delivery_address_description',
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
                            'melis_commerce_plugin_profile_config' => array(
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
                                    array(
                                        'spec' => array(
                                            'name' => 'show_select_address_data',
                                            'type' => 'Checkbox',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_show_select_addresses',
                                                'tooltip' => 'tr_meliscommerce_general_common_show_select_addresses tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => array(
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'show_select_address_data',
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
                                    'show_select_address_data' => array(
                                        'name'     => 'show_select_address_data',
                                        'required' => false,
                                        'validators' => array(
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