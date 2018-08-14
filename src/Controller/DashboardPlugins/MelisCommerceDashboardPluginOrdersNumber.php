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
        $this->pluginModule = 'meliscommerce';
        parent::__construct();
    }

    /**
     * Get the latest 5 orders
     * @return ViewModel
     */
    public function commerceOrders()
    {
        $melisOrdersService = $this->getServiceLocator()->get('MelisComOrderService');
        $langId = $this->getCurrentLocaleId('meliscommerce', 'meliscommerce_order_list');
        $melisTranslation = $this->getServiceLocator()->get('MelisCoreTranslation');
        //get language locale
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];

        //$newDate = date('d/m/Y H:i:s',strtotime($tmpdate));
//        setlocale(LC_TIME, 'de_DE.UTF8');
        //var_dump(strftime( "%h %y",strtotime("2011-12-22")));
//        exit;

        //get latest 5 orders
        $orders = $melisOrdersService->getOrderList(null,null,null,null,null,null,null,0,5,'ord_id DESC',null,null,null);

        foreach($orders as $order) {
            $numProducts = 0;
            $totalPrice = 0;

            //get status trans text
            $statusTrans = $melisOrdersService->getOrderStatusByOrderId($order->getId());
            foreach ($statusTrans as $trans) {
                if ($trans->ostt_lang_id == $langId) {
                    $orderStatus = $trans;
                } else {
                    $orderStatus = "";
                }
            }

            //get total price of an order
            foreach ($order->getPayment() as $payment) {
                $totalPrice = $payment->opay_price_total;
            }

            //get total quantity of an order
            foreach ($order->getBasket() as $basket) {
                $numProducts += $basket->obas_quantity;
            }

            // insert order status to order object
            $order->getOrder()->status_trans = $orderStatus->ostt_status_name;
            //insert order total price to order object
            $order->getOrder()->total_price = number_format($totalPrice, 2);
            // insert number of products to order object
            $order->getOrder()->numProducts = $numProducts;
            // change date format
            $order->getOrder()->ord_date_creation = strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($order->getOrder()->ord_date_creation));
        }
        $view = new ViewModel();

        $view->setTemplate('MelisCommerceDashboardPluginOrdersNumber/dashboard/commerce-orders');
        $view->orderDatas = $orders;

        return $view;
    }

    /**
     * Returns Json datas for the commerce orders graph on dashboard
     * @return JsonModel
     */
    public function getDashboardOrdersData()
    {
        // Graph Range X-Axis Limit
        $limit = 10;
        $success = 0;
        $values = array();

        if ($this->getController()->getRequest()->isPost()) {

            $chartFor = get_object_vars($this->getController()->getRequest()->getPost());
            $chartFor = isset($chartFor['chartFor']) ? $chartFor['chartFor'] : 'monthly';

            $melisCommerceOrdersService = $this->getServiceLocator()->get('MelisComOrderService');

            // Last Date/value of the Graph will be the Current Date
            if ($chartFor == 'hourly') {
                $curdate = date('Y-m-d H:i');
            } else if ($chartFor == 'monthly') {
                $curdate = date('Y-m');
            } else {
                $curdate = date('Y-m-d');
            }

            //loop the initial date to deduct it depending on the type of report
            for ($ctr = $limit ; $ctr > 0 ;$ctr--) {
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

        return new JsonModel([
            'date'    => date('Y-m-d'),
            'success' => $success,
            'values'  => $values,
        ]);
    }

    /**
     * Gets the tool
     * @param string $pluginKey
     * @param string $toolKey
     * @return array|object
     */
    private function getTool($pluginKey, $toolKey)
    {
        $tool = $this->getServiceLocator()->get('MelisCoreTool');
        $tool->setMelisToolKey($pluginKey, $toolKey);
        return $tool;
    }

    /**
     * Gets the lang ID
     * @param string $pluginKey
     * @param string $toolKey
     * @return mixed
     */
    private function getCurrentLocaleId($pluginKey, $toolKey)
    {
        return $this->getTool($pluginKey, $toolKey)->getCurrentLocaleID();
    }
}