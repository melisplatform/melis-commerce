<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProductsRelatedPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceProduct/show-related-products',
                
                        // Optional Filtering
                        // product id
                        'm_p_id' => NULl,
                    ),
                    'melis' => array()
                ),
            ),
        ),
     ),
);