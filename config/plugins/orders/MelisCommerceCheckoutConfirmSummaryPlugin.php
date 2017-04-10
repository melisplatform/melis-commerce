<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutConfirmSummaryPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-confirm-summary',
                        // Site id
                        'm_site_id' => 1,
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);