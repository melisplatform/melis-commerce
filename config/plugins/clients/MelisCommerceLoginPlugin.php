<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceLoginPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientLogin',
                        
                        // form fields
                        'm_login' => '',
                        'm_password' => '',
                        'm_remember_me' => '',
                        'm_redirection_link_ok' => 'http://www.test.com',
                        
                        // flag true if a form is submitted
                        'm_is_submit' => false,
                        
                        // Form setup, elements and validators
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
            ),
        ),
     ),
);