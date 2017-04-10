<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceLostPasswordGetEmailPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerce/ClientLostPassword',
                        
                        // email fo the account
                        'm_email' => '',
                        // submit form flag
                        'm_is_submit' => false,
                        // reset password page link
                        'lost_password_reset_page_link' => 'http://'.$_SERVER['SERVER_NAME'].'/lost-password/id/21',
                        // login page link
                        'redirection_link_is_loggedin'  => 'http://'.$_SERVER['SERVER_NAME'].'/lost-password/id/21',
                        // email details
                        'email' => array(
                            // custome email template
                            'email_template_path' => 'MelisCommerce/emailLayout',
                            'email_from' => '',
                            'email_from_name' => '',
                            'email_to' => '',
                            'email_to_name' => '',
                            'email_subject' => '',
                            'email_content' => '',
                            'email_content_tag_replace' => array(),
                            'email_reply_to' => '',
                        ),
                        
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
            ),
        ),
     ),
);