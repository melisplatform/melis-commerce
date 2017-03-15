<?php
// Client plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceLoginPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientLogin',
                        'm_login' => '',
                        'm_password' => '',
                        'm_remember_me' => '',
                        'm_redirection_link_ok' => 'http://www.test.com',
                        'm_is_submit' => false,
                        'forms' => array(
                            'meliscommerce_login' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => 'login',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_redirection_link_ok',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_login',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_email_address',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_login',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_email_address',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_password',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_password',
                                                'label_options' => array(
                                                    'disable_html_escape' => true,
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_password',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_remember_me',
                                            'type' => 'Checkbox',
                                            'options' => array(
                                                'label' => 'Remember me',
                                                'use_hidden_element' => false,
                                                'checked_value' => '1',
                                            )
                                        )
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_login' => array(
                                        'name'     => 'm_login',
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
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_password' => array(
                                        'name'     => 'm_password',
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
                                    'm_remember_me' => array(
                                        'name'     => 'm_remember_me',
                                        'required' => false,
                                        'validators' => array(),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    )
                                ),
                            )
                        )
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceLostPasswordGetEmailPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientLostPassword',
                        'm_email' => '',
                        'm_is_submit' => false,
                        'lost_password_reset_page_link' => 'http://'.$_SERVER['SERVER_NAME'].'/lost-password/id/21',
                        'redirection_link_is_loggedin'  => 'http://'.$_SERVER['SERVER_NAME'].'/lost-password/id/21',
                        'email_template_path' => 'MelisCommerce/emailLayout',
                        'forms' => array(
                            'lost_password' => array(
                                'attributes' => array(
                                    'name' => 'lost_password',
                                    'id' => 'lost_password',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_is_submit',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_email',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_email_address',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_email',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_email_address',
                                            )
                                        )
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_email' => array(
                                        'name'     => 'm_email',
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
                'MelisCommerceLostPasswordResetPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientLostPasswordReset',
                        'm_autologin' => false,
                        'm_password' => '',
                        'm_password2' => '',
                        'm_recovery_key' => null,
                        'redirect_link_not_loggedin' => 'http://'.$_SERVER['SERVER_NAME'].'/login-/-register/id/19',
                        'redirect_link_loggedin' => 'http://'.$_SERVER['SERVER_NAME'].'/my-account/id/22',
                        'forms' => array(
                            'lost_password_reset' => array(
                                'attributes' => array(
                                    'name' => 'lost-password-reset-form',
                                    'id' => 'lost-password-reset-form',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_is_submit',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_autologin',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_recovery_key',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_password',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_password',
                                                'label_options' => array(
                                                    'disable_html_escape' => true,
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_password',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_password2',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'label_options' => array(
                                                    'disable_html_escape' => true,
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_password2',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                            )
                                        )
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_autologin' => array(
                                        'name'     => 'm_autologin',
                                        'required' => false,
                                        'validators' => array(
                                            
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_password' => array(
                                        'name'     => 'm_password',
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
                                    'm_password2' => array(
                                        'name'     => 'm_password2',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'Identical',
                                                'options' => array(
                                                    'token' => 'm_password',
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
                                ),
                            )
                        )
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceRegisterPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientRegister',
                        'm_autologin' => true,
                        'm_email' => '',
                        'm_password' => '',
                        'm_password2' => '',
                        'm_civility' => 0,
                        'm_firstname' => '',
                        'm_lastname' => '',
                        'm_language' => 1, // The one fixed in conf.site.php
                        'm_country' => 1, // The one fixed in conf.site.php
                        'm_is_submit' => false,
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
                                            'name' => 'm_email',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_email_address',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_email',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_email_address',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_password',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_password',
                                                'label_options' => array(
                                                    'disable_html_escape' => true,
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_password',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_password2',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'label_options' => array(
                                                    'disable_html_escape' => true,
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_password2',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_confirm_password',
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_civility',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_firstname',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_firstname',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_fname',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_lastname',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_lastname',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_language',
                                            'type' => 'EcomPluginLanguageSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_language',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_language',
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
                                    'm_email' => array(
                                        'name'     => 'm_email',
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
                                    'm_password' => array(
                                        'name'     => 'm_password',
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
                                    'm_password2' => array(
                                        'name'     => 'm_password2',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'Identical',
                                                'options' => array(
                                                    'token' => 'm_password',
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
                                    'm_civility' => array(
                                        'name'     => 'm_civility',
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
                                    'm_firstname' => array(
                                        'name'     => 'm_firstname',
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
                                    'm_lastname' => array(
                                        'name'     => 'm_lastname',
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
                                    'm_language' => array(
                                        'name'     => 'm_language',
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
                'MelisCommerceAccountPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientAccount',
                        'profile_parameter' => array(),
                        'delivery_address_parameter' => array(),
                        'billing_address_parameter' => array(),
                        'include_mycart_parameter' => array(),
                        'm_redirection_link_not_loggedin' => 'http://www.test.com',
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceProfilePlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientProfile',
                        'cper_status' => '',
                        'cper_civility' => '',
                        'cper_firstname' => '',
                        'cper_name' => '',
                        'cper_middle_name' => '',
                        'cper_lang_id' => '',
                        'cli_country_id' => '',
                        'cper_email' => '',
                        'cper_password' => '',
                        'cper_confirm_password' => '',
                        'cper_job_title' => '',
                        'cper_job_service' => '',
                        'cper_tel_mobile' => '',
                        'cper_tel_landline' => '',
                        'profile_is_submit' => false,
                        'redirection_link_not_loggedin' => 'http://www.test.com',
                        'forms' => array(
                            'meliscommerce_profile' => array(
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
                                            'name' => 'profile_is_submit',
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
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_middle_name',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_mname',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_middle_name',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_lang_id',
                                            'type' => 'EcomPluginLanguageSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_language',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_lang_id',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cli_country_id',
                                            'type' => 'EcomPluginCountriesSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Client_country',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'country',
                                                'placeholder' => 'tr_meliscommerce_client_Client_country',
                                            )
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
                                                'Type' => 'password',
                                                'autocomplete' => 'off',
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password_placeholder'
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_confirm_password',
                                            'type' => 'Text',
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password_placeholder'
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_job_title',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_job_title',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_job_title',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_job_service',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_job_service',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_job_service',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_tel_mobile',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_mobile_num',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_tel_mobile',
                                            )
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cper_tel_landline',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_tp_num',
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
                                    )
                                )
                            )
                        )
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceDeliveryAddressPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientDeliveryAddress',
                        'show_select_address_data' => false,
                        'cadd_address_name' => '',
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
                        'delivery_add_is_submit' => false,
                        'redirection_link_not_loggedin' => 'http://www.test.com',
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
                                            'name' => 'cadd_id',
                                            'type' => 'hidden',
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'delivery_add_is_submit',
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
                    ),
                    'melis' => array(),
                ),
                'MelisCommerceBillingAddressPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientBillingAddress',
                        'show_select_address_data' => false,
                        'cadd_address_name' => '',
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
                        'billing_add_is_submit' => false,
                        'redirection_link_not_loggedin' => 'http://www.test.com',
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
                'MelisCommerceMyCartPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientMyCart',
                        'redirection_link_not_loggedin' => 'http://www.test.com',
                    ),
                    'melis' => array(),
                ),
            ),
        ),
    ),
);

