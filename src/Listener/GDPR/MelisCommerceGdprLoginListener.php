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

class MelisCommerceGdprLoginListener extends MelisGeneralListener
{
    protected $sl;

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_authentication_login_end',
            function($e) {
                $params = $e->getParams();
                
                if (empty($params['results']))
                    return;

                if (!$params['results']['success'])
                    return;
                
                $sm = $e->getTarget()->getServiceManager();
                $ecomAuthSrv = $sm->get('MelisComAuthenticationService');
                if ($ecomAuthSrv->hasIdentity()) {
                    $personId = $ecomAuthSrv->getPersonId();
                    $sm->get('MelisEcomClientPersonTable')->save(['cper_date_edit' => date('Y-m-d')], $personId);
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