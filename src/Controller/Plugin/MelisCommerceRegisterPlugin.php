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
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $is_submit              = (!empty($this->pluginFrontConfig['m_is_submit'])) ? $this->pluginFrontConfig['m_is_submit'] : false;
        $data['m_autologin']    = (!empty($this->pluginFrontConfig['m_autologin'])) ? $this->pluginFrontConfig['m_autologin'] : true;
        $data['m_email']        = (!empty($this->pluginFrontConfig['m_email'])) ? $this->pluginFrontConfig['m_email'] : '';
        $data['m_password']     = (!empty($this->pluginFrontConfig['m_password'])) ? $this->pluginFrontConfig['m_password'] : '';
        $data['m_password2']    = (!empty($this->pluginFrontConfig['m_password2'])) ? $this->pluginFrontConfig['m_password2'] : '';
        $data['m_civility']     = (!empty($this->pluginFrontConfig['m_civility'])) ? $this->pluginFrontConfig['m_civility'] : '';
        $data['m_firstname']    = (!empty($this->pluginFrontConfig['m_firstname'])) ? $this->pluginFrontConfig['m_firstname'] : '';
        $data['m_lastname']     = (!empty($this->pluginFrontConfig['m_lastname'])) ? $this->pluginFrontConfig['m_lastname'] : '';
        $data['m_language']     = (!empty($this->pluginFrontConfig['m_language'])) ? $this->pluginFrontConfig['m_language'] : '';
        $data['m_country']      = (!empty($this->pluginFrontConfig['m_country'])) ? $this->pluginFrontConfig['m_country'] : '';
        
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
                // Preparing the person data
                $person[] = array(
                    'cper_status'           => 1,
                    'cper_is_main_person'   => 1,
                    'cper_lang_id'          => $data['m_language'],
                    'cper_email'            => $data['m_email'],
                    'cper_password'         => $data['m_password'],
                    'cper_civility'         => $data['m_civility'],
                    'cper_name'             => $data['m_lastname'],
                    'cper_firstname'        => $data['m_firstname'],

                );

                if(!empty($data['m_email']))
                {
                    $clientPersonTbl = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
                    // Checking if the Email entered is existing on the database
                    $clientPerson = $clientPersonTbl->getEntryByField('cper_email', $data['m_email'])->current();

                    if ($clientPerson)
                    {
                        $errors['m_email'] = array(
                            'label' => 'Email address',
                            'isExist' => 'Email already exist, please try another email address'
                        );
                    }
                    else {
                        // Saving Client data using Client Servive
                        $melisComClientService = $this->getServiceLocator()->get('MelisComClientService');
                        $success = (int) $melisComClientService->saveClient($client, $person);

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
            'success' => $success,
            'errors' => $errors
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This function return the back office rendering for the template edition system
     * TODO
     */
    public function back()
    {
        return array();
    }
}
