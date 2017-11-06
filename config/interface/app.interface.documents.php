<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_documents_Documents',
                'rightsDisplay' => 'none',
                'documents' => array(
                    'minUploadSize' => 1,
                    'maxUploadSize' => 10500000,
                ),
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/assets/isotope/isotope.pkgd.min.js',
                    '/MelisCommerce/assets/imagesloaded/imagesloaded.pkgd.min.js',
                    '/MelisCommerce/assets/lightbox/js/lightbox.js',
                    '/MelisCommerce/js/tools/documents.tool.js',
                ),
                'css' => array(
                    '/MelisCommerce/assets/lightbox/css/lightbox.min.css',
                ),
            ),
            'datas' => array(
            
            ),
            'interface' => array(
                'meliscommerce_documents_image_attachments_conf' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_documents_image_attachments',
                        'melisKey' => 'meliscommerce_documents_image_attachments',
                        'name' => 'tr_meliscommerce_documents_image_attachments',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComDocument',
                        'action' => 'render-document-image-plugin',
                    ),
                    'interface' => array(
                        'meliscommerce_documents_image_lists' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_documents_image_lists',
                                'melisKey' => 'meliscommerce_documents_image_lists',
                                'name' => 'tr_meliscommerce_documents_image_lists',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComDocument',
                                'action' => 'render-document-image-lists',
//                                 'jscallback' => 'initImageDocuments();',
                            ),
                        ),
                    )
                ),
                'meliscommerce_documents_modal_container' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_documents_modal_container',
                        'melisKey' => 'meliscommerce_documents_modal_container',
                        'name' => 'tr_meliscommerce_documents_modal_container'
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComDocument',
                        'action' => 'render-document-generic-modal-container',
                        
                    ),
                    'interface' => array(
                        'meliscommerce_documents_modal_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_documents_modal_form',
                                'melisKey' => 'meliscommerce_documents_modal_form',
                                'name' => 'tr_meliscommerce_documents_modal_form'
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComDocument',
                                'action' => 'render-document-generic-modal-form',
                            ),
                        )
                    )
                ),
                'meliscommerce_documents_file_attachments_conf' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_documents_file_attachments',
                        'melisKey' => 'meliscommerce_documents_file_attachments',
                        'name' => 'tr_meliscommerce_documents_file_attachments',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComDocument',
                        'action' => 'render-document-file-plugin',
                    ),
                    'interface' => array(
                        'meliscommerce_documents_file_attachments_lists' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_documents_file_attachments_lists',
                                'melisKey' => 'meliscommerce_documents_file_attachments_lists',
                                'name' => 'tr_meliscommerce_documents_file_attachments_lists',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComDocument',
                                'action' => 'render-document-file-lists',
                            ),
                        ),
                    )
                ),
            ),
        ),
    ),
);