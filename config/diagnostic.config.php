<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'plugins' => [
        'diagnostic' => [
            'MelisCommerce' => [
                // location of your test folder inside the module
                'testFolder' => 'test',
                // moduleTestName is the name of your test folder inside 'testFolder'
                'moduleTestName' => 'MelisCommerceTest',
                // this should be properly setup so we can recreate the factory of the database
                'db' => [
                    'getMelisEcomAssocVariantTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomAssocVariant',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTable',
                        'db_table_name' => 'melis_ecom_assoc_variant',
                    ],
                    'getMelisEcomAssocVariantTypeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomAssocVariantType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTypeTable',
                        'db_table_name' => 'melis_ecom_assoc_variants_type',
                    ],
                    'getMelisEcomAttributeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomAttribute',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTable',
                        'db_table_name' => 'melis_ecom_attribute',
                    ],
                    'getMelisEcomAttributeTransTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeTransTable',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable',
                        'db_table_name' => 'melis_ecom_attribute_trans',
                    ],
                    'getMelisEcomAttributeTypeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable',
                        'db_table_name' => 'melis_ecom_attribute_type',
                    ],
                    'getMelisEcomAttributeValueTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeValue',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTable',
                        'db_table_name' => 'melis_ecom_attribute_value',
                    ],
                    'getMelisEcomAttributeValueTransTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeValueTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable',
                        'db_table_name' => 'melis_ecom_attribute_value_trans',
                    ],
                    'getMelisEcomBasketAnonymousTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomBasketAnonymous',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable',
                        'db_table_name' => 'melis_ecom_basket_anonymous',
                    ],
                    'getMelisEcomBasketPersistentTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomBasketPersistent',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable',
                        'db_table_name' => 'melis_ecom_basket_persistent',
                    ],
                    'getMelisEcomCategoryTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCategory',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTable',
                        'db_table_name' => 'melis_ecom_category',
                    ],
                    'getMelisEcomCategoryTransTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCategoryTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTransTable',
                        'db_table_name' => 'melis_ecom_category_trans',
                    ],
