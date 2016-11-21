<?php

return array(

    'plugins' => array(
        'diagnostic' => array(
            'MelisCommerce' => array(

                'testCommerceMediaFolders' => array(
                    'controller' => 'Diagnostic',
                    'action' => 'testCommerceMedia',
                    'payload' => array(
                        'label' => 'Melis commerce media folder',
                        'folder' => 'commerce'
                    )
                ),

                'testCommerceMediaProductFolders' => array(
                    'controller' => 'Diagnostic',
                    'action' => 'testCommerceMediaProductFolders',
                    'payload' => array(
                        'label' => 'Melis commerce product media folder',
                        'folder' => 'commerce/product'
                    )
                ),

                'testCommerceMediaCategoryFolders' => array(
                    'controller' => 'Diagnostic',
                    'action' => 'testCommerceMediaCategoryFolders',
                    'payload' => array(
                        'label' => 'Melis commerce category media folder',
                        'folder' => 'commerce/category'
                    )
                ),

                'testCommerceMediaVariantFolders' => array(
                    'controller' => 'Diagnostic',
                    'action' => 'testCommerceMediaVariantFolders',
                    'payload' => array(
                        'label' => 'Melis commerce variant media folder',
                        'folder' => 'commerce/variant'
                    )
                ),

                'testModuleTables' => array(
                    'controller' => 'Diagnostic',
                    'action' => 'testModuleTables',
                    'payload' => array(
                        'label' => 'Melis Commerce Tables Test',
                        'tables' => array(
                            'melis_ecom_attribute', 'melis_ecom_attribute_trans', 'melis_ecom_attribute_type', 'melis_ecom_attribute_value', 'melis_ecom_attribute_value_trans',
                            'melis_ecom_basket_anonymous', 'melis_ecom_basket_persistent', 'melis_ecom_category', 'melis_ecom_category_trans',
                            'melis_ecom_civility', 'melis_ecom_civility_trans', 'melis_ecom_client', 'melis_ecom_client_address', 'melis_ecom_client_address_type',
                            'melis_ecom_client_address_type_trans', 'melis_ecom_client_company', 'melis_ecom_client_person', 'melis_ecom_country', 'melis_ecom_country_category',
                            'melis_ecom_coupon', 'melis_ecom_coupon_client', 'melis_ecom_coupon_order', 'melis_ecom_currency', 'melis_ecom_document', 'melis_ecom_doc_relations', 'melis_ecom_doc_type',
                            'melis_ecom_lang', 'melis_ecom_order', 'melis_ecom_order_address', 'melis_ecom_order_basket', 'melis_ecom_order_messages', 'melis_ecom_order_payment', 'melis_ecom_order_payment_type',
                            'melis_ecom_order_shipping', 'melis_ecom_order_status', 'melis_ecom_order_status_trans', 'melis_ecom_price', 'melis_ecom_product', 'melis_ecom_product_attribute',
                            'melis_ecom_product_category', 'melis_ecom_product_text', 'melis_ecom_product_text_type', 'melis_ecom_seo', 'melis_ecom_variant', 'melis_ecom_variant_attribute_value',
                            'melis_ecom_variant_stock'
                        )
                    ),
                ),
            ),
        
        ),
    ),


);

