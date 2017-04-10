<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceRegisterPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientRegister',
                        'm_autologin' => true,
                        'cper_email' => '',
                        'cper_password' => '',
                        'cper_password2' => '',
                        'cper_civility' => 0,
                        'cper_firstname' => '',
                        'cper_name' => '',
                        'm_country' => 1, // The one fixed in conf.site.php
                        'm_is_submit' => false,
                        'm_redirection_link_ok' => 'http://www.test.com',
                        'forms' => array(
                            'meliscommerce_registration' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => 'registration',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_autologin',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_redirection_link_ok',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_email',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_email_address',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_email',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_email_address',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_password',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_password',
                                                'label_options' => array(
                                                    'disable_html_escape' => true,
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_password',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_password2',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'label_options' => array(
                                                    'disable_html_escape' => true,
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_password2',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
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
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_firstname',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_fname',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_name',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_name',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_country',
                                            'type' => 'EcomPluginCountriesSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Client_country',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_country',
                                                'placeholder' => 'tr_meliscommerce_client_Client_country',
                                            )
                                        )
                                    ),
                                ),
                                'input_filter' => array(
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
                                    'cper_password2' => array(
                                        'name'     => 'cper_password2',
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
                                    'm_country' => array(
                                        'name'     => 'm_country',
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
                            )
                        )
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);