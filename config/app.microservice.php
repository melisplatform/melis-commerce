<?php

/**
 * Web services (microservices) exposed by MelisCommerce.
 *
 * Dispatched by MelisCore's microservice controller:
 * \MelisCommerce\Service\<Service>::<method>(). Each service is registered under an alias
 * equal to its class short name (e.g. MelisComCategoryService), which the dispatcher requires.
 *
 * Form elements are declared in the SAME order as the service method parameters — the
 * dispatcher maps the Nth element to the Nth positional argument.
 */

/**
 * Helper: a single Text form element.
 */
$msElement = function (string $name, string $dataType, string $placeholder = '') {
    return [
        'spec' => [
            'name' => $name,
            'type' => 'Text',
            'options' => [ 'label' => $name ],
            'attributes' => [
                'id' => $name,
                'value' => '',
                'class' => '',
                'placeholder' => $placeholder,
                'data-type' => $dataType,
            ],
        ],
    ];
};

/**
 * Helper: input_filter entry for a required integer field.
 */
$msIntRequired = function (string $name) {
    return [
        'name' => $name,
        'required' => true,
        'validators' => [
            [
                'name' => 'IsInt',
                'options' => [
                    'message' => [
                        \Laminas\I18n\Validator\IsInt::INVALID => $name . ' must be an integer'
                    ],
                ],
            ],
        ],
        'filters' => [
            ['name' => 'StripTags'],
            ['name' => 'StringTrim'],
        ],
    ];
};

/**
 * Helper: input_filter entry for a required field (no type validator, e.g. a boolean flag).
 */
$msRequired = function (string $name) {
    return [
        'name' => $name,
        'required' => true,
        'filters' => [
            ['name' => 'StripTags'],
            ['name' => 'StringTrim'],
        ],
    ];
};

/**
 * Helper: input_filter entry for an optional field (no validator so an empty value
 * falls back to the method's default argument).
 */
$msOptional = function (string $name) {
    return [
        'name' => $name,
        'required' => false,
        'filters' => [
            ['name' => 'StripTags'],
            ['name' => 'StringTrim'],
        ],
    ];
};

$msAttributes = [
    'name' => 'microservice_form',
    'id'   => 'microservice_form',
    'method' => 'POST',
    'action' => $_SERVER['REQUEST_URI'],
];

