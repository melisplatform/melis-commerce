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
use Zend\Stdlib\ArrayUtils;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\View\Model\JsonModel;
/**
 * This plugin implements the business logic of the
 * "billingAddress" plugin.
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
 * $plugin = $this->MelisCommerceBillingAddressPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/address/billing'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'billingAddress');
 * 
 * How to display in your controller's view:
 * echo $this->billingAddress;
 * 
 * 
 */
class MelisCommerceBillingAddressPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $addressType = 'BIL';
        $success = 0;
        $errors = array();
        $message = '';
        $data = array();
        
        $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['billing_address'])) ? $this->pluginFrontConfig['forms']['billing_address'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);

        $showSelectAddresData = (bool) $this->pluginFrontConfig['show_select_address_data'];
        // if $showSelectAddresData is "true" then we prepend a select input allowing user to select address that is listed
        if($showSelectAddresData) {
            $addSelectAddressSelect = [
                $appConfigForm['attributes'],
                $appConfigForm['hydrator'],
                'elements' => [
                    [
                        'spec' => array(
                            'name' => 'sel-billing-address',
                            'type' => 'EcomPluginBillingAddressSelect',
                            'options' => array(
                                'label' => 'tr_meliscommerce_client_select_address',
                                'disable_inarray_validator' => true,
                            ),
                            'attributes' => array(
                                'id' => 'sel-billing-address',
                                'data-selectaddress' => 'select-address',
                            )
                        )
                    ]
                ],
            ];
            $appConfigForm = ArrayUtils::merge($addSelectAddressSelect, $appConfigForm);;
        }
        $billingAddress = $factory->createForm($appConfigForm);
        
        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $translator = $this->getServiceLocator()->get('translator');
        /**
         * If the Authentication doesn't have identity
         * this will redirect to the $redirection_link_not_loggedin
         */
        if ($melisComAuthSrv->hasIdentity())
        {
            // Value that trigger if the form is submitted or requested
            $is_submit = (!empty($this->pluginFrontConfig['billing_add_is_submit'])) ? $this->pluginFrontConfig['billing_add_is_submit'] : false;
            
            // Getting the current use Client Id and Person Id from session
            $clientId = $melisComAuthSrv->getClientId();
            $personId = $melisComAuthSrv->getPersonId();
            
            $clientBilAddress = $clientSrv->getClientAddressesByClientPersonId($personId, $addressType);
            
            $billingAddressId = null;
            $clientAddress = array();
            if (!empty($clientBilAddress[0]))
            {
                $clientAddress    = $clientBilAddress[0];
                $billingAddressId = $clientAddress->cadd_id;
            }
            
            // Retrieves the table columns for data looping
            $tableColumns = $clientSrv->getTableColumns('MelisEcomClientAddressTable');
            foreach($tableColumns as $column){
                // if the form was submitted, retrieve its values
                if ($is_submit) {
                    $data[$column] = isset($this->pluginFrontConfig[$column])? $this->pluginFrontConfig[$column] : '';
                } else {
                    $data[$column] = !empty($clientAddress->$column)? $clientAddress->$column : '';
                }
            }
            
            // Setting the Datas to Profile Form
            $billingAddress->setData($data);
            
            if ($is_submit)
            {
                if ($billingAddress->isValid())
                {
                    $billingAddressId           = (int) $data['cadd_id'];
                    $data['cadd_client_id']     = $clientId;
                    $data['cadd_client_person'] = $personId;
                    
                    /**
                     * Retrieving the id of the address type
                     * using the address type code
                     */
                    $clientAddTypeTbl = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTable');
                    $clientAddType = $clientAddTypeTbl->getEntryByField('catype_code', $addressType)->current();
                    $data['cadd_type'] = $clientAddType->catype_id;
                    $data = array_filter($data);
                    // Saving Client person delivery addrress
                    $billingAddressId = $clientSrv->saveClientAddress($data, $billingAddressId);
                    
                    if (!is_null($billingAddressId))
                    {
                        $success = 1;
                        $message = $translator->translate('tr_meliscommerce_checkout_billing_save_success');
                    }
                    else
                    {
                        $message = $translator->translate('tr_meliscommerce_checkout_delivery_save_error');
                    }
                }
                else 
                {
                    $message = $translator->translate('tr_meliscommerce_checkout_delivery_errors');
                    $errors  = $billingAddress->getMessages();
                }
            }
        }
        
        /**
         * As default form will created with the "profile_is_submit" input having value of "1"
         * so that after form render this will ready for submission
         */
        $billingAddress->get('billing_add_is_submit')->setValue('1');

        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'billingAddress' => $billingAddress,
            'message' => $message,
            'success' => $success,
            'errors' => $errors
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
