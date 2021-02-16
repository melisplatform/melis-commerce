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

use Laminas\Mvc\Controller\Plugin\Redirect;
use Laminas\View\Model\JsonModel;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;
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
        
        $translator = $this->getServiceManager()->get('translator');
        
        // Getting the Profile form from config
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_profile'])) ? $this->pluginFrontConfig['forms']['meliscommerce_profile'] : array();
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $profile = $factory->createForm($appConfigForm);
        
        $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        /**
         * If the Authentication doesn't have identity
         * this will redirect to the $redirection_link_not_loggedin
         */
        if ($melisComAuthSrv->hasIdentity())
        {
            
            $formData = $this->getFormData();
            
            // Value that trigger if the form is submitted or requested
            $is_submit = (!empty($formData['profile_is_submit'])) ? $formData['profile_is_submit'] : false;
            
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
            $clientSrv = $this->getServiceManager()->get('MelisComClientService');
            $clientPersonEntity = $clientSrv->getClientByIdAndClientPerson($clientId, $personId);
            
            if(!empty($clientPersonEntity))
            {
                $client = $clientPersonEntity->getClient();
                $person = $clientPersonEntity->getPersons()[0];
            }
            
            // Retrieves the table columns for data looping
            $tableClientColumns = $clientSrv->getTableColumns('MelisEcomClientTable');
            $tablePersonColumns = $clientSrv->getTableColumns('MelisEcomClientPersonTable');
            
            foreach($tableClientColumns as $column)
            {
                /**
                 * if the form was submitted,input fields will use the submitted values
                 * else this will use values of the current person
                 */
                if (!empty($formData[$column]) || $is_submit) 
                {
                    $data[$column] = isset($formData[$column])? $formData[$column] : '';
                } 
                else 
                {
                    if (!empty($client))
                    {
                        $data[$column] = isset($client->$column)? $client->$column : '';
                    }
                }
            }
            
            foreach($tablePersonColumns as $column)
            {
                /**
                 * if the form was submitted,input fields will use the submitted values
                 * else this will use values of the current person
                 */
                if (!empty($formData[$column]) || $is_submit) 
                {
                    $data[$column] = !empty($formData[$column])? $formData[$column] : '';
                } 
                else 
                {
                    if (!empty($person))
                    {
                        $data[$column] = !empty($person->$column)? $person->$column : '';
                    }
                }
                $personData[$column] = $data[$column];
            }
            
            // Adding the Confirm password to Form data so this will include to the form validation
            $data['cper_confirm_password'] = (!empty($formData['cper_confirm_password'])) ? $formData['cper_confirm_password'] : '';
            
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
                
                // Checking if the email value is not same with the current email of the person
                if ($data['cper_email'] != $melisComAuthSrv->getClientPersonSessDataByField('cper_email'))
                {
                    // Checking email availability
                    if ($clientSrv->checkEmailExist($data['cper_email'], $personId))
                    {
                        $errors['cper_email'] = array(
                            'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                            'emailExist' => $translator->translate('Email address is not available'),
                        );
                        
                        // Adding error to profile form factory
                        $profile->setMessages($errors);
                    }
                }
                
                if ($profile->isValid() && empty($errors))
                {
                    // Preparing the Client Data
                    $clientData = array(
                        'cli_country_id' => $data['cli_country_id']
                    );
                    
                    $personData = $profile->getData();
                    
                    // Removing field not belong to person table columns
                    unset($personData['profile_is_submit']);
                    unset($personData['cli_country_id']);
                    unset($personData['cper_confirm_password']);
                    
                    // Adding the Current user Client Id and Person Id from session
                    $personData['cper_id'] = $personId;
                    $personData['cper_client_id'] = $clientId;
                    
                    // Adding the Person to array as parameter in saving a client details
                    $persons[] = $personData;
                    
                    // Saving Client credintial using Client Service
                    $clientIdRes = $clientSrv->saveClient($clientData, $persons, array(), array(), $clientId);
                    
                    if (!is_null($clientIdRes))
                    {
                        $success = 1;
                        $message = $translator->translate('tr_meliscommerce_plugin_profile_save_success');
                        
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
                else 
                {
                    // Getting the form error messages
                    $errors = $profile->getMessages();
                    
                    $message = $translator->translate('tr_meliscommerce_client_pass_errors');
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
        $translator = $this->getServiceManager()->get('translator');
        $profile->get('cper_password')->setValue('');
        $profile->get('cper_password')->setLabel($profile->get('cper_password')->getLabel().' <i class="fa fa-info-circle fa-lg" title="'.$translator->translate('tr_meliscommerce_client_tooltip_password').'"></i>');
        $profile->get('cper_confirm_password')->setValue('');
        $profile->get('cper_confirm_password')->setLabel($profile->get('cper_confirm_password')->getLabel().' <i class="fa fa-info-circle fa-lg" title="'.$translator->translate('tr_meliscommerce_client_tooltip_password_2').'"></i>');
        
        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
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
     * This function generates the form displayed when editing the parameters of the plugin
     */
    public function createOptionsForms()
    {
        // construct form
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['modal_form'];
        
        $response = [];
        $render   = [];
        if (!empty($formConfig))
        {
            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);
                $request = $this->getServiceManager()->get('request');
                $parameters = $request->getQuery()->toArray();
                
                if (!isset($parameters['validate']))
                {
                    $form->setData($this->getFormData());
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $this->getFormData();
                    
                    $viewRender = $this->getServiceManager()->get('ViewRenderer');
                    $html = $viewRender->render($viewModelTab);
                    array_push($render, array(
                        'name' => $config['tab_title'],
                        'icon' => $config['tab_icon'],
                        'html' => $html
                    ));
                }
                else
                {
                    // validate the forms and send back an array with errors by tabs
                    $success = false;
                    $errors = array();
                    
                    $post = $request->getPost()->toArray();
                    
                    $form->setData($post);
                    
                    if (!$form->isValid())
                    {
                        if (empty($errors))
                        {
                            $errors = $form->getMessages();
                        }
                        else
                        {
                            $errors = ArrayUtil::merge($errors, $form->getMessages());
                        }
                    }
                    
                    if (empty($errors))
                    {
                        $success = true;
                    }
                    
                    if (!empty($errors))
                    {
                        foreach ($errors as $keyError => $valueError)
                        {
                            foreach ($config['elements'] as $keyForm => $valueForm)
                            {
                                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                                {
                                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                }
                            }
                        }
                    }
                    
                    array_push($response, array(
                        'name' => $this->pluginBackConfig['modal_form'][$formKey]['tab_title'],
                        'success' => $success,
                        'errors' => $errors,
                        'message' => '',
                    ));
                }
            }
        }
        
        if (!isset($parameters['validate']))
        {
            return $render;
        }
        else
        {
            return $response;
        }
    }
    
    /**
     * Returns the data to populate the form inside the modals when invoked
     * @return array
     */
    public function getFormData()
    {
        $data = parent::getFormData();
        return $data;
    }
    
    /**
     * This method will decode the XML in DB to make it in the form of the plugin config file
     * so it can overide it. Only front key is needed to update.
     * The part of the XML corresponding to this plugin can be found in $this->pluginXmlDbValue
     */
    public function loadDbXmlToPluginConfig()
    {
        $configValues = array();
        
        $xml = simplexml_load_string($this->pluginXmlDbValue);
        
        if ($xml)
        {
            if (!empty($xml->template_path))
            {
                $configValues['template_path'] = (string)$xml->template_path;
            }
        }
        
        return $configValues;
    }
    
    /**
     * This method saves the XML version of this plugin in DB, for this pageId
     * Automatically called from savePageSession listenner in PageEdition
     */
    public function savePluginConfigToXml($parameters)
    {
        $xmlValueFormatted = '';
        
        // template_path is mendatory for all plugins
        if (!empty($parameters['template_path']))
        {
            $xmlValueFormatted .= "\t\t" . '<template_path><![CDATA[' . $parameters['template_path'] . ']]></template_path>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
