<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutSummaryPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-summary',
                        // country id
                        'm_country_id' => 1,
                        // site id used for checkout session
                        'm_site_id' => 1,
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);