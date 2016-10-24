<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(
                'meliscommerce_language' => array(
                    'conf' => array(
                        'title' => 'tr_meliscommerce_language',
                    ),
                    'table' => array(
                        'target' => '#tableComLanguageList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComLanguage/getComLangData',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'language-table-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComLanguage',
                                    'action' => 'render-language-list-page-table-filter-limit',
                                ),
                            ),
                            'center' => array(
                                'language-table-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComLanguage',
                                    'action' => 'render-language-list-page-table-filter-search',
                                ),
                            ),
                            'right' => array(
                                'language-table-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComLanguage',
                                    'action' => 'render-language-list-page-table-filter-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'elang_id' => array(
                                'text' => 'tr_meliscommerce_language_elang_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            
                            ),
                            'elang_locale' => array(
                                'text' => 'tr_meliscommerce_language_elang_locale',
                                'css' => array('width' => '50%', 'padding-right' => '0'),
                                'sortable' => true,
                            
                            ),
                            'elang_name' => array(
                                'text' => 'tr_meliscommerce_language_elang_name',
                                'css' => array('width' => '50%', 'padding-right' => '0'),
                                'sortable' => true,
                            
                            ),
                        ),
                        'searchables' => array('elang_id', 'elang_locale', 'elang_name'),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComLanguage',
                                'action' => 'render-language-list-page-content-action-edit',
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComLanguage',
                                'action' => 'render-language-list-page-content-action-delete',
                            ),
                        ),
                    ),
                    'modals' => array(),
                    'forms' => array(
                        'meliscommerce_language_form' => array(
                            'attributes' => array(
                                'name' => 'ecomlanguageform',
                                'id' => 'ecomlanguageform',
                                'method' => 'POST',
                                'action' => '',
                            ),
                            'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                            'elements' => array(
                                array(
                                    'spec' => array(
                                        'name' => 'elang_id',
                                        'type' => 'hidden',
                                        'options' => array(
                                            //'label' => 'tr_meliscommerce_language_elang_id',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_lang_id',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'elang_name',
                                        'type' => 'MelisText',
                                        'options' => array(
                                            'label' => 'tr_meliscommerce_language_elang_name',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_lang_name',
                                            'value' => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'elang_locale',
                                        'type' => 'MelisText',
                                        'options' => array(
                                            'label' => 'tr_meliscommerce_language_elang_locale',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_lang_locale',
                                            'value' => '',
                                        ),
                                    ),
                                ),
                            ),
                            'input_filter' => array(
                                'elang_locale' => array(
                                    'name'     => 'elang_locale',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name'    => 'StringLength',
                                            'options' => array(
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => array(
                                                    \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscore_tool_language_lang_locale_long',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscore_tool_language_lang_locale_empty',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'filters'  => array(
                                        array('name' => 'StripTags'),
                                        array('name' => 'StringTrim'),
                                    ),
                                ),
                                'elang_name' => array(
                                    'name'     => 'elang_name',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name'    => 'StringLength',
                                            'options' => array(
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => array(
                                                    \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscore_tool_language_lang_name_long',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscore_tool_language_lang_name_empty',
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
        ),
    ),
);