<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_currency' => array(
                    'attributes' => array(
                        'name' => '',
                        'id' => 'categoryTreeViewSearchForm',
                        'method' => '',
                        'action' => '',
                    ),
                    'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'elements' => array(
                        array(
                            'spec' => array(
                                'name' => 'cur_id',
                                'type' => 'hidden',
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'cur_name',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscommerce_seo_Page_id',
                                ),
                                'attributes' => array(
                                    'id' => 'cur_name',
                                ),
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'cur_code',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscommerce_seo_Page_id',
                                ),
                                'attributes' => array(
                                    'id' => 'cur_code',
                                ),
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'cur_symbol',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscommerce_seo_Page_id',
                                ),
                                'attributes' => array(
                                    'id' => 'cur_symbol',
                                ),
                            ),
                        ),
                    ),
                    'input_filter' => array(
                        'cur_name' => array(
                            'name'     => 'cur_name',
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
                        'cur_code' => array(
                            'name'     => 'cur_code',
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
                        'cur_symbol' => array(
                            'name'     => 'cur_symbol',
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
                    ),
                ),
            ),
        ),
    ),
);