<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(   
                'meliscommerce_currency' => array(   
                    'conf' => array(
                        'title' => 'tr_meliscommerce_currencies',  
                    ),
                    'table' => array(
                        'target' => '#tableComCurrencyList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCurrency/getComCurrencyData',
                        'dataFunction' => '',
                        'ajaxCallback' => 'reInitTableEcomCurrency()',
                        'filters' => array(
                            'left' => array(
                                'country-table-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCurrency',
                                    'action' => 'render-currency-table-filter-limit',
                                ),
                            ),
                            'center' => array(
                                'country-table-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCurrency',
                                    'action' => 'render-currency-table-filter-search',
                                ),
                            ),
                            'right' => array(
                                'country-table-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCurrency',
                                    'action' => 'render-currency-table-filter-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'cur_id' => array(
                                'text' => 'tr_meliscommerce_currency_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                        
                            ),
                            'cur_default' => array(
                                'text' => 'tr_meliscommerce_currency_default',
                                'css' => array('width' => '3%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'cur_status' => array(
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'cur_symbol' => array(
                                'text' => 'tr_meliscommerce_currency_symbol',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'cur_code' => array(
                                'text' => 'tr_meliscommerce_currency_code',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'cur_name' => array(
                                'text' => 'tr_meliscommerce_currency_name',
                                'css' => array('width' => '49%', 'padding-right' => '0'),
                                'sortable' => true,
                            
                            ),

                        ),
                        'searchables' => array('cur_id', 'cur_name', 'cur_code', 'cur_symbol'),
                        'actionButtons' => array(
                            'default' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-content-action-default',
                            ),
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-content-action-edit',
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCurrency',
                                'action' => 'render-currency-content-action-delete',
                            ),
                        ),
                    ),
                    'modals' => array(),
                    'forms' => array(
                        'meliscommerce_currency_form' => array(
                            'attributes' => array(
                                'name' => 'ecomCurrencyForm',
                                'id' => 'ecomCurrencyForm',
                                'method' => 'POST',
                                'action' => '',
                            ),
                            'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                            'elements' => array(
                                array(
                                    'spec' => array(
                                        'name' => 'cur_id',
                                        'type' => 'hidden',
                                        'options' => array(
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_cur_id',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'tmp_cur_code',
                                        'type' => 'hidden',
                                        'options' => array(
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_tmp_cur_code',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'cur_code',
                                        'type' => 'MelisText',
                                        'options' => array(
                                            'label' => 'tr_meliscommerce_currency_code',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_cur_name',
                                            'value' => '',
                                        ),
                                    ),
                                ),
                                
                                array(
                                    'spec' => array(
                                        'name' => 'cur_name',
                                        'type' => 'MelisText',
                                        'options' => array(
                                            'label' => 'tr_meliscommerce_currency_name',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_cur_name',
                                            'value' => '',
                                        ),
                                    ),
                                ),
                                
                                array(
                                    'spec' => array(
                                        'name' => 'cur_symbol',
                                        'type' => 'MelisText',
                                        'options' => array(
                                            'label' => 'tr_meliscommerce_currency_symbol',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_cur_name',
                                            'value' => '',
                                        ),
                                    ),
                                ),

                            ),
                            'input_filter' => array(
                                'cur_code' => array(
                                    'name'     => 'cur_code',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name'    => 'StringLength',
                                            'options' => array(
                                                'encoding' => 'UTF-8',
                                                'max'      => 8,
                                                'messages' => array(
                                                    \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_currency_code_long',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_currency_code_empty',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'filters'  => array(
                                        array('name' => 'StripTags'),
                                        array('name' => 'StringTrim'),
                                    ),
                                ),
                                'cur_name' => array(
                                    'name'     => 'cur_name',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name'    => 'StringLength',
                                            'options' => array(
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => array(
                                                    \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_currency_name_long',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_currency_name_empty',
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
                                            'name'    => 'StringLength',
                                            'options' => array(
                                                'encoding' => 'UTF-8',
                                                'max'      => 5,
                                                'messages' => array(
                                                    \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_currency_symbol_long',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_currency_symbol_empty',
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