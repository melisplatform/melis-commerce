<?php

return array(

    'plugins' => array(
        'diagnostic' => array(
            'MelisCommerce' => array(
                // location of your test folder inside the module
                'testFolder' => 'test',
                // moduleTestName is the name of your test folder inside 'testFolder'
                'moduleTestName' => 'MelisCommerceTest',
                // this should be properly setup so we can recreate the factory of the database
                'db' => array(
                    'getMelisEcomAssocVariantTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomAssocVariant',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTable',
                        'db_table_name' => 'melis_ecom_assoc_variant',
                    ),
                    'getMelisEcomAssocVariantTypeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomAssocVariantType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTypeTable',
                        'db_table_name' => 'melis_ecom_assoc_variants_type',
                    ),
                    'getMelisEcomAttributeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomAttribute',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTable',
                        'db_table_name' => 'melis_ecom_attribute',
                    ),
                    'getMelisEcomAttributeTransTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable',
                        'db_table_name' => 'melis_ecom_attribute_trans',
                    ),
                    'getMelisEcomAttributeTypeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable',
                        'db_table_name' => 'melis_ecom_attribute_type',
                    ),
                    'getMelisEcomAttributeValueTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeValue',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTable',
                        'db_table_name' => 'melis_ecom_attribute_value',
                    ),
                    'getMelisEcomAttributeValueTransTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomAttributeValueTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable',
                        'db_table_name' => 'melis_ecom_attribute_value_trans',
                    ),
                    'getMelisEcomBasketAnonymousTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomBasketAnonymous',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable',
                        'db_table_name' => 'melis_ecom_basket_anonymous',
                    ),
                    'getMelisEcomBasketPersistentTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomBasketPersistent',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable',
                        'db_table_name' => 'melis_ecom_basket_persistent',
                    ),
                    'getMelisEcomCategoryTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCategory',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTable',
                        'db_table_name' => 'melis_ecom_category',
                    ),
                    'getMelisEcomCategoryTransTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCategoryTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTransTable',
                        'db_table_name' => 'melis_ecom_category_trans',
                    ),
