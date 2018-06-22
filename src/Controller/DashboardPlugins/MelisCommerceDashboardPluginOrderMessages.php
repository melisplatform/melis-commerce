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
    /*
     * Returns view for the Order Messages Dashboard Plugin
     */
    public function orderMessages()
    {
        $view = new ViewModel();
        $view->setTemplate('MelisCommerceDashboardPluginOrderMessages/commerce-orders-messages');
        return $view;
    }
    /*
     * This controller gets all the messages from the client
     * Returns JsonModel of messages or unanswered messages of the client
     */
    public function getMessages()
    {
        $success = 0;
        $messageList = array();
        $unansweredMessages = 0;
        //check if post
        if($this->getController()->getRequest()->isPost()) {
            //get filter [data passed from the ajax]
            $filter = get_object_vars($this->getController()->getRequest()->getPost());
            $filter = isset($filter['filter']) ? $filter['filter'] : 'all';
            //get Melis Commerce Order Service
            $melisOrdersService = $this->getServiceLocator()->get('MelisComOrderService');
            /*
             * Initialize $noReply variable.
             * This variable is used to check if the messages of the order
             * is already seen by user of BO or the user of the BO has already replied
             * to the order messages
             */
            $noReply = true;
            //get all orders
            $orderDatas = $melisOrdersService->getOrderList(null, null, null, null, null, null, null, null, null, null, null, null, null);

            foreach ($orderDatas as $order) {
                //get all messages of order
                $messages = $melisOrdersService->getOrderMessageByOrderId($order->getId());
                //loop every message
                foreach ($messages as $message){
                    /*
                     * If message osmg_user_id(BO user id who is talking) is NOT NULL
                     * which means that the user who is talking is from the BO/Admin
                     * we set the $noReply variable to false so that with even one message
                     * we could detect if the admin had replied to the customer with their order
                     */
                    if($message->omsg_user_id != ""){
                        $noReply = false;
                    }
                    //add the Customer Name for every message
                    $message->clientFirstName = $order->getPerson()->cper_firstname;
                    $message->clientLastName = $order->getPerson()->cper_name;
                    //add order reference number because we will be needing it for the tabOpen helper
                    //to name the zone
                    $message->reference = $order->getOrder()->ord_reference;
                }

                //if we want all messages
                if ($filter == 'all')
                {
                    //check if the order has messages
                    if(count($order->getMessages()) > 0){
                        foreach ($messages as $message){
                            // f NO BO user has replied to the order
                            if($noReply)
                            {
                                $unansweredMessages++;
                                $message->noReply = true;
                            }
                            else{
                                $message->noReply = false;
                            }
                            //push the client message to the list of meesages to send to the ajax
                            if($message->omsg_user_id == "")
                            array_push($messageList, $message);
                        }
                    }
                }
                //if we only wnat the unseen / unanswered messages
                else if ($filter == 'unseen')
                {
                    //check if the current order has NOT been replied by the BO user
                    if($noReply == true) {
                        //because we set the $noReply variable to true
                        //we still have to check if the order has messages
                        if(count($order->getMessages()) > 0){
                            foreach ($messages as $message) {
                                //if NO BO user has replied to the order
                                if($noReply)
                                {
                                    $unansweredMessages++;
                                    $message->noReply = true;
                                }
                                else{
                                    $message->noReply = false;
                                }
                                //push the client unanswered message to the list of meesages to send to the ajax
                                if($message->omsg_user_id == "")
                                array_push($messageList, $message);
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

        return new JsonModel(array(
            'success' => $success,
            'messages' => $messageList,
            'unansweredMessages' => $unansweredMessages,
        ));
    }
}