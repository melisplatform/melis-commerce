<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => '',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/settings.js',
                ),
                'css' => array()
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_settings' => array(
                      'interface' => array(
                          'meliscommerce_settings_leftmenu' => array(
                                'conf' => array(
                                    'id' => 'id_meliscommerce_settings_page',
                                    'melisKey' => 'meliscommerce_settings_page',
                                    'name' => 'tr_meliscommerce_settings',
                                    'icon' => 'fa fa-wrench',
                                ),  
                          ),
                          'meliscommerce_settings_page' => array(
                              'conf' => array(
                                  'id' => 'id_meliscommerce_settings_page',
                                  'melisKey' => 'meliscommerce_settings_page',
                                  'name' => 'tr_meliscommerce_settings',
                              ),
                              'forward' => array(
                                  'module' => 'MelisCommerce',
                                  'controller' => 'MelisComSettings',
                                  'action' => 'render-settings-page',
                                  'jscallback' => '',
                                  'jsdatas' => array()
                              ),
                              'interface' => array(
                                  'meliscommerce_settings_header_container' => array(
                                      'conf' => array(
                                          'id' => 'id_meliscommerce_settings_header_container',
                                          'melisKey' => 'meliscommerce_settings_header_container',
                                          'name' => 'tr_meliscommerce_settings_header_container',
                                      ),
                                      'forward' => array(
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComSettings',
                                          'action' => 'render-settings-header-container',
                                      ),
                                      'interface' => array(
                                          'meliscommerce_settings_header_left_container' => array(
                                              'conf' => array(
                                                  'id' => 'id_meliscommerce_settings_header_left_container',
                                                  'melisKey' => 'meliscommerce_settings_header_left_container',
                                                  'name' => 'tr_meliscommerce_settings_header_left_container',
                                              ),
                                              'forward' => array(
                                                  'module' => 'MelisCommerce',
                                                  'controller' => 'MelisComSettings',
                                                  'action' => 'render-settings-header-left-container',
                                              ),
                                              'interface' => array(
                                                  'meliscommerce_settings_header_title' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_settings_header_title',
                                                          'melisKey' => 'meliscommerce_settings_header_title',
                                                          'name' => 'tr_meliscommerce_settings',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComSettings',
                                                          'action' => 'render-settings-header-title',
                                                      ),
                                                  ),
                                              ),
                                          ),
                                          'meliscommerce_settings_header_right_container' => array(
                                              'conf' => array(
                                                  'id' => 'id_meliscommerce_settings_header_right_container',
                                                  'melisKey' => 'meliscommerce_settings_header_right_container',
                                                  'name' => 'tr_meliscommerce_settings_header_right_container',
                                              ),
                                              'forward' => array(
                                                  'module' => 'MelisCommerce',
                                                  'controller' => 'MelisComSettings',
                                                  'action' => 'render-settings-header-right-container',
                                              ),
                                              'interface' => array(
                                                  'meliscommerce_settings_header_right_container_save' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_settings_header_right_container_save',
                                                          'melisKey' => 'meliscommerce_settings_header_right_container_save',
                                                          'name' => 'tr_meliscommerce_settings_header_right_container_save',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComSettings',
                                                          'action' => 'render-settings-header-save',
                                                      ),
                                                  ),
                                              ),
                                          ),
                                      ),
                                  ),
                                  'meliscommerce_settings_page_content' => array(
                                      'conf' => array(
                                          'id' => 'id_meliscommerce_settings_page_content',
                                          'melisKey' => 'meliscommerce_settings_page_content',
                                          'name' => 'tr_meliscommerce_settings_page_content'
                                      ),
                                      'forward' => array(
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComSettings',
                                          'action' => 'render-settings-page-content',
                                      ),
                                      'interface' => array(
                                          'meliscommerce_settings_page_tabs_main' => array(
                                              'conf' => array(
                                                  'id' => 'id_meliscommerce_settings_page_tabs_main',
                                                  'melisKey' => 'meliscommerce_settings_page_tabs_main',
                                                  'name' => 'tr_meliscommerce_settings_page_tabs_main',
                                                  'icon' => 'glyphicons settings',
                                              ),
                                              'forward' => array(
                                                  'module' => 'MelisCommerce',
                                                  'controller' => 'MelisComSettings',
                                                  'action' => 'render-settings-page-tabs-main',
                                              ),
                                              'interface' => array(
                                                  'meliscommerce_settings_tabs_content_main_header' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_settings_tabs_content_main_header',
                                                          'melisKey' => 'meliscommerce_settings_tabs_content_main_header',
                                                          'name' => 'tr_meliscommerce_settings_tabs_content_main_header',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComSettings',
                                                          'action' => 'render-settings-tabs-content-header',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_settings_tabs_content_main_header_left' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_settings_tabs_content_main_header_left',
                                                                  'melisKey' => 'meliscommerce_settings_tabs_content_main_header_left',
                                                                  'name' => 'tr_meliscommerce_settings_tabs_content_main_header_left',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComSettings',
                                                                  'action' => 'render-settings-tabs-content-header-left',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_settings_tabs_content_main_header_title' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_settings_tabs_content_main_header_title',
                                                                          'melisKey' => 'meliscommerce_settings_tabs_content_main_header_title',
                                                                          'name' => 'tr_meliscommerce_settings_page_tabs_main',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComSettings',
                                                                          'action' => 'render-settings-tabs-content-header-title',
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                          'meliscommerce_settings_tabs_content_main_header_right' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_settings_tabs_content_main_header_right',
                                                                  'melisKey' => 'meliscommerce_settings_tabs_content_main_header_right',
                                                                  'name' => 'tr-meliscommerce_settings_tabs_content_main_header_right',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComSettings',
                                                                  'action' => 'render-settings-tabs-content-header-right',
                                                              ),
                                                              'interface' => array(),
                                                          ),
                                                      ),
                                                  ),
                                                  'meliscommerce_settings_tabs_content_main_details' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_settings_tabs_content_main_details',
                                                          'melisKey' => 'meliscommerce_settings_tabs_content_main_details',
                                                          'name' => 'tr_meliscommerce_settings_tabs_content_main_details',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComSettings',
                                                          'action' => 'render-settings-tabs-content-details',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_settings_tabs_content_main_details_left' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_settings_tabs_content_main_details_left',
                                                                  'melisKey' => 'meliscommerce_settings_tabs_content_main_details_left',
                                                                  'name' => 'tr_meliscommerce_settings_tabs_content_main_details_left',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComSettings',
                                                                  'action' => 'render-settings-tabs-content-details-left',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_settings_tabs_content_details_general' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_settings_tabs_content_details_general',
                                                                            'melisKey' => 'meliscommerce_settings_tabs_content_details_general',
                                                                            'name' => 'tr-meliscommerce_settings_tabs_content_details_general',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComSettings',
                                                                            'action' => 'render-settings-tabs-content-details-general',
                                                                        ),
                                                                  ),    
                                                              ),
                                                          ),
                                                      ),
                                                  ),
                                              ),
                                          ),
                                      ),
                                  ),
                              ),
                          ),
                          'meliscommerce_settings_tabs_content_details_general' => array(
                              'conf' => array(
                                  'id' => 'id_meliscommerce_settings_tabs_content_main_details_left',
                                  'melisKey' => 'meliscommerce_settings_tabs_content_main_details_left',
                                  'name' => 'tr_meliscommerce_settings_tabs_content_main_details_left',
                              ),
                              'forward' => array(
                                  'module' => 'MelisCommerce',
                                  'controller' => 'MelisComSettings',
                                  'action' => 'render-settings-tabs-content-details-general',
                              ),
                          ),
                      ),
                ),
            ),
        ),
    ),
);