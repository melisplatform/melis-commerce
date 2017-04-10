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
 * "deliveryAddress" plugin.
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
 * $plugin = $this->MelisCommerceDeliveryAddressPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/address/delivery'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'deliveryAddress');
 * 
 * How to display in your controller's view:
 * echo $this->deliveryAddress;
 * 
 * 
 */
class MelisCommerceDeliveryAddressPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $addressType = 'DEL';
        $success = 0;
        $errors = array();
        $message = '';

        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['delivery_address'])) ? $this->pluginFrontConfig['forms']['delivery_address'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $showSelectAddressData = (bool) $this->pluginFrontConfig['show_select_address_data'];
        // if $showSelectAddresData is "true" then we prepend a select input allowing user to select address that is listed
        if($showSelectAddressData) {
            $addSelectAddressSelect = [
                'elements' => [
                    [
                        'spec' => array(
                            'name' => 'sel-delivery-address',
                            'type' => 'EcomPluginDeliveryAddressSelect',
                            'options' => array(
                                'label' => 'tr_meliscommerce_client_select_address',
                                'disable_inarray_validator' => true,
                            ),

                            'attributes' => array(
                                'id' => 'sel-delivery-address',
                                'data-selectaddress' => 'select-address',
                            )
                        )
                    ]
                ],
            ];
            $appConfigForm = ArrayUtils::merge($addSelectAddressSelect, $appConfigForm);
        }
       
        $deliveryAddress = $factory->createForm($appConfigForm);
        
        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $translator = $this->getServiceLocator()->get('translator');
        /**
         * If the Authentication doesn't have identity
         * this will redirect to the $redirection_link_not_loggedin
         */
        if ($melisComAuthSrv->hasIdentity())
        {
            // Value that trigger if the form is submitted or requested
            $is_submit = (!empty($this->pluginFrontConfig['delivery_add_is_submit'])) ? $this->pluginFrontConfig['delivery_add_is_submit'] : false;
            
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
            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
            $clientDelAddress = $clientSrv->getClientAddressesByClientPersonId($personId, $addressType);
            
            $deliveryAddressId = null;
            $clientAddress = array();
            if (!empty($clientDelAddress[0]))
            {
                $clientAddress = $clientDelAddress[0];
                $deliveryAddressId = $clientAddress->cadd_id;
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
            $deliveryAddress->setData($data);
            if ($is_submit) {
                if ($deliveryAddress->isValid())
                {
                    $deliveryAddressId          = (int) $data['cadd_id'];
                    $data['cadd_client_id']     = $clientId;
                    $data['cadd_client_person'] = $personId;
                    
                    /**
                     * Retrieving the id of the address type
                     * using the address type code
                     */
                    $clientAddTypeTbl   = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTable');
                    $clientAddType      = $clientAddTypeTbl->getEntryByField('catype_code', $addressType)->current();
                    $data['cadd_type']  = $clientAddType->catype_id;
                    
                    // Saving Client person delivery addrress
                    $deliveryAddressId = $clientSrv->saveClientAddress($data, $deliveryAddressId);
                    
                    if (!is_null($deliveryAddressId))
                    {
                        $success = 1;
                        $message = $translator->translate('tr_meliscommerce_checkout_delivery_save_success');
                    }
                    else
                    {
                        $message = $translator->translate('tr_meliscommerce_checkout_delivery_save_error');
                    }
                }
                else 
                {
                    $message = $translator->translate('tr_meliscommerce_checkout_delivery_errors');
                    $errors  = $deliveryAddress->getMessages();
                }
            }
            else {

            }
        }
        
        /**
         * As default form will created with the "profile_is_submit" input having value of "1"
         * so that after form render this will ready for submission
         */
        $deliveryAddress->get('delivery_add_is_submit')->setValue('1');

        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'deliveryAddress' => $deliveryAddress,
            'message' => $message,
            'success' => $success,
            'errors'  => $errors
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
