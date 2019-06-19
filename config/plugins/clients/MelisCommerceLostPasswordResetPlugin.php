<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceLostPasswordResetPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerce/ClientLostPasswordReset'),
                        'id' => 'userLostPasswordReset',
                        // If true this will automatically login after password reset
                        'm_autologin' => false,
                        // Form to reset password
                        'm_password' => '',
                        'm_password2' => '',
                        // Recovery key of the user
                        'm_recovery_key' => null,
                        // Page redirected after password reset
                        'm_redirection_link_ok' => 'http://www.test.com',
                        
                        'm_lost_password_reset_is_submit' => '',
                        
                        'forms' => array(
                            'lost_password_reset' => array(
                                'attributes' => array(
                                    'name' => 'lost-password-reset-form',
                                    'id' => 'lost-password-reset-form',
                                    'method' => 'POST',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_lost_password_reset_is_submit',
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                        'name' => 'tr_meliscommerce_plugin_password_reset_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceLostPasswordResetPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_password_reset_description',
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
                            'melis_commerce_plugin_lost_password_reset_config' => array(
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
                                            'name' => 'm_redirection_link_ok',
                                            'type' => 'MelisText',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_destination_page_link',
                                                'tooltip' => 'tr_meliscommerce_general_common_destination_page_link tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_redirection_link_ok',
                                                'class' => 'melis-input-group-button',
                                                'data-button-icon' => 'fa fa-sitemap',
                                                'data-button-id' => 'meliscms-site-selector',
                                                'data-callback' => 'generatePageLink',
                                                'required' => 'required'
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_autologin',
                                            'type' => 'Checkbox',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_autologin',
                                                'tooltip' => 'tr_meliscommerce_general_common_autologin tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => array(
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_autologin',
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
                                    'm_redirection_link_ok' => array(
                                        'name'     => 'm_redirection_link_ok',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_autologin' => array(
                                        'name'     => 'm_autologin',
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