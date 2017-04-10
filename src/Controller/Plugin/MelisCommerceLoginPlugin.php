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
/**
 * This plugin implements the business logic of the
 * "Login" plugin.
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
 * $plugin = $this->MelisCommerceLoginPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/login/login'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'login');
 * 
 * How to display in your controller's view:
 * echo $this->login;
 * 
 * 
 */
class MelisCommerceLoginPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_login'])) ? $this->pluginFrontConfig['forms']['meliscommerce_login'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $loginForm = $factory->createForm($appConfigForm);
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $m_login = (!empty($this->pluginFrontConfig['m_login'])) ? $this->pluginFrontConfig['m_login'] : '';
        $m_password = (!empty($this->pluginFrontConfig['m_password'])) ? $this->pluginFrontConfig['m_password'] : '';
        $m_remeber_me = (!empty($this->pluginFrontConfig['m_remember_me'])) ? $this->pluginFrontConfig['m_remember_me'] : false;
        
        $is_submit = (!empty($this->pluginFrontConfig['m_is_submit'])) ? $this->pluginFrontConfig['m_is_submit'] : false;
        
        // Redirection key after login authentication success
        $redirection_link  = (!empty($this->pluginFrontConfig['m_redirection_link_ok'])) ? $this->pluginFrontConfig['m_redirection_link_ok'] : '';
        
        $success = 0;
        $message = null;
        $errors = array();
        
        $loginForm->setData($this->pluginFrontConfig);
        
        if ($is_submit)
        {
            if($loginForm->isValid())
            {
                /**
                 * Login using Commerce Authentication Service
                 */
                $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
                $result = $melisComAuthSrv->login($m_login, $m_password, $m_remeber_me);
                if ($result['success'] == 1)
                {
                    $success = 1;
                    
                    $clientId = $melisComAuthSrv->getClientId();
                    $clientKey = $melisComAuthSrv->getClientKey();
                    
                    $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
                    // Preparing the client Basket, which is added to Persistent basket
                    $melisComBasketService->getBasket($clientId, $clientKey);
                }
                
                $message = $result['message'];
            }
            else
            {
                // Retrieving the errors occured on form validation
                $errors = $loginForm->getMessages();
            }
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'login' => $loginForm,
            'redirect_link' => $redirection_link,
            'message' => $message,
            'success' => $success,
            'errors' => $errors
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
