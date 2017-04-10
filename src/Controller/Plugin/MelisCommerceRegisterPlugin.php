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
use Zend\Code\Scanner\Util;
use Zend\Stdlib\ArrayUtils;
use Zend\Session\Container;
/**
 * This plugin implements the business logic of the
 * "Registration" plugin.
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
 * $plugin = $this->MelisCommerceRegisterPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/registration/regitration'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'regitration');
 * 
 * How to display in your controller's view:
 * echo $this->registration;
 * 
 * 
 */
class MelisCommerceRegisterPlugin extends MelisTemplatingPlugin
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
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_registration'])) ? $this->pluginFrontConfig['forms']['meliscommerce_registration'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $registrationForm = $factory->createForm($appConfigForm);
        $melisComClientService = $this->getServiceLocator()->get('MelisComClientService');
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $is_submit              = (!empty($this->pluginFrontConfig['m_is_submit'])) ? $this->pluginFrontConfig['m_is_submit'] : false;
        $data['m_autologin']    = (!empty($this->pluginFrontConfig['m_autologin'])) ? $this->pluginFrontConfig['m_autologin'] : true;
        $data['m_redirection_link_ok']      = (!empty($this->pluginFrontConfig['m_redirection_link_ok'])) ? $this->pluginFrontConfig['m_redirection_link_ok'] : 'http://www.test.com';
        
        // get submitted form values
        foreach($registrationForm->getElements() as $key => $val){
            $data[$key] = (!empty($this->pluginFrontConfig[$key])) ? $this->pluginFrontConfig[$key] : null;
        }
        
        $redirection_link = $data['m_redirection_link_ok'];
        
        $registrationForm->setData($data);
        
        $success = 0;
        $errors = array();
        if ($is_submit)
        {

            if($registrationForm->isValid())
            {
                // Preparing the client data
                $client = array(
                    'cli_status'        => 1,
                    'cli_country_id'    => $data['m_country']
                );
                
                $container = new Container('melisplugins');
                $langId = $container['melis-plugins-lang-id'];
                
                // Preparing the person data
                $person[] = array(
                    'cper_status'           => 1,
                    'cper_is_main_person'   => 1,
                    'cper_lang_id'          => $langId,
                    'cper_email'            => $data['cper_email'],
                    'cper_password'         => $data['cper_password'],
                    'cper_civility'         => $data['cper_civility'],
                    'cper_name'             => $data['cper_name'],
                    'cper_firstname'        => $data['cper_firstname'],

                );

                if(!empty($data['cper_email']))
                {
                    // Checking if the Email entered is existing on the database
                    $clientPerson = $melisComClientService->getClientPersonByEmail($data['cper_email']);

                    if ($clientPerson)
                    {
                        $translator = $this->getServiceLocator()->get('translator');
                        $errors['m_email'] = array(
                            'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                            'emailExist' => $translator->translate('Email address is not available'),
                        );
                    }
                    else 
                    {
                        // Saving Client data using Client Servive
                        $success = (int) $melisComClientService->saveClient($client, $person);
                        
                        /**
                         * if the autologgin has true/1 value 
                         * this will automatically loggin after registration
                         */
                        if ($data['m_autologin'])
                        {
                            /**
                             * Login using Commerce Authentication Service
                             */
                            $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
                            $melisComAuthSrv->login($data['cper_email'], $data['cper_password']);
                        }
                    }
                }
            }
            else
            {
                if (!empty($errors))
                {
                    $errors = ArrayUtils::merge($errors, $registrationForm->getMessages());
                }
                else
                {
                    $errors = $registrationForm->getMessages();
                }
            }
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'registration' => $registrationForm,
            'redirect_link' => $redirection_link,
            'success' => $success,
            'errors' => $errors
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
