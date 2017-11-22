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
use Zend\Stdlib\ArrayUtils;
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
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceProfilePlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $success = 0;
        $errors = array();
        $message = '';
        
        $translator = $this->getServiceLocator()->get('translator');
        
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
            
            if(!empty($clientPersonEntity)){
                $client = $clientPersonEntity->getClient();
                $person = $clientPersonEntity->getPersons()[0];
            }
            
            // Retrieves the table columns for data looping
            $tableClientColumns = $clientSrv->getTableColumns('MelisEcomClientTable');
            $tablePersonColumns = $clientSrv->getTableColumns('MelisEcomClientPersonTable');
            
            foreach($tableClientColumns as $column){
                // if the form was submitted, retrieve its values
                if (!empty($this->pluginFrontConfig[$column])) {
                    $data[$column] = isset($this->pluginFrontConfig[$column])? $this->pluginFrontConfig[$column] : '';
                } else {
                    $data[$column] = isset($client->$column)? $client->$column : '';
                }
            }
            
            foreach($tablePersonColumns as $column){
                if (!empty($this->pluginFrontConfig[$column])) {
                    $data[$column] = !empty($this->pluginFrontConfig[$column])? $this->pluginFrontConfig[$column] : '';
                } else {
                    $data[$column] = !empty($person->$column)? $person->$column : '';
                }
                $personData[$column] = $data[$column];
            }
            if(empty($data['cper_confirm_password'])){
                $data['cper_password'] = null;
            }
            $data['cper_confirm_password'] = (!empty($this->pluginFrontConfig['cper_confirm_password'])) ? $this->pluginFrontConfig['cper_confirm_password'] : '';
            
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
                    
                    // Removing password to avoid password update
                    unset($personData['cper_password']);
                }
                
                if ($data['cper_email'] == $melisComAuthSrv->getClientPersonSessDataByField('cper_email'))
                {
                    $profile->getInputFilter()->remove('cper_email');
                }
                else
                {
                    if ($clientSrv->checkEmailExist($data['cper_email'], $personId))
                    {
                        $errors['cper_email'] = array(
                            'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                            'emailExist' => $translator->translate('Email address is not available'),
                        );
                    }
                }
                
                
                if ($profile->isValid())
                {
                    if (empty($errors))
                    {
                        // Preparing the Client Data
                        $clientData = array(
                            'cli_country_id' => $data['cli_country_id']
                        );
                        
                        
                        // Adding the Current user Client Id and Person Id from session
                        $personData['cper_id'] = $personId;
                        $personData['cper_client_id'] = $clientId;
                        $personData['cper_is_main_person'] = 1;
                        $persons[] = $personData;
                        
                        // Saving Client credintial using Client Service
                        $clientIdRes = $clientSrv->saveClient($clientData, $persons, array(), array(), $clientId);
                        
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
                            // Adding back the client key from old session ideitity and update a new Person details
                            $personData->clientKey = $melisComAuthSrv->getIdentity()->clientKey;
                            // Updating Data stored in Session
                            $storage = $melisComAuthSrv->getStorage();
                            $storage->write($personData);
                        }
                        else
                        {
                            $message = $translator->translate('tr_meliscommerce_client_common_error');
                        }
                    }
                }
                else 
                {
                    $message = $translator->translate('tr_meliscommerce_client_pass_errors');
                    $errors = ArrayUtils::merge($errors, $profile->getMessages());
                }
            }
            
            /**
             * This input field set value in order to validate 
             * after submission of the form proivided of this plugin
             */
            $profile->get('profile_is_submit')->setValue(true);
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
}
