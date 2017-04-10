<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceAttributesShowPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceProduct/show-attributes',
                        
                        // Id of the attribute assigned to the product
                        'm_p_id' => null,
                        // Id of the last attribute selected
                        'm_action' => array(),
                        // array of attribute and its attribute value selected
                        'm_attrSelection' => array(),
                        // country id
                        'm_p_country' => null,
                        // Flag true if form is submitted
                        'm_is_submit' => false,
                    ),
                    'melis' => array()
                ),
                
            ),
        ),
     ),
);