return [
    'plugins' => [
        'microservice' => [
            'MelisCommerce' => [
                'MelisComCategoryService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_category',

                    'getCategoryListById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '-1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                            $msElement('start', 'int', '0'),
                            $msElement('limit', 'int', '10'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msOptional('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                            'start'      => $msOptional('start'),
                            'limit'      => $msOptional('limit'),
                        ],
                    ],

                    'getCategoryListByIdRecursive' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                            $msElement('start', 'int', '0'),
                            $msElement('limit', 'int', '10'),
                            $msElement('fatherId', 'int', ''),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                            'start'      => $msOptional('start'),
                            'limit'      => $msOptional('limit'),
                            'fatherId'   => $msOptional('fatherId'),
                        ],
                    ],

                    'getCategoryById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                        ],
                    ],

                    'getCategoryNameById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                        ],
                    ],

                    'getCategoryProductsById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                        ],
                    ],

                    'getCategoryCountriesById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                        ],
                    ],

                    'getCategoryTranslationById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                        ],
                    ],

                    'getAllSubCategoryIdById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                        ],
                    ],

                    'getSubCategoryIdByIdRecursive' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                            $msElement('fatherId', 'int', '0'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                            'fatherId'   => $msOptional('fatherId'),
                            'langId'     => $msOptional('langId'),
                        ],
                    ],

                    'getCategorySeoById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msOptional('categoryId'),
                            'langId'     => $msOptional('langId'),
                        ],
                    ],

                    'getCategoryTreeview' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('fatherId', 'int', ''),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'fatherId'  => $msOptional('fatherId'),
                            'langId'    => $msOptional('langId'),
                            'onlyValid' => $msOptional('onlyValid'),
                        ],
                    ],

                    'getCategoryBreadCrumb' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                        ],
                    ],

                    'getChildrenByLangId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('fatherId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('valid', 'bool', 'true'),
                            $msElement('order', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'fatherId' => $msIntRequired('fatherId'),
                            'langId'   => $msIntRequired('langId'),
                            'valid'    => $msRequired('valid'),
                            'order'    => $msOptional('order'),
                        ],
                    ],
                ],

                'MelisComProductService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_product',

                    // docSubType (trailing array arg) is intentionally NOT declared: with no matching
                    // form element the dispatcher falls back to the method's default (array()).
                    'getProductById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                            $msElement('groupId', 'int', '-1'),
                            $msElement('docType', 'string', ''),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'langId'    => $msOptional('langId'),
                            'countryId' => $msOptional('countryId'),
                            'groupId'   => $msOptional('groupId'),
                            'docType'   => $msOptional('docType'),
                        ],
                    ],

                    'getAssocProducts' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'langId'    => $msOptional('langId'),
                        ],
                    ],

                    'getProductAssociation' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                        ],
                    ],

                    'getProductAttributesById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'langId'    => $msOptional('langId'),
                        ],
                    ],

                    'getProductTextsById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('productTextCode', 'string', ''),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId'       => $msIntRequired('productId'),
                            'productTextCode' => $msOptional('productTextCode'),
                            'langId'          => $msOptional('langId'),
                        ],
                    ],

                    'getProductTextByCode' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('typeCode', 'string', ''),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'typeCode'  => $msRequired('typeCode'),
                            'langId'    => $msOptional('langId'),
                        ],
                    ],

                    'getProductPricesById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                            $msElement('groupId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'countryId' => $msOptional('countryId'),
                            'groupId'   => $msOptional('groupId'),
                        ],
                    ],

                    'getProductCategories' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('status', 'int', ''),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'status'    => $msOptional('status'),
                        ],
                    ],

                    'getProductName' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'langId'    => $msIntRequired('langId'),
                        ],
                    ],

                    'getProductsByCategoryId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                            $msElement('langId', 'int', '1'),
                            $msElement('order', 'string', ''),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                            'langId'     => $msOptional('langId'),
                            'order'      => $msOptional('order'),
                        ],
                    ],

                    'getProductVariants' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'onlyValid' => $msOptional('onlyValid'),
                        ],
                    ],

                    'getProductVariantPriceById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('order', 'string', 'ASC'),
                            $msElement('priceColumn', 'string', 'price_net'),
                        ],
                        'input_filter' => [
                            'productId'   => $msIntRequired('productId'),
                            'order'       => $msOptional('order'),
                            'priceColumn' => $msOptional('priceColumn'),
                        ],
                    ],

                    'getProductTitleAndSeoById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'langId'    => $msIntRequired('langId'),
                        ],
                    ],

                    'getProductBasicDetails' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                            $msElement('groupId', 'int', ''),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'countryId' => $msOptional('countryId'),
                            'groupId'   => $msOptional('groupId'),
                            'langId'    => $msOptional('langId'),
                        ],
                    ],

                    'getMaximumMinimumPrice' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('type', 'string', ''),
                            $msElement('priceColumn', 'string', 'price_net'),
                            $msElement('from', 'string', 'product'),
                        ],
                        'input_filter' => [
                            'type'        => $msOptional('type'),
                            'priceColumn' => $msOptional('priceColumn'),
                            'from'        => $msOptional('from'),
                        ],
                    ],

                    'getProductPageAssociationsByProductId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                        ],
                    ],
                ],

                'MelisComVariantService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_variant',

                    'getVariantListByProductId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                            $msElement('groupId', 'int', '-1'),
                            $msElement('onlyValid', 'bool', 'false'),
                            $msElement('isMain', 'bool', ''),
                            $msElement('start', 'int', '0'),
                            $msElement('limit', 'int', '10'),
                            $msElement('search', 'string', ''),
                            $msElement('order', 'string', 'var_id'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'langId'    => $msOptional('langId'),
                            'countryId' => $msOptional('countryId'),
                            'groupId'   => $msOptional('groupId'),
                            'onlyValid' => $msOptional('onlyValid'),
                            'isMain'    => $msOptional('isMain'),
                            'start'     => $msOptional('start'),
                            'limit'     => $msOptional('limit'),
                            'search'    => $msOptional('search'),
                            'order'     => $msOptional('order'),
                        ],
                    ],

                    // docSubType (trailing array arg) intentionally NOT declared → dispatcher uses default array().
                    'getVariantById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                            $msElement('groupId', 'int', '-1'),
                            $msElement('docType', 'string', ''),
                        ],
                        'input_filter' => [
                            'variantId' => $msIntRequired('variantId'),
                            'langId'    => $msOptional('langId'),
                            'countryId' => $msOptional('countryId'),
                            'groupId'   => $msOptional('groupId'),
                            'docType'   => $msOptional('docType'),
                        ],
                    ],

                    'getVariantBySKU' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantSKU', 'string', ''),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'variantSKU' => $msRequired('variantSKU'),
                            'langId'     => $msOptional('langId'),
                        ],
                    ],

                    'getMainVariantByProductId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                            $msElement('groupId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'langId'    => $msOptional('langId'),
                            'countryId' => $msOptional('countryId'),
                            'groupId'   => $msOptional('groupId'),
                        ],
                    ],

                    'getProductByVariantId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'variantId' => $msIntRequired('variantId'),
                        ],
                    ],

                    'getVariantStocksById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                        ],
                        'input_filter' => [
                            'variantId' => $msIntRequired('variantId'),
                            'countryId' => $msOptional('countryId'),
                        ],
                    ],

                    'getVariantFinalStocks' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantId', 'int', '1'),
                            $msElement('countryId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'variantId' => $msIntRequired('variantId'),
                            'countryId' => $msIntRequired('countryId'),
                        ],
                    ],

                    'getVariantAttributesValuesById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'variantId' => $msIntRequired('variantId'),
                            'langId'    => $msOptional('langId'),
                        ],
                    ],

                    'getVariantPricesById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                            $msElement('groupId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'variantId' => $msIntRequired('variantId'),
                            'countryId' => $msOptional('countryId'),
                            'groupId'   => $msOptional('groupId'),
                        ],
                    ],

                    'getAssocVariantsByProductId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                        ],
                    ],

                    'getVariantCommonAttr' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('attributeId', 'int', '1'),
                            $msElement('attributeValueId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId'        => $msIntRequired('productId'),
                            'attributeId'      => $msIntRequired('attributeId'),
                            'attributeValueId' => $msIntRequired('attributeValueId'),
                        ],
                    ],

                    'getVariantAndSeoById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('variantId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'variantId' => $msIntRequired('variantId'),
                            'langId'    => $msIntRequired('langId'),
                        ],
                    ],
                ],

                'MelisComAttributeService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_attribute',

                    'getAttributes' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('langId', 'int', '1'),
                            $msElement('status', 'bool', ''),
                            $msElement('visible', 'bool', ''),
                            $msElement('searchable', 'bool', ''),
                            $msElement('start', 'int', '0'),
                            $msElement('limit', 'int', '10'),
                            $msElement('order', 'string', ''),
                            $msElement('search', 'string', ''),
                        ],
                        'input_filter' => [
                            'langId'     => $msOptional('langId'),
                            'status'     => $msOptional('status'),
                            'visible'    => $msOptional('visible'),
                            'searchable' => $msOptional('searchable'),
                            'start'      => $msOptional('start'),
                            'limit'      => $msOptional('limit'),
                            'order'      => $msOptional('order'),
                            'search'     => $msOptional('search'),
                        ],
                    ],

                    'getAttributeById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeId' => $msIntRequired('attributeId'),
                            'langId'      => $msOptional('langId'),
                        ],
                    ],

                    'getAttrById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attrId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attrId' => $msIntRequired('attrId'),
                        ],
                    ],

                    'getAttrValByField' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('col', 'string', ''),
                            $msElement('attrId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'col'    => $msRequired('col'),
                            'attrId' => $msIntRequired('attrId'),
                        ],
                    ],

                    'getAttributeTransByAtributeId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attrId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attrId' => $msIntRequired('attrId'),
                            'langId' => $msIntRequired('langId'),
                        ],
                    ],

                    'getAttributeTransById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeTransId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeTransId' => $msIntRequired('attributeTransId'),
                            'langId'           => $msOptional('langId'),
                        ],
                    ],

                    'getAttributeTrans' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeTransId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeTransId' => $msIntRequired('attributeTransId'),
                            'langId'           => $msIntRequired('langId'),
                        ],
                    ],

                    'getAttributeText' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeId' => $msIntRequired('attributeId'),
                            'langId'      => $msIntRequired('langId'),
                        ],
                    ],

                    'getAttributeValuesList' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('start', 'int', '0'),
                            $msElement('limit', 'int', '10'),
                            $msElement('order', 'string', ''),
                            $msElement('search', 'string', ''),
                            $msElement('valCol', 'string', ''),
                        ],
                        'input_filter' => [
                            'attributeId' => $msOptional('attributeId'),
                            'langId'      => $msOptional('langId'),
                            'start'       => $msOptional('start'),
                            'limit'       => $msOptional('limit'),
                            'order'       => $msOptional('order'),
                            'search'      => $msOptional('search'),
                            'valCol'      => $msOptional('valCol'),
                        ],
                    ],

                    'getUsedAttributeValuesByProductId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('status', 'bool', 'false'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'status'    => $msOptional('status'),
                            'langId'    => $msOptional('langId'),
                        ],
                    ],

                    'getUsedAttributeValuesByProduct' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('productId', 'int', '1'),
                            $msElement('attrId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'productId' => $msIntRequired('productId'),
                            'attrId'    => $msIntRequired('attrId'),
                        ],
                    ],

                    'getAttributeValuesById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeValueId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeValueId' => $msIntRequired('attributeValueId'),
                            'langId'           => $msOptional('langId'),
                        ],
                    ],

                    'getAttributeValuesByAttributeId' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeId' => $msIntRequired('attributeId'),
                            'langId'      => $msOptional('langId'),
                        ],
                    ],

                    'getAttributeValueTransById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeValueTransId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeValueTransId' => $msIntRequired('attributeValueTransId'),
                            'langId'                => $msOptional('langId'),
                        ],
                    ],

                    'getAttributeListAndValues' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('attributeId', 'int', ''),
                            $msElement('status', 'bool', 'false'),
                            $msElement('searchable', 'bool', 'false'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'attributeId' => $msOptional('attributeId'),
                            'status'      => $msOptional('status'),
                            'searchable'  => $msOptional('searchable'),
                            'langId'      => $msOptional('langId'),
                        ],
                    ],
                ],

                'MelisComPriceService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_price',

                    // data (trailing typed-array arg) intentionally NOT declared → dispatcher uses default [].
                    'getItemPrice' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('itemId', 'int', '1'),
                            $msElement('countryId', 'int', '1'),
                            $msElement('groupId', 'int', '1'),
                            $msElement('type', 'string', 'variant'),
                        ],
                        'input_filter' => [
                            'itemId'    => $msIntRequired('itemId'),
                            'countryId' => $msIntRequired('countryId'),
                            'groupId'   => $msIntRequired('groupId'),
                            'type'      => $msOptional('type'),
                        ],
                    ],
                ],

                'MelisComProductSearchService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_productsearch',

                    // Trailing array args (fieldsTypeCodes, docTypes) NOT declared → dispatcher uses default array().
                    'getProductByCategory' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('countryId', 'int', ''),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'countryId'  => $msOptional('countryId'),
                        ],
                    ],

                    // Only `search` is exposed: the next positional params (fieldsTypeCodes[], categoryId[]) are
                    // array args, so declaring anything past `search` would map a text field onto an array param.
                    // Everything after `search` therefore uses the method defaults.
                    'searchProductByTextFields' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('search', 'string', 'fashion'),
                        ],
                        'input_filter' => [
                            'search' => $msRequired('search'),
                        ],
                    ],

                    // Same rationale as searchProductByTextFields: only the leading `search` field is exposable.
                    'searchProductFull' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('search', 'string', 'fashion'),
                        ],
                        'input_filter' => [
                            'search' => $msRequired('search'),
                        ],
                    ],
                ],

                'MelisComCurrencyService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_currency',

                    'getCurrencies' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('status', 'bool', ''),
                            $msElement('start', 'int', '0'),
                            $msElement('limit', 'int', '10'),
                            $msElement('order', 'string', ''),
                            $msElement('search', 'string', ''),
                        ],
                        'input_filter' => [
                            'status' => $msOptional('status'),
                            'start'  => $msOptional('start'),
                            'limit'  => $msOptional('limit'),
                            'order'  => $msOptional('order'),
                            'search' => $msOptional('search'),
                        ],
                    ],

                    'getDefaultCurrency' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [],
                    ],

                    'getCountriesUsingCurrency' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('currencyId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'currencyId' => $msIntRequired('currencyId'),
                        ],
                    ],
                ],

                'MelisComCountryService' => [
                    '_description' => 'tr_meliscommerce_ws_desc_country',

                    'getCountries' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [],
                    ],

                    'getCountryById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('countryId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'countryId' => $msIntRequired('countryId'),
                        ],
                    ],

                    'getCountryCurrency' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('countryId', 'int', '1'),
                            $msElement('status', 'bool', ''),
                        ],
                        'input_filter' => [
                            'countryId' => $msIntRequired('countryId'),
                            'status'    => $msOptional('status'),
                        ],
                    ],
                ],
            ],
        ],
    ],
];
