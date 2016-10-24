<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_clients_Clients',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/client.tool.js',
                ),
                'css' => array(
                    '/MelisCommerce/css/clients.css',
                )
            ),
            'datas' => array(
                'default' => array(
                    'accounts' => array(
                        'hash_method' => 'sha256',
                        'salt' => 'salt_#{3xamPle;',
                    ),
                )
            ),
            'interface' => array(
                'meliscommerce_clients_list' => array(
                    'interface' => array(
                        'meliscommerce_clients_list_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_clients_list_page',
                                'melisKey' => 'meliscommerce_clients_list_page',
                                'name' => 'tr_meliscommerce_clients_Clients',
                                'icon' => 'fa fa-users',
                            ),
                        ),
                        'meliscommerce_clients_list_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_clients_list_page',
                                'melisKey' => 'meliscommerce_clients_list_page',
                                'name' => 'tr_meliscommerce_clients_list_page'
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-page',
                            ),
                            'interface' => array(
                                'meliscommerce_clients_list_header' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_clients_list_header',
                                        'melisKey' => 'meliscommerce_clients_list_header',
                                        'name' => 'tr_meliscommerce_clients_list_header'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClientList',
                                        'action' => 'render-client-list-header',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_clients_list_add_client' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_clients_list_add_client',
                                                'melisKey' => 'meliscommerce_clients_list_add_client',
                                                'name' => 'tr_meliscommerce_clients_list_add_client'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientList',
                                                'action' => 'render-client-list-add-client',
                                            )
                                        )
                                    )
                                ),
                                'meliscommerce_clients_list_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_clients_list_content',
                                        'melisKey' => 'meliscommerce_clients_list_content',
                                        'name' => 'tr_meliscommerce_clients_list_content'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClientList',
                                        'action' => 'render-client-list-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_clients_list_table' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_clients_list_table',
                                                'melisKey' => 'meliscommerce_clients_list_table',
                                                'name' => 'tr_meliscommerce_clients_list_table'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientList',
                                                'action' => 'render-client-list-table',
                                            ),
                                        )
                                    )
                                )
                            )
                        ),
                    )
                ),
                'meliscommerce_client' => array(
                    'interface' => array(
                        'meliscommerce_client_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_client_page',
                                'melisKey' => 'meliscommerce_client_page',
                                'name' => 'tr_meliscommerce_client_page'
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-page',
                            ),
                            'interface' => array(
                                'meliscommerce_client_page_header' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_client_page_header',
                                        'melisKey' => 'meliscommerce_client_page_header',
                                        'name' => 'tr_meliscommerce_client_page_header'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClient',
                                        'action' => 'render-client-page-header',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_client_save' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_client_save',
                                                'melisKey' => 'meliscommerce_client_save',
                                                'name' => 'tr_meliscommerce_client_save'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-save',
                                            )
                                        ),
                                    )
                                ),
                                'meliscommerce_client_page_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_client_page_content',
                                        'melisKey' => 'meliscommerce_client_page_content',
                                        'name' => 'tr_meliscommerce_client_page_content'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClient',
                                        'action' => 'render-client-page-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_client_page_tab_main' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_client_page_tab_main',
                                                'melisKey' => 'meliscommerce_client_page_tab_main',
                                                'name' => 'tr_meliscommerce_client_page_tab_main',
                                                'icon' => 'glyphicons tag'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-main',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_client_page_tab_main_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_client_page_tab_main_header',
                                                        'melisKey' => 'meliscommerce_client_page_tab_main_header',
                                                        'name' => 'tr_meliscommerce_client_page_tab_main_header',
                                                        'icon' => 'glyphicons tag'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-main-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_client_status' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_client_status',
                                                                'melisKey' => 'meliscommerce_client_status',
                                                                'name' => 'tr_meliscommerce_client_status',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-status',
                                                            ),
                                                        )
                                                    )
                                                ),
                                                'meliscommerce_client_page_tab_main_content' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_client_page_tab_main_content',
                                                        'melisKey' => 'meliscommerce_client_page_tab_main_content',
                                                        'name' => 'tr_meliscommerce_client_page_tab_main_content',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-main-content',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_client_page_tab_main_content_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_client_page_tab_main_content_left',
                                                                'melisKey' => 'meliscommerce_client_page_tab_main_content_left',
                                                                'name' => 'tr_meliscommerce_client_page_tab_main_content_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-page-tab-main-content-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_client_main_form' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_client_main_form',
                                                                        'melisKey' => 'meliscommerce_client_main_form',
                                                                        'name' => 'tr_meliscommerce_client_main_form',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComClient',
                                                                        'action' => 'render-client-main-form',
                                                                    ),
                                                                ),
                                                            )
                                                        ),
                                                        'meliscommerce_client_page_tab_main_content_right' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_client_page_tab_main_content_right',
                                                                'melisKey' => 'meliscommerce_client_page_tab_main_content_right',
                                                                'name' => 'tr_meliscommerce_client_page_tab_main_content_right',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-page-tab-main-content-right',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_client_page_main_contact' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_client_page_main_contact',
                                                                        'melisKey' => 'meliscommerce_client_page_main_contact',
                                                                        'name' => 'tr_meliscommerce_client_page_main_contact',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComClient',
                                                                        'action' => 'render-client-main-contact',
                                                                    ),
                                                                )
                                                            )
                                                        ),
                                                    )
                                                ),
                                            )
                                        ),
                                        'meliscommerce_client_page_tab_contact' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_client_page_tab_contact',
                                                'melisKey' => 'meliscommerce_client_page_tab_contact',
                                                'name' => 'tr_meliscommerce_client_page_tab_contact',
                                                'icon' => 'glyphicons earphone'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-contact',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_client_page_tab_contact_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_client_page_tab_contact_header',
                                                        'melisKey' => 'meliscommerce_client_page_tab_contact_header',
                                                        'name' => 'tr_meliscommerce_client_page_tab_contact_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-contact-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_client_add_contact' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_client_add_contact',
                                                                'melisKey' => 'meliscommerce_client_add_contact',
                                                                'name' => 'tr_meliscommerce_client_add_contact',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-add-contact',
                                                            ),
                                                        )
                                                    )
                                                ),
                                                'meliscommerce_client_page_tab_contact_content' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_client_page_tab_contact_content',
                                                        'melisKey' => 'meliscommerce_client_page_tab_contact_content',
                                                        'name' => 'tr_meliscommerce_client_page_tab_contact_content',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-contact-content',
                                                    ),
                                                ),
                                            )
                                        ),
                                        'meliscommerce_client_page_tab_company' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_client_page_tab_company',
                                                'melisKey' => 'meliscommerce_client_page_tab_company',
                                                'name' => 'tr_meliscommerce_client_page_tab_company',
                                                'icon' => 'glyphicons building'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-company',
                                            ),
                                        ),
                                        'meliscommerce_client_page_tab_address' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_client_page_tab_address',
                                                'melisKey' => 'meliscommerce_client_page_tab_address',
                                                'name' => 'tr_meliscommerce_client_page_tab_address',
                                                'icon' => 'glyphicons google_maps'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-address',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_client_page_tab_address_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_client_page_tab_address_header',
                                                        'melisKey' => 'meliscommerce_client_page_tab_address_header',
                                                        'name' => 'tr_meliscommerce_client_page_tab_address_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-address-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_client_add_address' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_client_add_address',
                                                                'melisKey' => 'meliscommerce_client_add_address',
                                                                'name' => 'tr_meliscommerce_client_add_address',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-add-address',
                                                            ),
                                                        ),
                                                    )
                                                ),
                                                'meliscommerce_client_page_tab_address_content' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_client_page_tab_address_content',
                                                        'melisKey' => 'meliscommerce_client_page_tab_address_content',
                                                        'name' => 'tr_meliscommerce_client_page_tab_address_content',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-address-content',
                                                    ),
                                                )
                                            )
                                        ),
                                        'meliscommerce_client_page_tab_orders' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_client_page_tab_orders',
                                                'melisKey' => 'meliscommerce_client_page_tab_orders',
                                                'name' => 'tr_meliscommerce_client_page_tab_orders',
                                                'icon' => 'glyphicons notes_2'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-orders',
                                            ),
                                        ),
                                    )
                                )
                            )
                        )
                    )
                ), 
                'meliscommerce_client_modal' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_client_modal',
                        'melisKey' => 'meliscommerce_client_modal',
                        'name' => 'tr_meliscommerce_client_modal',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComClient',
                        'action' => 'render-client-modal',
                    ),
                    'interface' => array(
                        'meliscommerce_client_modal_contact_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_client_modal_contact_form',
                                'melisKey' => 'meliscommerce_client_modal_contact_form',
                                'name' => 'tr_meliscommerce_client_modal_contact_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-modal-contact-form',
                            ),
                        ),
                        'meliscommerce_client_modal_contact_address_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_client_modal_contact_address_form',
                                'melisKey' => 'meliscommerce_client_modal_contact_address_form',
                                'name' => 'tr_meliscommerce_client_modal_contact_address_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-modal-contact-address-form',
                            ),
                        ),
                        'meliscommerce_client_modal_address_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_client_modal_address_form',
                                'melisKey' => 'meliscommerce_client_modal_address_form',
                                'name' => 'tr_meliscommerce_client_modal_address_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-modal-address-form',
                            ),
                        )
                    )
                )
            )
        )
    )
);