//                     'getMelisEcomCivilityTable' => [
//                         'model' => 'MelisCommerce\Model\MelisEcomCivility',
//                         'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCivilityTable',
//                         'db_table_name' => 'melis_ecom_civility',
//                     ],
                    'getMelisEcomCivilityTransTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCivilityTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCivilityTransTable',
                        'db_table_name' => 'melis_ecom_civility_trans',
                    ],
                    'getMelisEcomClientTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomClient',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientTable',
                        'db_table_name' => 'melis_ecom_client',
                    ],
                    'getMelisEcomClientAddressTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomClientAddress',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTable',
                        'db_table_name' => 'melis_ecom_client_address',
                    ],
                    'getMelisEcomClientAddressTypeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomClientAddressType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable',
                        'db_table_name' => 'melis_ecom_client_address_type',
                    ],
                    'getMelisEcomClientAddressTypeTransTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomClientAddressTypeTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable',
                        'db_table_name' => 'melis_ecom_client_address_type',
                    ],
                    'getMelisEcomClientCompanyTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomClientCompany',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientCompanyTable',
                        'db_table_name' => 'melis_ecom_client_company',
                    ],
                    'getMelisEcomClientPersonTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomClientPerson',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientPersonTable',
                        'db_table_name' => 'melis_ecom_client_person',
                    ],
                    'getMelisEcomCountryCategoryTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCountryCategory',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable',
                        'db_table_name' => 'melis_ecom_country_category',
                    ],
                    'getMelisEcomCountryTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCountry',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCountryTable',
                        'db_table_name' => 'melis_ecom_country',
                    ],
                    'getMelisEcomCouponTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCoupon',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCouponTable',
                        'db_table_name' => 'melis_ecom_coupon',
                    ],
                    'getMelisEcomCouponClientTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCouponClient',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCouponClientTable',
                        'db_table_name' => 'melis_ecom_coupon_client',
                    ],
                    'getMelisEcomCouponOrderTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCouponOrder',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCouponOrderTable',
                        'db_table_name' => 'melis_ecom_coupon_order',
                    ],
                    'getMelisEcomCurrencyTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomCurrency',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCurrencyTable',
                        'db_table_name' => 'melis_ecom_currency',
                    ],
                    'getMelisEcomDocumentTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomDocument',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomDocumentTable',
                        'db_table_name' => 'melis_ecom_document',
                    ],
                    'getMelisEcomDocRelationsTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomDocRelations',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomDocRelationsTable',
                        'db_table_name' => 'melis_ecom_doc_relations',
                    ],
                    'getMelisEcomDocTypeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomDocType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomDocTypeTable',
                        'db_table_name' => 'melis_ecom_doc_type',
                    ],
                    'getMelisEcomLangTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomLang',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomLangTable',
                        'db_table_name' => 'melis_ecom_lang',
                    ],
                    'getMelisEcomOrderTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrder',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderTable',
                        'db_table_name' => 'melis_ecom_order',
                    ],
                    'getMelisEcomOrderAddressTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderAddress',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderAddressTable',
                        'db_table_name' => 'melis_ecom_order_address',
                    ],
                    'getMelisEcomOrderBasketTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderBasket',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderBasketTable',
                        'db_table_name' => 'melis_ecom_order_basket',
                    ],
                    'getMelisEcomOrderMessageTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderMessage',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderMessageTable',
                        'db_table_name' => 'melis_ecom_order_message',
                    ],
                    'getMelisEcomOrderPaymentTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderPayment',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable',
                        'db_table_name' => 'melis_ecom_order_payment',
                    ],
                    'getMelisEcomOrderPaymentTypeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderPaymentType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable',
                        'db_table_name' => 'melis_ecom_order_payment_type',
                    ],
                    'getMelisEcomOrderShippingTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderShipping',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderShippingTable',
                        'db_table_name' => 'melis_ecom_order_shipping',
                    ],
                    'getMelisEcomOrderStatusTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderStatus',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTable',
                        'db_table_name' => 'melis_ecom_order_status',
                    ],
                    'getMelisEcomOrderStatusTransTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomOrderStatusTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable',
                        'db_table_name' => 'melis_ecom_order_status_trans',
                    ],
                    'getMelisEcomPriceTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomPrice',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomPriceTable',
                        'db_table_name' => 'melis_ecom_price',
                    ],
                    'getMelisEcomProductTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomProduct',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductTable',
                        'db_table_name' => 'melis_ecom_product',
                    ],
                    'getMelisEcomProductAttributeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomProductAttribute',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductAttributeTable',
                        'db_table_name' => 'melis_ecom_product_attribute',
                    ],
                    'getMelisEcomProductCategoryTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomProductCategory',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable',
                        'db_table_name' => 'melis_ecom_product_category',
                    ],
                    'getMelisEcomProductTextTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomProductText',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTable',
                        'db_table_name' => 'melis_ecom_product_text',
                    ],
                    'getMelisEcomProductTextTypeTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomProductTextType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable',
                        'db_table_name' => 'melis_ecom_product_text_type',
                    ],
                    'getMelisEcomSeoTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomSeo',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomSeoTable',
                        'db_table_name' => 'melis_ecom_seo',
                    ],
                    'getMelisEcomVariantTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomVariant',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomVariantTable',
                        'db_table_name' => 'melis_ecom_variant',
                    ],
                    'getMelisEcomProductVariantAttributeValueTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomVariantAttributeValue',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable',
                        'db_table_name' => 'melis_ecom_variant_attribute_value',
                    ],
                    'getMelisEcomVariantStockTable' => [
                        'model' => 'MelisCommerce\Model\MelisEcomVariantStock',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomVariantStockTable',
                        'db_table_name' => 'melis_ecom_variant_stock',
                    ],
                ],
                // these are the various types of methods that you would like to give payloads for testing
                // you don't have to put all the methods in the test controller,
                // instead, just put the methods that will be needing or requiring the payloads for your test.
                'methods' => [
                    
                    'testComLanguage' => [
                        'payloads' => [
                            'create' => [
                                [
                                    'elang_locale' => 'tt_TT',
                                    'elang_name' => 'PHP Test',
                                    'elang_status' => 1,
                                ],
                            ],
                            'read' => [
                                [
                                    'column' => 'elang_locale',
                                    'value' => 'tt_TT',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'elang_locale',
                                    'value' => 'tt_TT',
                                ],
                            ]
                        ],
                    ],
                    
                    'testComCurrency' => [
                        'payloads' => [
                            'create' => [
                                [
                                    'cur_default' => 0,
                                    'cur_name' => 'PHP TEST',
                                    'cur_code' => 'PHP',
                                    'cur_symbol' => 'P',
                                    'cur_status' => 1,
                                ],
                            ],
                            'read' => [
                                [
                                    'column' => 'cur_name',
                                    'value' => 'PHP TEST',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'cur_name',
                                    'value' => 'PHP TEST',
                                ],
                            ],
                        ],
                    ],
                    
                    'testComCountry' => [
                        'payloads' => [
                            'create' => [
                                [
                                    'ctry_name' => 'PHP Test',
                                    'ctry_currency_id' => '',
                                    'ctry_status' => 1,
                                    'ctry_currency_id' => '-1',
                                ],
                            ],
                            'read' => [
                                [
                                    'column' => 'ctry_name',
                                    'value' => 'PHP Test',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'ctry_name',
                                    'value' => 'PHP Test',
                                ],
                            ],
                        ],
                    ],
                    
                    'testComCategories' => [
                        'payloads' => [
                            'create' => [
                                [
                                    'melis_ecom_category' => [
                                        'cat_reference' => 'PHP Category Ref',
                                        'cat_date_creation' => date('Y-m-d H:i:s'),
                                        'cat_user_id_creation' => 1,
                                        'cat_date_edit' => date('Y-m-d H:i:s'),
                                        'cat_user_id_edit' => 1,
                                    ],
                                    'melis_ecom_category_trans' => [
                                        'catt_name' => 'PHP Category Test Name',
                                        'catt_description' => 'This category is created using the PHPunit tests',
                                    ],
                                ],
                                [
                                    'melis_ecom_category' => [
                                        'cat_reference' => 'PHP Category Ref2',
                                        'cat_date_creation' => date('Y-m-d H:i:s'),
                                        'cat_user_id_creation' => 1,
                                        'cat_date_edit' => date('Y-m-d H:i:s'),
                                        'cat_user_id_edit' => 1,
                                    ],
                                    'melis_ecom_category_trans' => [
                                        'catt_name' => 'PHP Category Test Name2',
                                        'catt_description' => 'This category is created using the PHPunit tests2',
                                    ],
                                ],
                            ],
                            'read' => [
                                [
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref',
                                ],
                                [
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref2',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref',
                                ],
                                [
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref2',
                                ],
                            ],
                        ],
                    ],
                    
                    'testComProducts' => [
                        'payloads' => [
                            'create' => [
                                [
                                    'melis_ecom_product_text_type' => [
                                        'ptt_code' => 'PHP',
                                        'ptt_name' => 'PHP TEST',
                                        'ptt_field_type' => 1,
                                    ],
                                    'melis_ecom_product' => [
                                        'prd_reference' => 'PHP product test ref',
                                        'prd_status' => 1,
                                        'prd_date_creation' => date('Y-m-d H:i:s'),
                                        'prd_user_id_creation' => 1,
                                    ],
                                    'melis_ecom_product_text' => [
                                        'ptxt_field_short' => 'Product Text Test PHP',
                                    ],
                                    'melis_ecom_product_category' => [
                                        'pcat_order' => 1,
                                    ],
                                    'melis_ecom_price' => [
                                        'price_net' => 100,
                                        'price_gross' => 80,
                                        'price_vat_percent' => 10,
                                        'price_vat_price' => 11,
                                    ],
                                ],
                            ],
                            'read' => [
                                [
                                    'column' => 'prd_reference',
                                    'value' => 'PHP product test ref',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'prd_reference',
                                    'value' => 'PHP product test ref',
                                ],
                            ],
                        ],
                    ],
                    
                    'testComVariants' => [
                        'payloads' => [
                            'create' => [
                                [
                                    'melis_ecom_variant' => [
                                        'var_status' => 1,
                                        'var_sku'    => 'PHPUNITTEST123',
                                        'var_main_variant' => 1,
                                        'var_date_creation' => date('Y-m-d H:i:s'),
                                        'var_user_id_creation' => 1,
                                    ],
                                    'melis_ecom_variant_stock' => [
                                        'stock_quantity' => 100
                                    ],
                                    'melis_ecom_price' => [
                                        'price_net' => 100,
                                        'price_gross' => 80,
                                        'price_vat_percent' => 10,
                                        'price_vat_price' => 11,
                                    ],
                                ],
                            ],
                            'read' =>  [
                                [
                                    'column' => 'var_sku',
                                    'value' => 'PHPUNITTEST123',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'var_sku',
                                    'value' => 'PHPUNITTEST123',
                                ],
                            ],
                        ],
                    ],
                    
                    'testComAttributes' => [
                        'payloads' => [
                            'create'=> [
                                [
                                    'melis_ecom_attribute' => [
                                        'attr_type_id' => 1,
                                        'attr_status' => 1,
                                        'attr_reference' => 'PHP Attribute Test',
                                        'attr_visible' => 1,
                                        'attr_searchable' => 1,
                                    ],
                                    'melis_ecom_attribute_trans' => [
                                        'atrans_name' => 'PHP Attribute Test Trans',
                                        'atrans_description' => 'PHP Attribute Test Trans',
                                    ],
                                    'melis_ecom_attribute_value' => [
                                        'atval_type_id' => 1,
                                        'atval_reference' => 'PHP Attr Val Test',
                                    ],
                                    'melis_ecom_attribute_value_trans' => [
                                        'avt_v_int' => 143,
                                    ],
                                ],
                            ],
                            'read'=> [
                                [
                                    'column' => 'attr_reference',
                                    'value' => 'PHP Attribute Test',
                                ],
                            ],
                            'delete'=> [
                               [
                                   'column' => 'attr_reference',
                                   'value' => 'PHP Attribute Test',
                               ],
                            ],
                        ],
                    ],
                    
                    'testComClients' => [
                        'payloads' => [
                            'create' => [
                                [
                                    'melis_ecom_client' => [
                                        'cli_status' => 1,
                                        'cli_date_creation' => date('Y-m-d H:i:s'),
                                    ],
                                    'melis_ecom_client_person' => [
                                        'cper_status' => 1,
                                        'cper_is_main_person' => 1,
                                        'cper_email' => 'phptest12345@mail.com',
                                        'cper_password' => 'passwordni',
                                        'cper_password_recovery_key' => 'hashkeyni',
                                        'cper_civility' => 1,
                                        'cper_name' => 'Php',
                                        'cper_middle_name' => 'Test',
                                        'cper_firstname' => 'Php',
                                        'cper_job_title' => 'Tester',
                                        'cper_job_service' => 'Shell',
                                        'cper_tel_mobile' => '123456789',
                                        'cper_tel_landline' => '123456789',
                                        'cper_date_creation' => date('Y-m-d H:i:s'),
                                    ],
                                    'melis_ecom_client_company' => [
                                        'ccomp_name' => 'PHPTest company',
                                        'ccomp_number_id' => 14344,
                                        'ccomp_vat_number' => 12345,
                                        'ccomp_date_creation' => date('Y-m-d H:i:s'),
                                    ],
                                    'melis_ecom_client_address' => [
                                        'cadd_type' => 1,
                                        'cadd_address_name' => 'PHPTest address',
                                        'cadd_civility' => 1,
                                        'cadd_name' => 'Php',
                                        'cadd_middle_name' => 'Test',
                                        'cadd_firstname' => 'Php',
                                        'cadd_num' => 1,
                                        'cadd_stairs' => 2,
                                        'cadd_building_name' => 'Winland',
                                        'cadd_company' => 'Php tester',
                                        'cadd_street' => 'Escario',
                                        'cadd_zipcode' => 6000,
                                        'cadd_city' => 'Cebu',
                                        'cadd_state' => 'Cebu',
                                        'cadd_country' => 'Philippines',
                                        'cadd_phone_mobile' => 123456789,
                                        'cadd_phone_landline' => 987654321,
                                        'cadd_creation_date' => date('Y-m-d H:i:s'),
                                    ],
                                ],
                            ],
                            'read' => [
                                [
                                    'column' => 'cper_email',
                                    'value' => 'phptest12345@mail.com',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'cper_email',
                                    'value' => 'phptest12345@mail.com',
                                ],
                            ],
                        ],
                    ],
                    
                    'testComOrders' => [
                        'payloads' => [
                            'create' => [
                               [
                                   'order_product_text' => [
                                       'column' => 'ptxt_field_short', 
                                       'value'   => 'Product Text Test PHP',
                                   ],
                                   'order_product' => [
                                       'column' => 'prd_reference',
                                       'value' => 'PHP product test ref',
                                   ],
                                   'order_variant' => [
                                       'column' => 'var_sku',
                                       'value' => 'PHPUNITTEST123',
                                   ],
                                   'order_category' => [
                                       'column' => 'catt_name',
                                       'value' => 'PHP Category Test Name',
                                    ],
                                   'melis_ecom_order' => [
                                       'ord_status' => 1,
                                       'ord_reference' => 'Php Test Order',
                                       'ord_date_creation' => date('Y-m-d H:i:s'),
                                       'ord_billing_address' => -1,
                                       'ord_delivery_address' => -1,
                                   ],
                                   'melis_ecom_order_basket' => [
                                       'obas_quantity' => 1,
                                       'obas_sent' => 0,
                                   ],
                                   
                                   'melis_ecom_order_address' => [
                                       'oadd_civility' => 1,
                                       'oadd_name' => 'Tester',
                                       'oadd_middle_name' => 'Middle',
                                       'oadd_firstname' => 'Php',
                                       'oadd_num' => 1,
                                       'oadd_stairs' => 2,
                                       'oadd_building_name' => 'Winland',
                                       'oadd_company' => 'PHPTESTER',
                                       'oadd_street' => 'Escario',
                                       'oadd_zipcode' => '6000',
                                       'oadd_city' => 'Cebu City',
                                       'oadd_state' => 'Cebu',
                                       'oadd_country' => 'Philippines',
                                       'oadd_phone_mobile' => '123456798',
                                       'oadd_phone_landline' => '23456789',
                                       'oadd_creation_date' => date('Y-m-d H:i:s'),
                                   ],
                                   'melis_ecom_order_payment' => [
                                       'opay_payment_type_id' => 1,
                                       'opay_transac_id' => 'PHPTESTERTRANSID',
                                       'opay_transac_return_value' => 1,
                                       'opay_transac_price_paid_confirm' => 100,
                                       'opay_transac_raw_response' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                                       'opay_date_payment' => date('Y-m-d H:i:s'),
                                   ],
                                   'melis_ecom_order_shipping' => [
                                       'oship_tracking_code' => 'PHPTESTSHIPPINGID',
                                       'oship_content' => 'Shipping includes php test',
                                       'oship_date_sent' => date('Y-m-d H:i:s'),
                                   ],
                               ],
                            ],
                            'read' => [
                                [
                                    'column' => 'ord_reference',
                                    'value' => 'Php Test Order',
                                ],
                            ],
                            'delete' => [
                                [
                                    'column' => 'ord_reference',
                                    'value' => 'Php Test Order',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

