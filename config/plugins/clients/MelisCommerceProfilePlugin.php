<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProfilePlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerce/ClientProfile'),
                        'id' => 'userProfile',
                        // User input fields
                        'cper_civility' => '',
                        'cper_firstname' => '',
                        'cper_name' => '',
                        'cper_middle_name' => '',
                        'cper_lang_id' => '',
                        'cper_email' => '',
                        'cper_password' => '',
                        'cper_confirm_password' => '',
                        'cper_job_title' => '',
                        'cper_job_service' => '',
                        'cper_tel_mobile' => '',
                        'cper_tel_landline' => '',
                        'cli_country_id' => '',
                        'profile_is_submit' => false,
                        'forms' => array(
                            'meliscommerce_profile' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => '',
                                    'method' => 'POST',
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
                                            'name' => 'cper_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'cper_civility',
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password_placeholder',
                                                'class' => 'form-control'
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
                                                'placeholder' => 'tr_meliscommerce_client_Contact_password_placeholder',
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                                                'class' => 'form-control'
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
                        'name' => '\tr_meliscommerce_plugin_profile_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceProfilePlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_profile_description',
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
                                )
                            ),
                        )
                    ),
                ),
            ),
        ),
     ),
);