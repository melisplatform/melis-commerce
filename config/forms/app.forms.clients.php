<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_clients' => array(
                    'meliscommerce_clients_main_form' => array(
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
                                    'name' => 'cli_country_id',
                                    'type' => 'EcomCountriesNoAllCountriesSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Client_country',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'cli_country_id',
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(
                            'cli_country_id' => array(
                                'name'     => 'cli_country_id',
                                'required' => true,
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
                        )
                    ),
                    'meliscommerce_client_list_export_form' => array(
                        'attributes' => array(
                            'name' => 'client-list-export',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cli_status',
                                    'type' => 'Select',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_page_status',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_all',
                                        'value_options' => array(
                                            'active' => 'tr_meliscommerce_coupon_page_status_online',
                                            'inactive' => 'tr_meliscommerce_coupon_page_status_offline',
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_civility',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'date_start',
                                    'type' => 'DateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_date_start',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'date_start',
                                        'dateLabel' => 'tr_meliscommerce_orders_date_start',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'date_end',
                                    'type' => 'DateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_date_end',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'date_end',
                                        'dateLabel' => 'tr_meliscommerce_orders_date_end',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'separator',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_orders_sperator',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'value' => ';',
                                        'maxlength' => '1'
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'cli_status' => array(
                                'name' => 'cli_status',
                                'required' => false,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'separator' => array(
                                'name' => 'separator',
                                'require' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ),
                                        ),
                                    )
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        ),
                    ),
                    'meliscommerce_clients_contact_form' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                            'class' => 'clientContactForm',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cper_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_client_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_is_main_person',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_status',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_civility',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_civility tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_civility',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_firstname',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_fname',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_fname tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_firstname',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_name tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_name',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_middle_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_mname',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_mname tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_middle_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_lang_id',
                                    'type' => 'EcomLanguageSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_language',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_language tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_lang_id',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_email',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_email_address',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_email_address tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_email',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_password',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_password',
                                        'label_options' => array(
                                            'disable_html_escape' => true,
                                        )
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_password',
                                        'Type' => 'password',
                                        'autocomplete' => 'off',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_confirm_password',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_confirm_password',
                                        'label_options' => array(
                                            'disable_html_escape' => true,
                                        )
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_confirm_password',
                                        'Type' => 'password',
                                        'autocomplete' => 'off',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_job_title',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_job_title',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_job_title tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_job_title',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_job_service',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_job_service',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_job_service tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_job_service',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_tel_mobile',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_mobile_num',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_mobile_num tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_tel_mobile',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cper_tel_landline',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_tp_num',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_tp_num tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cper_tel_landline',
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(
                            'cper_civility' => array(
                                'name'     => 'cper_civility',
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
                            'cper_firstname' => array(
                                'name'     => 'cper_firstname',
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
                            'cper_name' => array(
                                'name'     => 'cper_name',
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
                            'cper_lang_id' => array(
                                'name'     => 'cper_lang_id',
                                'required' => true,
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
                            'cper_email' => array(
                                'name'     => 'cper_email',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'EmailAddress',
                                        'options' => array(
                                            'domain'   => 'true',
                                            'hostname' => 'true',
                                            'mx'       => 'true',
                                            'deep'     => 'true',
                                            'message'  => 'tr_meliscommerce_client_Contact_invalid_email',
                                        )
                                    ),
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ),
                                        ),
                                    ),
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
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'cper_password' => array(
                                'name'     => 'cper_password',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => '\MelisCommerce\Validator\MelisPasswordValidator',
                                        'options' => array(
                                            'min' => 8,
                                            'messages' => array(
                                                \MelisCommerce\Validator\MelisPasswordValidator::TOO_SHORT => 'tr_meliscommerce_client_Contact_password_error_low',
                                                \MelisCommerce\Validator\MelisPasswordValidator::NO_DIGIT => 'tr_meliscommerce_client_Contact_password_regex_not_match',
                                                \MelisCommerce\Validator\MelisPasswordValidator::NO_LOWER => 'tr_meliscommerce_client_Contact_password_regex_not_match',
                                            ),
                                        ),
                                    ),
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
                            'cper_confirm_password' => array(
                                'name'     => 'cper_confirm_password',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'Identical',
                                        'options' => array(
                                            'token' => 'cper_password',
                                            'messages' => array(
                                                \Zend\Validator\Identical::NOT_SAME => 'tr_meliscommerce_client_Contact_confirm_password_not_match'
                                            )
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
                            'cper_job_title' => array(
                                'name'     => 'cper_job_title',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 80,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_80',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'cper_job_service' => array(
                                'name'     => 'cper_job_service',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 80,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_80',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'cper_tel_mobile' => array(
                                'name'     => 'cper_tel_mobile',
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
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'cper_tel_landline' => array(
                                'name'     => 'cper_tel_landline',
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
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        )
                    ),
                    'meliscommerce_clients_addresses_form' => array(
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
                                    'name' => 'cadd_id',
                                    'type' => 'hidden',
                                )
                            ),
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
                                    'name' => 'cadd_address_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_name tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_address_name',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_type',
                                    'type' => 'EcomAddressTypeSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_address_type',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_type tooltip',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_type',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_civility',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_civility tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_fname tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_firstname',
                                        'required' => 'required', 
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_name',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_name tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cadd_name',
                                        'required' => 'required',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cadd_middle_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Contact_mname',
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_mname tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_street_num tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_street_name tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_building_name tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_stairs tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_city tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_state tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_country tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_zipcode tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_company_name tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_mobile_number tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_phone_landline tooltip',
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
//                                         'tooltip' => 'tr_meliscommerce_client_Contact_address_additional_information tooltip',
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
                            'cadd_type' => array(
                                'name'     => 'cadd_type',
                                'required' => true,
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
                    'meliscommerce_clients_company_form' => array(
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
                                    'name' => 'ccomp_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ccomp_client_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ccomp_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Company_name'
                                    ),
                                    'attributes' => array(
                                        'id' => 'ccomp_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ccomp_number_id',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Company_id'
                                    ),
                                    'attributes' => array(
                                        'id' => 'comp_number_id',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ccomp_vat_number',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Company_vat_num'
                                    ),
                                    'attributes' => array(
                                        'id' => 'comp_vat_number',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ccomp_group',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_client_Company_ccomp_group'
                                    ),
                                    'attributes' => array(
                                        'id' => 'ccomp_group',
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(
                            'ccomp_name' => array(
                                'name'     => 'ccomp_name',
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
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ccomp_number_id' => array(
                                'name'     => 'ccomp_number_id',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 150,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_150',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ccomp_vat_number' => array(
                                'name'     => 'ccomp_vat_number',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 150,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_150',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ccomp_group' => array(
                                'name'     => 'ccomp_group',
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
                                    ),
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