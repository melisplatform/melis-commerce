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
use Zend\View\Model\JsonModel;
/**
 * This plugin implements the business logic of the
 * "profile" plugin.
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
 * $plugin = $this->MelisCommerceClientProfilePlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/profile/profile'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'profile');
 * 
 * How to display in your controller's view:
 * echo $this->profile;
 * 
 * 
 */
class MelisCommerceProfilePlugin extends MelisTemplatingPlugin
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
        
        // Getting the Profile form from config
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_profile'])) ? $this->pluginFrontConfig['forms']['meliscommerce_profile'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $profile = $factory->createForm($appConfigForm);
        
        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        /**
         * If the Authentication doesn't have identity
         * this will redirect to the $redirection_link_not_loggedin
         */
        if ($melisComAuthSrv->hasIdentity())
        {
            // Value that trigger if the form is submitted or requested
            $is_submit = (!empty($this->pluginFrontConfig['profile_is_submit'])) ? $this->pluginFrontConfig['profile_is_submit'] : false;
            
            // Getting the current use Client Id and Person Id from session
            $clientId = $melisComAuthSrv->getClientId();
            $personId = $melisComAuthSrv->getPersonId();
            
            /**
             * Pre-data to fill the form
             * If page requested the form, data will loaded are from database using service
             * else this will loaded from $pluginFrontConfig
             * Ex. 
             *      $preData[$key] = (!$is_submit) ? $val : '';
             */
            $preData = array();
            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
            $clientPersonEntity = $clientSrv->getClientByIdAndClientPerson($clientId, $personId);
            foreach ($clientPersonEntity->getClient() As $key => $val)
            {
                $preData[$key] = (!$is_submit) ? $val : '';
            }
            
            foreach ($clientPersonEntity->getPersons()[0] As $key => $val)
            {
                $preData[$key] = (!$is_submit) ? $val : '';
            }
            
            $data['cper_status'] = (!empty($this->pluginFrontConfig['cper_status'])) ? $this->pluginFrontConfig['cper_status'] : $preData['cper_status'];
            $data['cper_civility'] = (!empty($this->pluginFrontConfig['cper_civility'])) ? $this->pluginFrontConfig['cper_civility'] : $preData['cper_civility'];
            $data['cper_firstname'] = (!empty($this->pluginFrontConfig['cper_firstname'])) ? $this->pluginFrontConfig['cper_firstname'] : $preData['cper_firstname'];
            $data['cper_name'] = (!empty($this->pluginFrontConfig['cper_name'])) ? $this->pluginFrontConfig['cper_name'] : $preData['cper_name'];
            $data['cper_middle_name'] = (!empty($this->pluginFrontConfig['cper_middle_name'])) ? $this->pluginFrontConfig['cper_middle_name'] : $preData['cper_middle_name'];
            $data['cper_lang_id'] = (!empty($this->pluginFrontConfig['cper_lang_id'])) ? $this->pluginFrontConfig['cper_lang_id'] : $preData['cper_lang_id'];
            $data['cli_country_id'] = (!empty($this->pluginFrontConfig['cli_country_id'])) ? $this->pluginFrontConfig['cli_country_id'] : $preData['cli_country_id'];
            $data['cper_email'] = (!empty($this->pluginFrontConfig['cper_email'])) ? $this->pluginFrontConfig['cper_email'] : $preData['cper_email'];
            $data['cper_password'] = (!empty($this->pluginFrontConfig['cper_password'])) ? $this->pluginFrontConfig['cper_password'] : '';
            $data['cper_confirm_password'] = (!empty($this->pluginFrontConfig['cper_confirm_password'])) ? $this->pluginFrontConfig['cper_confirm_password'] : '';
            $data['cper_job_title'] = (!empty($this->pluginFrontConfig['cper_job_title'])) ? $this->pluginFrontConfig['cper_job_title'] : $preData['cper_job_title'];
            $data['cper_job_service'] = (!empty($this->pluginFrontConfig['cper_job_service'])) ? $this->pluginFrontConfig['cper_job_service'] : $preData['cper_job_service'];
            $data['cper_tel_mobile'] = (!empty($this->pluginFrontConfig['cper_tel_mobile'])) ? $this->pluginFrontConfig['cper_tel_mobile'] : $preData['cper_tel_mobile'];
            $data['cper_tel_landline'] = (!empty($this->pluginFrontConfig['cper_tel_landline'])) ? $this->pluginFrontConfig['cper_tel_landline'] : $preData['cper_tel_landline'];
            
            // Setting the Datas to Profile Form
            $profile->setData($data);
            
            if ($is_submit)
            {
                // Checking if the Contact Form has data of the password
                if (empty($data['cper_password']))
                {
                    // If the existing password empty, this means contact not updating the current password
                    // removing Input from form will also remove from the validation
                    $profile->getInputFilter()->remove('cper_password');
                    $profile->getInputFilter()->remove('cper_confirm_password');
                }
                
                if ($data['cper_email'] == $melisComAuthSrv->getClientPersonSessDataByField('cper_email'))
                {
                    $profile->getInputFilter()->remove('cper_email');
                }
                
                if ($profile->isValid())
                {
                    // Preparing the Client Data
                    $clientData = array(
                        'cli_country_id' => $data['cli_country_id']
                    );
                    
                    // Unsetting the data that are not related to Client Person table
                    unset($data['cli_country_id']);
                    unset($data['cper_confirm_password']);
                    
                    if (empty($data['cper_password']))
                    {
                        /**
                         * Unsetting password from data to avoid overwrting 
                         * the existing password stored in database
                         */
                        unset($data['cper_password']);
                    }
                    // Adding the Current user Client Id and Person Id from session
                    $data['cper_id'] = $personId;
                    $data['cper_client_id'] = $clientId;
                    $personData[] = $data;
                    
                    // Saving Client credintial using Client Service
                    $clientIdRes = $clientSrv->saveClient($clientData, $personData, array(), array(), $clientId);
                    
                    if (!is_null($clientIdRes))
                    {
                        $success = 1;
                        $message = 'Profile has been successfully saved';
                        
                        // Retrieving updated info of Client Person using Client Service
                        $clientPersonEntity = $clientSrv->getClientPersonById($personId);
                        $personData = $clientPersonEntity->getPerson();
                        // Unsetting password to avoid storing to session
                        unset($personData->cper_password);
                        // Unsetting civlity info
                        unset($personData->civ_id);
                        unset($personData->civility_trans);
                        // Updating Data stored in Session
                        $storage = $melisComAuthSrv->getStorage();
                        $storage->write($personData);
                    }
                    else 
                    {
                        $message = 'Something is wrong, please contact administrator for assistance';
                    }
                }
                else 
                {
                    $message = 'Error(s) occured';
                    $errors = $profile->getMessages();
                }
            }
        }
        else 
        {
            // Gettin the Redirect Uri from config
            $m_redirection_link_not_loggedin = (!empty($this->pluginFrontConfig['m_redirection_link_not_loggedin'])) ? $this->pluginFrontConfig['m_redirection_link_not_loggedin'] : 'http://www.test.com';
            
            $controller = $this->getController();
            $redirector = $controller->getPluginManager()->get('Redirect');
            $redirector->toUrl($m_redirection_link_not_loggedin);
        }
        
        /**
         * Removing the Password value to avoid dislying in form input and
         * adding custom label for password and confirm password as guide in creating password
         */
        $translator = $this->getServiceLocator()->get('translator');
        $profile->get('cper_password')->setValue('');
        $profile->get('cper_password')->setLabel($profile->get('cper_password')->getLabel().' <i class="fa fa-info-circle fa-lg" title="'.$translator->translate('tr_meliscommerce_client_tooltip_password').'"></i>');
        $profile->get('cper_confirm_password')->setValue('');
        $profile->get('cper_confirm_password')->setLabel($profile->get('cper_confirm_password')->getLabel().' <i class="fa fa-info-circle fa-lg" title="'.$translator->translate('tr_meliscommerce_client_tooltip_password_2').'"></i>');
        
        /**
         * As default form will created with the "profile_is_submit" input having value of "1"
         * so that after form render this will ready for submission
         */
        $profile->get('profile_is_submit')->setValue('1');
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'profile' => $profile,
            'message' => $message,
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
