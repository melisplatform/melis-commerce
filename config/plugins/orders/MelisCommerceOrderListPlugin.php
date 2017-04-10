<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceOrderListPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceOrder/show-client-order-list',
                        'm_order_sort' => 'ord_id DESC',
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);