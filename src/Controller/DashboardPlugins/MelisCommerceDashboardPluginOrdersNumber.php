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

class MelisCommerceDashboardPluginOrdersNumber extends MelisCoreDashboardTemplatingPlugin
{

    public function __construct()
    {
        //set plugin. first index of plugin in dashboardplugin config.
        $this->pluginModule = 'meliscommerce';
        parent::__construct();
    }
    /*
     *Gets data for the plugin.
     *for the table
     * returns view for the plugin
     */
    public function commerceOrders()
    {
        //get service
        $melisOrdersService = $this->getServiceLocator()->get('MelisComOrderService');

        //use any tool to get langId
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_order_list');
        $langId = $melisTool->getCurrentLocaleID();

        //get language locale
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];

        //get order list with limit = 5 and on desc order
        $orderDatas = $melisOrdersService->getOrderList(null,null,null,null,null,null,null,0,5,'ord_id DESC',null,null,null);

        $numProducts = 0;
        $totalPrice = 0;

        //loop to every order
        foreach($orderDatas as $orderData) {
            //get status trans text
            $statusTrans = $melisOrdersService->getOrderStatusByOrderId($orderData->getId());
            foreach ($statusTrans as $trans) {
                if ($trans->ostt_lang_id == $langId) {
                    $orderStatus = $trans;
                }
            }

            //get total price of an order
            foreach($orderData->getPayment() as $payment){
                $totalPrice = $payment->opay_price_total;
            }

            //get total quantity of an order
            foreach($orderData->getBasket() as $basket) {
                $numProducts += $basket->obas_quantity;
            }
            // insert order status to order object
            $orderData->getOrder()->status_trans = $orderStatus->ostt_status_name;
            //insert order total price to order object
            $orderData->getOrder()->total_price = number_format($totalPrice, 2);
            // insert number of products to order object
            $orderData->getOrder()->numProducts = $numProducts;

            //set back to 0 for the other order
            $numProducts = 0;
            $totalPrice = 0;
        }


        $view = new ViewModel();
        //set view for the template
        $view->setTemplate('MelisCommerceDashboardPluginOrdersNumber/dashboard/commerce-orders');
        $view->orderDatas = $orderDatas;
        return $view;
    }
    /*
     * Returns Json datas for the commerce orders graph on dashboard
     */
    public function getDashboardOrdersData()
    {
        // Graph Range X-Axis Limit
        $limit = 10;
        $success = 0;
        $values = array();

        if($this->getController()->getRequest()->isPost()) {

            $chartFor = get_object_vars($this->getController()->getRequest()->getPost());
            $chartFor = isset($chartFor['chartFor']) ? $chartFor['chartFor'] : 'monthly';

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
                // Retreve Prospects Values from database
                $nb = $melisCommerceOrdersService->getOrdersDataByDate($chartFor,$curdate);

                // Checking type of report
                switch ($chartFor) {
                    case 'hourly':
                        $values[] = array($curdate, $nb);
                        // Deduct 1 Hour every loop
                        $curdate = date('Y-m-d H:i',strtotime($curdate.' -1 hour'));
                        break;
                    case 'daily':
                        $values[] = array($curdate, $nb);
                        // Deduct 1 Day every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 days'));
                        break;
                    case 'weekly':
                        $values[] = array($curdate, $nb);
                        // Deduct 1 Week / 7 Days every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 week'));
                        break;
                    case 'monthly':
                        $values[] = array($curdate, $nb);
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
            'values' => $values,
        ));
    }
}