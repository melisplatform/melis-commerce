<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_variants' => array(
                    'meliscommerce_variants_information_form' => array(
                        'attributes' => array(
                            'name' => 'variant',
                            'id' => '',
                            'method' => 'POST',
                            'action' => 'melis/MelisCommerce/MelisComVariant/saveVariantForm',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(                            
                            array(
                                'spec' => array(
                                    'name' => 'var_sku',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_main_information_main_variant_input_label',
                                        'tooltip' => 'tr_meliscommerce_variant_main_information_main_variant_input_label tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => '',                                        
                                    ),                                    
                                ),
                            ),
                        ),
                        'input_filter' => array(                            
                            'var_sku' => array(
                                'name'     => 'var_sku',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_variants_error_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_variant_main_information_main_variant_long_err',
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
                    'meliscommerce_variants_stocks_form' => array(
                        'attributes' => array(
                            'name' => 'stockForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => 'melis/MelisCommerce/MelisComVariant/saveStocksForm',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'stock_id',
                                    'type' => 'Hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                    ),
                                ),
                            ),
//                             array(
//                                 'spec' => array(
//                                     'name' => 'stock_org_qty',
//                                     'type' => 'Hidden',
//                                     'options' => array(
//                                     ),
//                                     'attributes' => array(
//                                     ),
//                                 ),
//                             ),
                            array(
                                'spec' => array(
                                    'name' => 'stock_country_id',
                                    'type' => 'Hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'stock_quantity',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_stocks_quantity_label',
                                        'tooltip' => 'tr_meliscommerce_variant_stocks_quantity_label tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => '',                                        
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'stock_next_fill_up',
                                    'type' => 'DateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_tab_stocks_fill_up',
                                        'tooltip' => 'tr_meliscommerce_variant_tab_stocks_fill_up tooltip',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'stocksDate',
                                        'dateLabel' => 'tr_meliscommerce_variant_tab_stocks_fill_up'
                                    ),
                                ),
                            ),
                             
                        ),
                        'input_filter' => array( 
                            'stock_id' => array(
                                'name'     => 'stock_id',
                                'required' => false,
                                'validators' => array(),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'stock_country_id' => array(
                                'name'     => 'stock_country_id',
                                'required' => false,
                                'validators' => array(),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'stock_quantity' => array(
                                'name'     => 'stock_quantity',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => '\Zend\I18n\Validator\IsInt',
                                        'options' => array(
                                            'messages'=> array(
                                                \Zend\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_variant_prices_variant_price_digit_invalid',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => '\Zend\Validator\GreaterThan',
                                        'options' => array(
                                            'min' => -1,
                                            'messages' => array(
                                                \Zend\Validator\GreaterThan::NOT_GREATER => 'tr_meliscommerce_variant_prices_variant_price_digit_greater',
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
);
