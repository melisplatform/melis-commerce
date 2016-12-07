<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_seo' => array(
                    'meliscommerce_seo_form' => array(
                        'attributes' => array(
                            'name' => 'seoForm',
                            'id' => 'seoForm',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'eseo_id',
                                    'type' => 'hidden',
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'eseo_lang_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_seo_Lang_id',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'eseo_page_id',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_seo_Page_id',
                                    ),
                                    'attributes' => array(
                                        'id' => 'eseo_page_id',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'eseo_meta_title',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_seo_Meta_title',
                                    ),
                                    'attributes' => array(
                                        'id' => 'eseo_meta_title',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'eseo_meta_description',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_seo_Meta_description',
                                    ),
                                    'attributes' => array(
                                        'id' => 'eseo_meta_description',
                                        'rows' => 5,
                                        'class' => 'melis-seo-desc form-control'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'eseo_url',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_seo_Url',
                                    ),
                                    'attributes' => array(
                                        'id' => 'eseo_url',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'eseo_url_redirect',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_seo_Url_redirect',
                                        'label_options' => array(
                                            'disable_html_escape' => true,
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => 'eseo_url_redirect',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'eseo_url_301',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_seo_Url_301',
                                        'label_options' => array(
                                            'disable_html_escape' => true,
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => 'eseo_url_301',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'eseo_lang_id' => array(
                                'name'     => 'eseo_lang_id',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_seo_Seo_input_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'eseo_page_id' => array(
                                'name'     => 'eseo_page_id',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'IsInt',
                                        'options' => array(
                                            'messages' => array(
                                                Zend\I18n\Validator\IsInt::NOT_INT => 'tr_meliscommerce_seo_Page_id_invalid',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'eseo_meta_title' => array(
                                'name'     => 'eseo_meta_title',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_title_too_long_255',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'eseo_meta_description' => array(
                                'name'     => 'eseo_meta_description',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_desc_too_long_255',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'eseo_url' => array(
                                'name'     => 'eseo_url',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_url_too_long_255',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'eseo_url_redirect' => array(
                                'name'     => 'eseo_url_redirect',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_url_redirect_too_long_255',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'eseo_url_301' => array(
                                'name'     => 'eseo_url_301',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_url_301_too_long_255',
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
                )
            )
        )
    )
);