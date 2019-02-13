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

class MelisCommerceDashboardPluginOrderMessages extends MelisCoreDashboardTemplatingPlugin
{

    public function __construct()
    {
        //set plugin. first index of plugin in dashboardplugin config
        $this->pluginModule = 'meliscommerce';
        parent::__construct();
    }

    /**
     * Returns view for the Order Messages Dashboard Plugin
     * @return ViewModel
     */
    public function orderMessages()
    {
        /** @var \MelisCore\Service\MelisCoreDashboardPluginsRightsService $dashboardPluginsService */
        $dashboardPluginsService = $this->getServiceLocator()->get('MelisCoreDashboardPluginsService');
        //get the class name to make it as a key to the plugin
        $path = explode('\\', __CLASS__);
        $className = array_pop($path);
        $isAccessable = $dashboardPluginsService->canAccess($className);

        $view = new ViewModel();
        $view->setTemplate('MelisCommerceDashboardPluginOrderMessages/commerce-orders-messages');
        $view->isAccessable = $isAccessable;

        return $view;
    }

    /**
     * Gets all the messages from the client
     * @return JsonModel
     */
    public function getMessages()
    {
        $success = 0;
        $messageList = array();
        $unansweredMessages = 0;
        $request = $this->getController()->getRequest();

        if ($request->isPost()) {
            $filter = $this->getFilter($request);
            $orders = $this->getOrders();
            $noReply = true;

            foreach ($orders as $order) {
                $messages = $order->getMessages();

                if (count($messages) > 0) {

                    foreach ($messages as $message) {
                        if ($message->omsg_user_id != "") {
                            $noReply = false;
                        }

                        // get client name and reference number
                        $message->clientFirstName = $order->getPerson()->cper_firstname;
                        $message->clientLastName = $order->getPerson()->cper_name;
                        $message->reference = $order->getOrder()->ord_reference;
                        $message->orderDate = $this->formatDateByLangLocale($order->getOrder()->ord_date_creation);
                        $message->totalOrderAmount = number_format($this->getTotalOrderAmount($order), 2);
                    }

                    if ($filter == 'all') {
                        if (count($messages) > 0) {
                            foreach ($messages as $message) {
                                // If no BO user has replied to the order
                                $message->noReply = $noReply;

                                if ($noReply) {
                                    $unansweredMessages++;
                                }

                                if ($message->omsg_user_id == "") {
                                    array_push($messageList, $message);
                                }
                            }
                        }
                    } else if ($filter == 'unseen') {
                        if ($noReply) {
                            if (count($messages) > 0) {
                                foreach ($messages as $message) {
                                    //if NO BO user has replied to the order
                                    $message->noReply = $noReply;

                                    if ($noReply) {
                                        $unansweredMessages++;
                                    }

                                    if ($message->omsg_user_id == "") {
                                        array_push($messageList, $message);
                                    }
                                }
                            }
                        }
                    } else {

                    }

                    //sort all messages by date_creation in descending order to show the latest message on the top
                    array_multisort(array_column($messageList, "omsg_date_creation"), SORT_DESC, $messageList);
                    // we set the $noReply variable to true again for the next order
                    $noReply = true;
                }
            }
        }

        return new JsonModel(array(
            'success' => $success,
            'messages' => $messageList,
            'unansweredMessages' => $unansweredMessages,
        ));
    }

    /**
     * @return mixed
     */
    public function getLangLocale()
    {
        $container = new Container('meliscore');
        $langLocale = $container['melis-lang-locale'];

        return $langLocale;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        $melisOrdersService = $this->getServiceLocator()->get('MelisComOrderService');
        $orders = $melisOrdersService->getOrderList(null, null, null, null, null, null, null, null, null, null, null, null, null);

        return $orders;
    }

    /**
     * @param $date
     * @return string
     */
    public function formatDateByLangLocale($date)
    {
        $melisTranslation = $this->getServiceLocator()->get('MelisCoreTranslation');
        $langLocale = $this->getLangLocale();
        $formattedDate = strftime($melisTranslation->getDateFormatByLocate($langLocale), strtotime($date));

        return $formattedDate;
    }

    public function getFilter($request)
    {
        $filter = get_object_vars($request->getPost());

        if (isset($filter['filter'])) {
            return $filter['filter'];
        } else {
            return 'all';
        }
    }

    public function getTotalOrderAmount($order)
    {
        $totalOrderAmount = 0;

        foreach ($order->getPayment() as $payment) {
            $totalOrderAmount = +$payment->opay_price_total;
        }

        return $totalOrderAmount;
    }
}