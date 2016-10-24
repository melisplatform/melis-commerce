<?php 
    
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_prices' => array(
                    'meliscommerce_prices_form' => array(
                        'attributes' => array(
                            'name' => 'priceForm',
                            'id' => '',
                            'method' => 'POST',
                            'action' => 'melis/MelisCommerce/MelisComVariant/savePricesForm',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'price_id',
                                    'type' => 'Hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'price_country_id',
                                    'type' => 'Hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'price_currency',
                                    'type' => 'Hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'price_net',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_label',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '10'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'price_gross',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_gross_label',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '10'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'price_vat_percent',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_vat_percent_label',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '10'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'price_vat_price',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_vat_price_label',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '10'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'price_other_tax_price',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_variant_prices_variant_price_other_tax_price_label',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'maxlength' => '10'
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'price_id' => array(
                                'name'     => 'price_id',
                                'required' => false,
                                'validators' => array(),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'price_country_id' => array(
                                'name'     => 'price_country_id',
                                'required' => false,
                                'validators' => array(),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'price_currency' => array(
                                'name'     => 'price_currency',
                                'required' => false,
                                'validators' => array(),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'price_net' => array(
                                'name'     => 'price_net',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => array(
                                            'messages' => array(
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_gross' => array(
                                'name'     => 'price_gross',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => array(
                                            'messages' => array(
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_vat_percent' => array(
                                'name'     => 'price_vat_percent',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => array(
                                            'messages' => array(
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_vat_price' => array(
                                'name'     => 'price_vat_price',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => array(
                                            'messages' => array(
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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
                            'price_other_tax_price' => array(
                                'name'     => 'price_other_tax_price',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => '\MelisCommerce\Validator\MelisPriceValidator',
                                        'options' => array(
                                            'messages' => array(
                                                \MelisCommerce\Validator\MelisPriceValidator::INVALID_PRICE => 'tr_meliscommerce_variant_prices_variant_price_digit_decimal_invalid',
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