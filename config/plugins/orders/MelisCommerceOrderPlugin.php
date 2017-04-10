<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceOrderPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceOrder/show-client-order',
                        // Order id
                        'm_order_id' => null,
                        // image type identifier ex. DEFAULT, SMALL, MEDIUM etc., by default it wont retrieve image
                        'm_basket_image' => array(),
                        // image source to be take ex. product, variant. if set to null it will try to get image
                        // from variant. If no image set on variant it will retrieve image from product
                        'm_image_src' => null,
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);