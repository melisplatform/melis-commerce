<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'plugins' => [
        'meliscommerce' => [
            'emails' => [
                'VARIANTSLOWSTOCK' => [
                    'code' => 'VARIANTSLOWSTOCK',
                    'email_name' => 'Low on stock',
                    'layout' => '',
                    'headers' => [
                        'from' => 'noreply@melistechnology.com',
                        'from_name' => 'Melis Technology',
                        'to' => 'admin@melistechnology.com',
                        'name_to' => 'Melis Admin',
                        'replyTo' => '',
                        'tags' => 'PRODUCT_TEXT, PRODUCT_ID, VARIANT_SKU, VARIANT_ID, STOCKS',
                    ],
                    'contents' => [
                        'en_EN' => [
                            'subject' => 'tr_meliscommerce_orders_email_low_stock_subject',
                            'html' => 'tr_meliscommerce_orders_email_low_stock_html',
                            'text' => 'tr_meliscommerce_orders_email_low_stock_text',
                        ],
                        'fr_FR' => [
                            'subject' => 'tr_meliscommerce_orders_email_low_stock_subject',
                            'html' => 'tr_meliscommerce_orders_email_low_stock_html',
                            'text' => 'tr_meliscommerce_orders_email_low_stock_text',
                        ],
                    ],
                ],
            ]
        ]
    ]
];