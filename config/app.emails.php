<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'emails' => array(
                'VARIANTSLOWSTOCK' => array(
                    'code' => 'VARIANTSLOWSTOCK',
                    'email_name' => 'Low on stock',
                    'layout' => '',
                    'headers' => array(
                        'from' => 'noreply@melistechnology.com',
                        'from_name' => 'Melis Technology',
                        'to' => 'admin@melistechnology.com',
                        'name_to' => 'Melis Admin',
                        'replyTo' => '',
                        'tags' => 'PRODUCT_TEXT, PRODUCT_ID, VARIANT_SKU, VARIANT_ID, STOCKS',
                    ),
                    'contents' => array(
                        'en_EN' => array(
                            'subject' => 'tr_meliscommerce_orders_email_low_stock_subject',
                            'html' => 'tr_meliscommerce_orders_email_low_stock_html',
                            'text' => 'tr_meliscommerce_orders_email_low_stock_text',
                        ),
                        'fr_FR' => array(
                            'subject' => 'tr_meliscommerce_orders_email_low_stock_subject',
                            'html' => 'tr_meliscommerce_orders_email_low_stock_html',
                            'text' => 'tr_meliscommerce_orders_email_low_stock_text',
                        ),
                    ),
                ),
            )
        )
    )
);