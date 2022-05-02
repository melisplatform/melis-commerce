<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener\GDPR;

use Laminas\EventManager\EventManagerInterface;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceGdprUserInfoListener extends MelisGeneralListener
{
    protected $sl;

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'melis_core_gdpr_user_info_event',
            function($e) {
                $parameters = $e->getParams();
                $moduleName = $this->getModuleName();
                $this->sl = $e->getTarget()->getServiceManager();
                $dataConfig = $this->getConfig($moduleName);
                $tableColumns = array_keys($dataConfig['values']['columns']);
                $specificSearch = isset($parameters['search']['specific-search']) ? true : false;
                $arrayDatas = $this->getService('MelisEcomClientPersonTable')->getDataForGdpr($parameters['search'], $specificSearch)->toArray();

                //module should stay silent if no data mataches
                if (!empty($arrayDatas)) {
                    $dataConfig['values']['datas'] = $this->structureDatasArray($arrayDatas, $tableColumns, $dataConfig);
                    //send data back
                    $parameters['results'][$moduleName] = $dataConfig;
                }
            }
        );
    }

    /**
     * This will get the module name of the class
     * @param Class
     * @return String = module name
     */
    private function getModuleName()
    {
        $controllerClass = get_class($this);
        $moduleName = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        return $moduleName;
    }

    /**
     * Returns structured data array
     * @param Array = params
     * @param Array = columns
     */
    private function structureDatasArray($arrayDatas = [], $tableColumns = [])
    {
        foreach ($arrayDatas as $arrayData) {
            $datasArray[$arrayData['cper_id']] = [];

            foreach($tableColumns as $columnKey => $columnValue) {
                foreach ($arrayData as $dataKey => $dataValue) {
                    if ($columnValue == $dataKey) {
                        $datasArray[$arrayData['cper_id']] = $datasArray[$arrayData['cper_id']] + [$dataKey => $dataValue];
                        break;
                    }
                }
            }
        }

        return $datasArray;
    }

    /**
     * Returns Service
     * @param $service
     * @return mixed
     */
    private function getService($service)
    {
        return $this->sl->get($service);
    }

    /**
     * Returns gdpr config
     * @param $moduleName
     * @return mixed
     */
    private function getConfig($moduleName)
    {
        $config = $this->getService('config');
        return $config['plugins'][$moduleName]['gdpr']['getUserInfo'];
    }
}