<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(
                'meliscommerce_country' => array(
                    'conf' => array(
                        'title' => 'tr_meliscommerce_countries',
                    ),
                    'table' => array(
                        'target' => '#tableComCountryList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComCountry/getComCountryData',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'country-table-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCountry',
                                    'action' => 'render-country-list-page-table-filter-limit',
                                ),
                            ),
                            'center' => array(
                                'country-table-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCountry',
                                    'action' => 'render-country-list-page-table-filter-search',
                                ),
                            ),
                            'right' => array(
                                'country-table-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCountry',
                                    'action' => 'render-country-list-page-table-filter-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'ctry_id' => array(
                                'text' => 'tr_meliscommerce_country_ctry_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            
                            ),
                            'ctry_flag' => array(
                                'text' => 'tr_meliscommerce_country_ctry_flag',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,

                            ),
                            'ctry_status' => array(
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'ctry_name' => array(
                                'text' => 'tr_meliscommerce_country_ctry_name',
                                'css' => array('width' => '40%', 'padding-right' => '0'),
                                'sortable' => true,
                            
                            ),
                            'cur_name' => array(
                                'text' => 'tr_meliscommerce_country_ctry_currency_id',
                                'css' => array('width' => '40%', 'padding-right' => '0'),
                                'sortable' => true,
                            
                            ),
                        ),
                        'searchables' => array('melis_ecom_country.ctry_id', 'melis_ecom_country.ctry_name', 'melis_ecom_currency.cur_name', 'melis_ecom_currency.cur_symbol'),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCountry',
                                'action' => 'render-country-list-page-content-action-edit',
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCountry',
                                'action' => 'render-country-list-page-content-action-delete',
                            ),
                        ),
                    ),
                    'modals' => array(),
                    'forms' => array(
                        'meliscommerce_country_form' => array(
                            'attributes' => array(
                                'name' => 'ecomCountryform',
                                'id' => 'ecomCountryform',
                                'method' => 'POST',
                                'action' => '',
                            ),
                            'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                            'elements' => array(
                                array(
                                    'spec' => array(
                                        'name' => 'ctry_id',
                                        'type' => 'hidden',
                                        'options' => array(
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_lang_id',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'tmp_ctry_name',
                                        'type' => 'hidden',
                                        'options' => array(
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_tmp_ctry_name',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'ctry_flag',
                                        'type' => 'file',
                                        'options' => array(
                                            //'label' => 'tr_meliscommerce_country_ctry_flag',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_ctry_flag',
                                            'value' => '',
                                            'placeholder' => 'tr_meliscommerce_country_ctry_flag_choose',
                                            'data-buttonText' => 'Select Flag',
                                            'class' => 'filestyle',
                                            'onchange' => 'imagePreview("#imgCountryFlag", this);',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'ctry_name',
                                        'type' => 'MelisText',
                                        'options' => array(
                                            'label' => 'tr_meliscommerce_country_ctry_name',
                                            'tooltip' => 'tr_meliscommerce_country_ctry_name tooltip',
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_lang_name',
                                            'value' => '',
                                            'required' => 'required',
                                        ),
                                    ),
                                ),
                                array(
                                    'spec' => array(
                                        'name' => 'ctry_currency_id',
                                        'type' => 'EcomCurrencyAllStatusSelect',
                                        'options' => array(
                                            'label' => 'tr_meliscommerce_country_ctry_select_empty',
                                            'tooltip' => 'tr_meliscommerce_country_ctry_select_empty tooltip',
                                            'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                            'disable_inarray_validator' => true,
                                        ),
                                        'attributes' => array(
                                            'id' => 'id_lang_locale',
                                            'value' => '',
                                        ),
                                    ),
                                ),
                            ),
                            'input_filter' => array(
                                'ctry_name' => array(
                                    'name'     => 'ctry_name',
                                    'required' => true,
                                    'validators' => array(
                                        array(
                                            'name'    => 'StringLength',
                                            'options' => array(
                                                'encoding' => 'UTF-8',
                                                'max'      => 45,
                                                'messages' => array(
                                                    \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_country_ctry_name_long',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                'messages' => array(
                                                    \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_country_ctry_name_empty',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'filters'  => array(
                                        array('name' => 'StripTags'),
                                        array('name' => 'StringTrim'),
                                    ),
                                ),
                                'ctry_currency_id' => array(
                                    'name'     => 'ctry_currency_id',
                                    'required' => false,
                                    'validators' => array(
                                        array(
                                            'name'    => 'StringLength',
                                            'options' => array(
                                                'encoding' => 'UTF-8',
                                                'max'      => 11,
                                                'messages' => array(
                                                    \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_country_ctry_currency_id_long',
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