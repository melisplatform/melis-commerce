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
            'conf' => [
                'id' => '',
                'name' => 'tr_meliscommerce_documents_Documents',
                'rightsDisplay' => 'none',
                'documents' => [
                    'minUploadSize' => 1,
                    'maxUploadSize' => 10500000,
                ],
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/assets/isotope/isotope.pkgd.min.js',
                    '/MelisCommerce/assets/imagesloaded/imagesloaded.pkgd.min.js',
                    '/MelisCommerce/assets/lightbox/js/lightbox.js',
                    '/MelisCommerce/js/tools/documents.tool.js',
                ],
                'css' => [
                    '/MelisCommerce/assets/lightbox/css/lightbox.min.css',
                ],
            ],
            'datas' => [
            
            ],
            'interface' => [
                'meliscommerce_documents_image_attachments_conf' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_documents_image_attachments',
                        'melisKey' => 'meliscommerce_documents_image_attachments',
                        'name' => 'tr_meliscommerce_documents_image_attachments',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComDocument',
                        'action' => 'render-document-image-plugin',
                    ],
                    'interface' => [
                        'meliscommerce_documents_image_lists' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_documents_image_lists',
                                'melisKey' => 'meliscommerce_documents_image_lists',
                                'name' => 'tr_meliscommerce_documents_image_lists',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComDocument',
                                'action' => 'render-document-image-lists',
//                                 'jscallback' => 'initImageDocuments(];',
                            ],
                        ],
                    ]
                ],
                'meliscommerce_documents_modal_container' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_documents_modal_container',
                        'melisKey' => 'meliscommerce_documents_modal_container',
                        'name' => 'tr_meliscommerce_documents_modal_container'
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComDocument',
                        'action' => 'render-document-generic-modal-container',
                        
                    ],
                    'interface' => [
                        'meliscommerce_documents_modal_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_documents_modal_form',
                                'melisKey' => 'meliscommerce_documents_modal_form',
                                'name' => 'tr_meliscommerce_documents_modal_form'
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComDocument',
                                'action' => 'render-document-generic-modal-form',
                            ],
                        ]
                    ]
                ],
                'meliscommerce_documents_file_attachments_conf' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_documents_file_attachments',
                        'melisKey' => 'meliscommerce_documents_file_attachments',
                        'name' => 'tr_meliscommerce_documents_file_attachments',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComDocument',
                        'action' => 'render-document-file-plugin',
                    ],
                    'interface' => [
                        'meliscommerce_documents_file_attachments_lists' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_documents_file_attachments_lists',
                                'melisKey' => 'meliscommerce_documents_file_attachments_lists',
                                'name' => 'tr_meliscommerce_documents_file_attachments_lists',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComDocument',
                                'action' => 'render-document-file-lists',
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],
];