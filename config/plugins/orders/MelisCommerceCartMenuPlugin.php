<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCartMenuPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCartMenuPlugin/show-cart-menu',
                        
                        // image type use for displaying products
                        'image_type' => 'DEFAULT',
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);