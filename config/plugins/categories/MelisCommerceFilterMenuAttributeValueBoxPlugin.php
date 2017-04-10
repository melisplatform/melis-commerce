<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceFilterMenuAttributeValueBoxPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-attribute-filter',
                        // the ID of the attribute to be fetch
                        'attribute_id' => null,
                        // filtering
                        // array of attribute values
                        'm_box_filter_attribute_values_ids_selected' => array(),
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);