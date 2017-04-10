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

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Sendmail;
/**
 * This plugin implements the business logic of the
 * "lostPasswordReset" plugin.
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
 * $plugin = $this->MelisCommerceLostPasswordResetPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/password/reset'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'lostPasswordReset');
 * 
 * How to display in your controller's view:
 * echo $this->lostPasswordReset;
 * 
 * 
 */
class MelisCommerceLostPasswordResetPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $success = 0;
        $errors = array();
        $message = '';
        $loggedinRedirectLink = null;
        $notLoggedinRedirectLink = null;
        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['lost_password_reset'])) ? $this->pluginFrontConfig['forms']['lost_password_reset'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $lostPasswordReset = $factory->createForm($appConfigForm);
        
        $data['m_password'] = (!empty($this->pluginFrontConfig['m_password'])) ? $this->pluginFrontConfig['m_password'] : '';
        $data['m_password2'] = (!empty($this->pluginFrontConfig['m_password2'])) ? $this->pluginFrontConfig['m_password2'] : '';
        $data['m_recovery_key'] = (!empty($this->pluginFrontConfig['m_recovery_key'])) ? $this->pluginFrontConfig['m_recovery_key'] : '';
        $data['m_autologin'] = (!empty($this->pluginFrontConfig['m_autologin'])) ? $this->pluginFrontConfig['m_autologin'] : false;
        
        $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
        $translator = $this->getServiceLocator()->get('translator');
        $clientPerson = $clientSrv->getClientPersonByRecoveryKey($data['m_recovery_key']);
        
        if (empty($clientPerson))
        {
            $isValidKey = false;
            $message = 'Invalid password recovery key';
            $message = $translator->translate('tr_meliscommerce_client_pass_key_invalid');
        }
        else
        {
            $isValidKey = true;
            // Value that trigger if the form is submitted or requested
            $is_submit = (!empty($this->pluginFrontConfig['m_is_submit'])) ? $this->pluginFrontConfig['m_is_submit'] : false;
        
            // Setting the Datas to Lost Password Form
            $lostPasswordReset->setData($data);
        
            if ($is_submit)
            {
                if ($lostPasswordReset->isValid())
                {
                    $person = (Array) $clientPerson;
                    $person['cper_password'] = $data['m_password'];
                    $person['cper_password_recovery_key'] = null;
                    unset($person['cper_id']);
                    
                    $clientSrv->saveClientPerson($person, null, $clientPerson->cper_id);
                    
                    if ($data['m_autologin'])
                    {
                        $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
                        
                        $login = $pluginManager->get('MelisCommerceLoginPlugin');
                        // Adding the custom link the user is already identify
                        $loginParameters = array(
                            'm_login' => $clientPerson->cper_email,
                            'm_password' => $data['m_password'],
                        );
                        // add generated view to children views for displaying it in the contact view
                        $login->render($loginParameters);
                    }
                    
                    $message = $translator->translate('tr_meliscommerce_client_pass_change_success');
                    $success = 1;
                }
                else
                {
                    $message = $translator->translate('tr_meliscommerce_client_pass_errors');
                    $errors = $lostPasswordReset->getMessages();
                }
            }
        }
        
        /**
         * As default form will created with the "m_is_submit" input having value of "1"
         * so that after form render this will ready for submission
         */
        $lostPasswordReset->get('m_is_submit')->setValue('1');
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'lostPasswordReset' => $lostPasswordReset,
            'message' => $message,
            'success' => $success,
            'errors' => $errors,
            'loggedinRedirectLink' => $loggedinRedirectLink,
            'notLoggedinRedirectLink' => $notLoggedinRedirectLink,
            'isValidKey' => $isValidKey,
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}