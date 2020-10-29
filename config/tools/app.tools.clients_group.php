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
            'tools' => [
                'meliscommerce_clients_group' => [
                    'table' => [
                        'target' => '#tableClientsGroupsList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComClientsGroup/getClientsGroupList',
                        'dataFunction' => '',
                        'ajaxCallback' => 'clientsGroupTableCallBack();',
                        'filters' => [
                            'left' => [
                                'group-table-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientsGroup',
                                    'action' => 'render-clients-group-table-filter-limit',
                                ],
                            ],
                            'center' => [
                                'group-table-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientsGroup',
                                    'action' => 'render-clients-group-table-filter-search',
                                ],
                            ],
                            'right' => [
                                'group-table-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientsGroup',
                                    'action' => 'render-clients-group-table-filter-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'cgroup_id' => [
                                'text' => 'Id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            
                            ],
                            'cgroup_status' => [
                                'text' => 'Status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,

                            ],
                            'cgroup_name' => [
                                'text' => 'Name',
                                'css' => ['width' => '50%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                        ],
                        'searchables' => ['cgroup_name'],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientsGroup',
                                'action' => 'render-clients-group-content-action-edit',
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientsGroup',
                                'action' => 'render-clients-group-content-action-delete',
                            ],
                        ],
                    ],
                ],
                
            ],
        ],
    ],
];