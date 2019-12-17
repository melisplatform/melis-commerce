<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\DashboardPlugins;

use MelisCore\Controller\DashboardPlugins\MelisCoreDashboardTemplatingPlugin;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

class MelisCommerceDashboardPluginSalesRevenue extends MelisCoreDashboardTemplatingPlugin
{

    public function __construct()
    {
        //set plugin. first index of plugin in dashboardplugin config.
        $this->pluginModule = 'meliscommerce';
        parent::__construct();
    }

    public function commerceSalesRevenue()
    {
        /** @var \MelisCore\Service\MelisCoreDashboardPluginsRightsService $dashboardPluginsService */
        $dashboardPluginsService = $this->getServiceLocator()->get('MelisCoreDashboardPluginsService');
        //get the class name to make it as a key to the plugin
        $path = explode('\\', __CLASS__);
        $className = array_pop($path);
        $isAccessable = $dashboardPluginsService->canAccess($className);

        $view = new ViewModel();
        $view->setTemplate('MelisCommerceDashboardPluginSalesRevenue/dashboard/commerce-sales-revenue');
        $view->activeFilter = $this->pluginConfig['datas']['activeFilter'];
        $view->isAccessable = $isAccessable;

        return $view;
    }

    public function getDashboardSalesRevenueData()
    {
        $limit = 10;
        $success = 0;
        $values = array();

        if($this->getController()->getRequest()->isPost()) {
            $chartFor = get_object_vars($this->getController()->getRequest()->getPost());
            $chartFor = ($chartFor['chartFor'] == "") ? $this->pluginConfig['datas']['activeFilter'] : $chartFor['chartFor'];

            //$pluginConfig['activeFilter'] = $chartFor;

            $melisCommerceOrdersService = $this->getServiceLocator()->get('MelisComOrderService');
            // Last Date/value of the Graph will be the Current Date
            if($chartFor == 'hourly') {
                $curdate = date('Y-m-d H:i');
            }
            else if($chartFor == 'monthly'){
                $curdate = date('Y-m');
            }
            else {
                $curdate = date('Y-m-d');
            }

            //loop the initial date to deduct it depending on the type of report
            for ($ctr = $limit ; $ctr > 0 ;$ctr--)
            {
                // Retrieve Sales Revenue Data
                $salesRevenueData = $melisCommerceOrdersService->getSalesRevenueDataByDate($chartFor,$curdate);
                // Checking type of report
                switch ($chartFor) {
                    case 'hourly':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Hour every loop
                        $curdate = date('Y-m-d H:i',strtotime($curdate.' -1 hour'));
                        break;
                    case 'daily':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Day every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 days'));
                        break;
                    case 'weekly':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Week / 7 Days every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 week'));
                        break;
                    case 'monthly':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Month every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 months'));
                        break;
                    default:
                        # code...
                        break;
                }
            }
            $success = 1;
        }
        return new JsonModel(array(
            'date' => date('Y-m-d'),
            'success' => $success,
            'values' => $values
        ));
    }

    public function savePluginConfigToXml($config){
        return "\t".'<activeFilter><![CDATA['.$config['activeFilter'].']]></activeFilter>'."\n";
    }
}