//                     'getMelisEcomCivilityTable' => array(
//                         'model' => 'MelisCommerce\Model\MelisEcomCivility',
//                         'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCivilityTable',
//                         'db_table_name' => 'melis_ecom_civility',
//                     ),
                    'getMelisEcomCivilityTransTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCivilityTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCivilityTransTable',
                        'db_table_name' => 'melis_ecom_civility_trans',
                    ),
                    'getMelisEcomClientTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomClient',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientTable',
                        'db_table_name' => 'melis_ecom_client',
                    ),
                    'getMelisEcomClientAddressTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomClientAddress',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTable',
                        'db_table_name' => 'melis_ecom_client_address',
                    ),
                    'getMelisEcomClientAddressTypeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomClientAddressType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable',
                        'db_table_name' => 'melis_ecom_client_address_type',
                    ),
                    'getMelisEcomClientAddressTypeTransTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomClientAddressTypeTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable',
                        'db_table_name' => 'melis_ecom_client_address_type',
                    ),
                    'getMelisEcomClientCompanyTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomClientCompany',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientCompanyTable',
                        'db_table_name' => 'melis_ecom_client_company',
                    ),
                    'getMelisEcomClientPersonTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomClientPerson',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomClientPersonTable',
                        'db_table_name' => 'melis_ecom_client_person',
                    ),                    
                    'getMelisEcomCountryCategoryTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCountryCategory',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable',
                        'db_table_name' => 'melis_ecom_country_category',
                    ),
                    'getMelisEcomCountryTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCountry',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCountryTable',
                        'db_table_name' => 'melis_ecom_country',
                    ),
                    'getMelisEcomCouponTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCoupon',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCouponTable',
                        'db_table_name' => 'melis_ecom_coupon',
                    ),
                    'getMelisEcomCouponClientTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCouponClient',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCouponClientTable',
                        'db_table_name' => 'melis_ecom_coupon_client',
                    ),
                    'getMelisEcomCouponOrderTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCouponOrder',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCouponOrderTable',
                        'db_table_name' => 'melis_ecom_coupon_order',
                    ),                    
                    'getMelisEcomCurrencyTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomCurrency',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomCurrencyTable',
                        'db_table_name' => 'melis_ecom_currency',
                    ),
                    'getMelisEcomDocumentTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomDocument',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomDocumentTable',
                        'db_table_name' => 'melis_ecom_document',
                    ),
                    'getMelisEcomDocRelationsTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomDocRelations',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomDocRelationsTable',
                        'db_table_name' => 'melis_ecom_doc_relations',
                    ),
                    'getMelisEcomDocTypeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomDocType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomDocTypeTable',
                        'db_table_name' => 'melis_ecom_doc_type',
                    ),
                    'getMelisEcomLangTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomLang',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomLangTable',
                        'db_table_name' => 'melis_ecom_lang',
                    ),
                    'getMelisEcomOrderTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrder',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderTable',
                        'db_table_name' => 'melis_ecom_order',
                    ),
                    'getMelisEcomOrderAddressTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderAddress',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderAddressTable',
                        'db_table_name' => 'melis_ecom_order_address',
                    ),
                    'getMelisEcomOrderBasketTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderBasket',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderBasketTable',
                        'db_table_name' => 'melis_ecom_order_basket',
                    ),
                    'getMelisEcomOrderMessageTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderMessage',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderMessageTable',
                        'db_table_name' => 'melis_ecom_order_message',
                    ),
                    'getMelisEcomOrderPaymentTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderPayment',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable',
                        'db_table_name' => 'melis_ecom_order_payment',
                    ),
                    'getMelisEcomOrderPaymentTypeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderPaymentType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable',
                        'db_table_name' => 'melis_ecom_order_payment_type',
                    ),
                    'getMelisEcomOrderShippingTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderShipping',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderShippingTable',
                        'db_table_name' => 'melis_ecom_order_shipping',
                    ),
                    'getMelisEcomOrderStatusTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderStatus',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTable',
                        'db_table_name' => 'melis_ecom_order_status',
                    ),
                    'getMelisEcomOrderStatusTransTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomOrderStatusTrans',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable',
                        'db_table_name' => 'melis_ecom_order_status_trans',
                    ),
                    'getMelisEcomPriceTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomPrice',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomPriceTable',
                        'db_table_name' => 'melis_ecom_price',
                    ),
                    'getMelisEcomProductTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomProduct',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductTable',
                        'db_table_name' => 'melis_ecom_product',
                    ),
                    'getMelisEcomProductAttributeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomProductAttribute',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductAttributeTable',
                        'db_table_name' => 'melis_ecom_product_attribute',
                    ),
                    'getMelisEcomProductCategoryTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomProductCategory',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable',
                        'db_table_name' => 'melis_ecom_product_category',
                    ),
                    'getMelisEcomProductTextTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomProductText',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTable',
                        'db_table_name' => 'melis_ecom_product_text',
                    ),
                    'getMelisEcomProductTextTypeTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomProductTextType',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable',
                        'db_table_name' => 'melis_ecom_product_text_type',
                    ),
                    'getMelisEcomSeoTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomSeo',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomSeoTable',
                        'db_table_name' => 'melis_ecom_seo',
                    ),
                    'getMelisEcomVariantTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomVariant',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomVariantTable',
                        'db_table_name' => 'melis_ecom_variant',
                    ),
                    'getMelisEcomProductVariantAttributeValueTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomVariantAttributeValue',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable',
                        'db_table_name' => 'melis_ecom_variant_attribute_value',
                    ),
                    'getMelisEcomVariantStockTable' => array(
                        'model' => 'MelisCommerce\Model\MelisEcomVariantStock',
                        'model_table' => 'MelisCommerce\Model\Tables\MelisEcomVariantStockTable',
                        'db_table_name' => 'melis_ecom_variant_stock',
                    ),
                ),
                // these are the various types of methods that you would like to give payloads for testing
                // you don't have to put all the methods in the test controller,
                // instead, just put the methods that will be needing or requiring the payloads for your test.
                'methods' => array(
                    
                    'testComLanguage' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'elang_locale' => 'tt_TT',
                                    'elang_name' => 'PHP Test',
                                    'elang_status' => 1,
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'elang_locale',
                                    'value' => 'tt_TT',
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'elang_locale',
                                    'value' => 'tt_TT',
                                ),
                            )
                        ),  
                    ),
                    
                    'testComCurrency' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'cur_default' => 0,
                                    'cur_name' => 'PHP TEST',
                                    'cur_code' => 'PHP',
                                    'cur_symbol' => 'P',
                                    'cur_status' => 1,
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'cur_name',
                                    'value' => 'PHP TEST',
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'cur_name',
                                    'value' => 'PHP TEST',
                                ),
                            ),
                        ), 
                    ),
                    
                    'testComCountry' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'ctry_name' => 'PHP Test',
                                    'ctry_currency_id' => '',
                                    'ctry_status' => 1,
                                    'ctry_currency_id' => '-1',
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'ctry_name',
                                    'value' => 'PHP Test',
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'ctry_name',
                                    'value' => 'PHP Test',
                                ),
                            ),
                        ),
                    ),
                    
                    'testComCategories' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'melis_ecom_category' => array(
                                        'cat_reference' => 'PHP Category Ref',
                                        'cat_date_creation' => date('Y-m-d H:i:s'),
                                        'cat_user_id_creation' => 1,
                                        'cat_date_edit' => date('Y-m-d H:i:s'),
                                        'cat_user_id_edit' => 1,
                                    ),
                                    'melis_ecom_category_trans' => array(
                                        'catt_name' => 'PHP Category Test Name',
                                        'catt_description' => 'This category is created using the PHPunit tests',
                                    ),
                                ),
                                array(
                                    'melis_ecom_category' => array(
                                        'cat_reference' => 'PHP Category Ref2',
                                        'cat_date_creation' => date('Y-m-d H:i:s'),
                                        'cat_user_id_creation' => 1,
                                        'cat_date_edit' => date('Y-m-d H:i:s'),
                                        'cat_user_id_edit' => 1,
                                    ),
                                    'melis_ecom_category_trans' => array(
                                        'catt_name' => 'PHP Category Test Name2',
                                        'catt_description' => 'This category is created using the PHPunit tests2',
                                    ),
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref',
                                ),
                                array(
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref2',
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref',
                                ),
                                array(
                                    'column' => 'cat_reference',
                                    'value'  => 'PHP Category Ref2',
                                ),
                            ),
                        ),  
                    ),
                    
                    'testComProducts' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'melis_ecom_product_text_type' => array(
                                        'ptt_code' => 'PHP',
                                        'ptt_name' => 'PHP TEST',
                                        'ptt_field_type' => 1,
                                    ),
                                    'melis_ecom_product' => array(
                                        'prd_reference' => 'PHP product test ref',
                                        'prd_status' => 1,
                                        'prd_date_creation' => date('Y-m-d H:i:s'),
                                        'prd_user_id_creation' => 1,
                                    ),
                                    'melis_ecom_product_text' => array(
                                        'ptxt_field_short' => 'Product Text Test PHP',
                                    ),
                                    'melis_ecom_product_category' => array(
                                        'pcat_order' => 1,
                                    ),
                                    'melis_ecom_price' => array(
                                        'price_net' => 100,
                                        'price_gross' => 80,
                                        'price_vat_percent' => 10,
                                        'price_vat_price' => 11,
                                    ),
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'prd_reference',
                                    'value' => 'PHP product test ref',
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'prd_reference',
                                    'value' => 'PHP product test ref',
                                ),
                            ),
                        ),  
                    ),
                    
                    'testComVariants' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'melis_ecom_variant' => array(
                                        'var_status' => 1,
                                        'var_sku'    => 'PHPUNITTEST123',
                                        'var_main_variant' => 1,
                                        'var_date_creation' => date('Y-m-d H:i:s'),
                                        'var_user_id_creation' => 1,
                                    ),
                                    'melis_ecom_variant_stock' => array(
                                        'stock_quantity' => 100
                                    ),
                                    'melis_ecom_price' => array(
                                        'price_net' => 100,
                                        'price_gross' => 80,
                                        'price_vat_percent' => 10,
                                        'price_vat_price' => 11,
                                    ),
                                ),
                            ),
                            'read' =>  array(
                                array(
                                    'column' => 'var_sku',
                                    'value' => 'PHPUNITTEST123',
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'var_sku',
                                    'value' => 'PHPUNITTEST123',
                                ),
                            ),
                        ),  
                    ),
                    
                    'testComAttributes' => array(
                        'payloads' => array(
                            'create'=> array(
                                array(
                                    'melis_ecom_attribute' => array(
                                        'attr_type_id' => 1,
                                        'attr_status' => 1,
                                        'attr_reference' => 'PHP Attribute Test',
                                        'attr_visible' => 1,
                                        'attr_searchable' => 1,
                                    ),
                                    'melis_ecom_attribute_trans' => array(
                                        'atrans_name' => 'PHP Attribute Test Trans',
                                        'atrans_description' => 'PHP Attribute Test Trans',
                                    ),
                                    'melis_ecom_attribute_value' => array(
                                        'atval_type_id' => 1,
                                        'atval_reference' => 'PHP Attr Val Test',
                                    ),
                                    'melis_ecom_attribute_value_trans' => array(
                                        'avt_v_int' => 143,
                                    ),
                                ),
                            ),
                            'read'=> array(
                                array(
                                    'column' => 'attr_reference',
                                    'value' => 'PHP Attribute Test',
                                ),
                            ),
                            'delete'=> array(
                               array(
                                   'column' => 'attr_reference',
                                   'value' => 'PHP Attribute Test',
                               ),
                            ),
                        ),
                    ),
                    
                    'testComClients' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'melis_ecom_client' => array(
                                        'cli_status' => 1,
                                        'cli_date_creation' => date('Y-m-d H:i:s'),
                                    ),
                                    'melis_ecom_client_person' => array(
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
                                    ),
                                    'melis_ecom_client_company' => array(
                                        'ccomp_name' => 'PHPTest company',
                                        'ccomp_number_id' => 14344,
                                        'ccomp_vat_number' => 12345,
                                        'ccomp_date_creation' => date('Y-m-d H:i:s'),
                                    ),
                                    'melis_ecom_client_address' => array(
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
                                    ),
                                ),
                            ),  
                            'read' => array(
                                array(
                                    'column' => 'cper_email',
                                    'value' => 'phptest12345@mail.com',
                                ),
                            ),  
                            'delete' => array(
                                array(
                                    'column' => 'cper_email',
                                    'value' => 'phptest12345@mail.com',
                                ),
                            ),  
                        ),  
                    ),
                    
                    'testComOrders' => array(
                        'payloads' => array(
                            'create' => array(
                               array(
                                   'order_product_text' => array(
                                       'column' => 'ptxt_field_short', 
                                       'value'   => 'Product Text Test PHP',
                                   ),
                                   'order_product' => array(
                                       'column' => 'prd_reference',
                                       'value' => 'PHP product test ref',
                                   ),
                                   'order_variant' => array(
                                       'column' => 'var_sku',
                                       'value' => 'PHPUNITTEST123',
                                   ),
                                   'order_category' => array(
                                       'column' => 'catt_name',
                                       'value' => 'PHP Category Test Name',
                                    ),
                                   'melis_ecom_order' => array(
                                       'ord_status' => 1,
                                       'ord_reference' => 'Php Test Order',
                                       'ord_date_creation' => date('Y-m-d H:i:s'),
                                       'ord_billing_address' => -1,
                                       'ord_delivery_address' => -1,
                                   ),
                                   'melis_ecom_order_basket' => array(
                                       'obas_quantity' => 1,
                                       'obas_sent' => 0,
                                   ),
                                   
                                   'melis_ecom_order_address' => array(
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
                                   ),
                                   'melis_ecom_order_payment' => array(
                                       'opay_payment_type_id' => 1,
                                       'opay_transac_id' => 'PHPTESTERTRANSID',
                                       'opay_transac_return_value' => 1,
                                       'opay_transac_price_paid_confirm' => 100,
                                       'opay_transac_raw_response' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                                       'opay_date_payment' => date('Y-m-d H:i:s'),
                                   ),
                                   'melis_ecom_order_shipping' => array(
                                       'oship_tracking_code' => 'PHPTESTSHIPPINGID',
                                       'oship_content' => 'Shipping includes php test',
                                       'oship_date_sent' => date('Y-m-d H:i:s'),
                                   ),
                               ),
                            ),  
                            'read' => array(
                                array(
                                    'column' => 'ord_reference',
                                    'value' => 'Php Test Order',
                                ),
                            ),  
                            'delete' => array(
                                array(
                                    'column' => 'ord_reference',
                                    'value' => 'Php Test Order',
                                ),
                            ),  
                        ),  
                    ),
                ),
            ),
        ),
    ),


);

