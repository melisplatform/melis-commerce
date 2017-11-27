<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

use MelisEngine\Controller\Plugin\MelisTemplatingPlugin;
use MelisFront\Navigation\MelisFrontNavigation;

use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "order messages" plugin.
 * 
 * Please look inside app.plugins.php for possible awaited parameters
 * in front and back function calls.
 * 
 * front() and back() are the only functions to create / update.
 * front() generates the website view
 * back() generates the plugin view in template edition mode (TODO)
 * 
 * Configuration can be found in $pluginConfig / $pluginFrontConfig / $pluginBackConfig
 * Configuration is automatically merged with the parameters provided when calling the plugin.
 * Merge detects automatically from the route if rendering must be done for front or back.
 * 
 * How to call this plugin without parameters:
 * $plugin = $this->MelisCommerceOrderMessagesPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceOrderMessagesPlugin();
 * $parameters = array(
 *      'template_path' => 'your-site-folder/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'orderMessages');
 * 
 * How to display in your controller's view:
 * echo $this->orderMessages;
 * 
 * 
 */
class MelisCommerceOrderMessagesPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $clientId = null;
        $messages = array();
        $errors = array();
        $success = 0;
        
        $orderId = !empty($this->pluginFrontConfig['m_c_order'])? $this->pluginFrontConfig['m_c_order'] : null;
        $message = !empty($this->pluginFrontConfig['m_c_message'])? $this->pluginFrontConfig['m_c_message'] : '';
        $isSubmit = !empty($this->pluginFrontConfig['message_is_submit'])? $this->pluginFrontConfig['message_is_submit'] : 0;
        
        $orderSvc = $this->getServiceLocator()->get('MelisComOrderService');
        $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $translator = $this->getServiceLocator()->get('translator');
        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_order_add_message_form'])) ? 
                          $this->pluginFrontConfig['forms']['meliscommerce_order_add_message_form'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $addMessageForm = $factory->createForm($appConfigForm);
        
        $addMessageForm->setData(array('m_c_order' => $orderId));        
        if ($isSubmit) {
            
            if ($ecomAuthSrv->hasIdentity()) {
                $addMessageForm->setData(array('m_c_order' => $orderId, 'm_c_message' => $message));
                
                if ($addMessageForm->isValid()) {
                    
                    $data = $addMessageForm->getData();
                    $messageData = array(
                        'omsg_order_id' => $data['m_c_order'],
                        'omsg_client_id' => $ecomAuthSrv->getClientId(),
                        'omsg_client_person_id' => $ecomAuthSrv->getPersonId(),
                        'omsg_message' => $data['m_c_message'],
                        'omsg_date_creation' => date('Y-m-d H:i:s'),
                    );
                    
                    $messageId = $orderSvc->saveOrderMessage($messageData);
                     
                    $success = 1;
                    if (empty($messageId)) {
                        $errors['genError'] = array(
                            'genError' => $translator->translate('tr_meliscommerce_general_error')
                        );
                        $success = 0;
                    }
                    
                } else {
                    
                    $errors = $addMessageForm->getMessages();
                }
            }
            
        }
        if(!empty($orderId)){
            foreach($orderSvc->getOrderMessageByOrderId($orderId) as $message){
                $tmp = array();
                $tmp = $message->getArrayCopy();
                if(empty($message->omsg_user_id)){
                    // get clients
                    
                    $client = $clientSvc->getClientByIdAndClientPerson($message->omsg_client_id, $message->omsg_client_person_id);
                    $clientPerson = $client->getPersons()[0];
                    
                    $tmp['firstName'] = $clientPerson->cper_firstname;
                    $tmp['lastName'] = $clientPerson->cper_name;
                    $tmp['middleName'] = $clientPerson->cper_middle_name;
                    
                }else{
                    // get admin details
                    $tmp['firstName'] = 'Admin';
                    $tmp['lastName'] = '';
                    $tmp['middleName'] = '';
                }
                $messages[] = $tmp;
            }
        }
        
        $viewVariables = array(
            'orderMessages' => $messages,
            'addMessageForm' => $addMessageForm,
            'success' => $success,
            'errors' => $errors,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    public function back()
    {
        
    }
}
