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
            
            $preData['cadd_address_name']   = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_address_name : '';
            $preData['cadd_num']            = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_num : '';
            $preData['cadd_street']         = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_street : '';
            $preData['cadd_building_name']  = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_building_name : '';
            $preData['cadd_stairs']         = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_stairs : '';
            $preData['cadd_city']           = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_city : '';
            $preData['cadd_state']          = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_state : '';
            $preData['cadd_country']        = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_country : '';
            $preData['cadd_zipcode']        = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_zipcode : '';
            $preData['cadd_company']        = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_company : '';
            $preData['cadd_phone_mobile']   = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_phone_mobile : '';
            $preData['cadd_phone_landline'] = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_phone_landline : '';
            $preData['cadd_complementary']  = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_complementary : '';
            $preData['cadd_id']             = (!$is_submit && !empty($clientAddress)) ? $clientAddress->cadd_id : '';
            
            $data['cadd_address_name']      = (!empty($is_submit && $this->pluginFrontConfig['cadd_address_name']))   ? $this->pluginFrontConfig['cadd_address_name']  : $preData['cadd_address_name'];
            $data['cadd_num']               = ($is_submit && !empty($this->pluginFrontConfig['cadd_num']))            ? $this->pluginFrontConfig['cadd_num']           : $preData['cadd_num'];
            $data['cadd_street']            = ($is_submit && !empty($this->pluginFrontConfig['cadd_street']))         ? $this->pluginFrontConfig['cadd_street']        : $preData['cadd_street'];
            $data['cadd_building_name']     = ($is_submit && !empty($this->pluginFrontConfig['cadd_building_name']))  ? $this->pluginFrontConfig['cadd_building_name'] : $preData['cadd_building_name'];
            $data['cadd_stairs']            = ($is_submit && !empty($this->pluginFrontConfig['cadd_stairs']))         ? $this->pluginFrontConfig['cadd_stairs']        : $preData['cadd_stairs'];
            $data['cadd_city']              = ($is_submit && !empty($this->pluginFrontConfig['cadd_city']))           ? $this->pluginFrontConfig['cadd_city'] : $preData['cadd_city'];
            $data['cadd_state']             = ($is_submit && !empty($this->pluginFrontConfig['cadd_state']))          ? $this->pluginFrontConfig['cadd_state'] : $preData['cadd_state'];
            $data['cadd_country']           = ($is_submit && !empty($this->pluginFrontConfig['cadd_country']))        ? $this->pluginFrontConfig['cadd_country'] : $preData['cadd_country'];
            $data['cadd_zipcode']           = ($is_submit && !empty($this->pluginFrontConfig['cadd_zipcode']))        ? $this->pluginFrontConfig['cadd_zipcode'] : $preData['cadd_zipcode'];
            $data['cadd_company']           = ($is_submit && !empty($this->pluginFrontConfig['cadd_company']))        ? $this->pluginFrontConfig['cadd_company'] : $preData['cadd_company'];
            $data['cadd_phone_mobile']      = ($is_submit && !empty($this->pluginFrontConfig['cadd_phone_mobile']))   ? $this->pluginFrontConfig['cadd_phone_mobile'] : $preData['cadd_phone_mobile'];
            $data['cadd_phone_landline']    = ($is_submit && !empty($this->pluginFrontConfig['cadd_phone_landline'])) ? $this->pluginFrontConfig['cadd_phone_landline'] : $preData['cadd_phone_landline'];
            $data['cadd_complementary']     = ($is_submit && !empty($this->pluginFrontConfig['cadd_complementary']))  ? $this->pluginFrontConfig['cadd_complementary'] : $preData['cadd_complementary'];
            $data['cadd_id']                = ($is_submit && !empty($this->pluginFrontConfig['cadd_id']))             ? $this->pluginFrontConfig['cadd_id'] : $preData['cadd_id'];

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
                    
                    /**
                     * Retrieving client person data from Commerce Authentication Service
                     */
                    $data['cadd_civility']      = $melisComAuthSrv->getClientPersonSessDataByField('cper_civility');
                    $data['cadd_name']          = $melisComAuthSrv->getClientPersonSessDataByField('cper_name');
                    $data['cadd_middle_name']   = $melisComAuthSrv->getClientPersonSessDataByField('cper_middle_name');
                    $data['cadd_firstname']     = $melisComAuthSrv->getClientPersonSessDataByField('cper_firstname');
                    
                    // Saving Client person delivery addrress
                    $deliveryAddressId = $clientSrv->saveClientAddress($data, $deliveryAddressId);
                    
                    if (!is_null($deliveryAddressId))
                    {
                        $success = 1;
                        $message = 'Delivery address has been successfully saved';
                    }
                    else
                    {
                        $message = 'Something is wrong, please contact administrator for assistance';
                    }
                }
                else 
                {
                    $message = 'Error(s) occured';
                    $errors  = $deliveryAddress->getMessages();
                }
            }
            else {

            }
        }
        else
        {
            // Gettin the Redirect Uri from config
            $m_redirection_link_not_loggedin = (!empty($this->pluginFrontConfig['redirection_link_not_loggedin'])) ? $this->pluginFrontConfig['redirection_link_not_loggedin'] : 'http://www.test.com';
            
            $controller = $this->getController();
            $redirector = $controller->getPluginManager()->get('Redirect');
            $redirector->toUrl($m_redirection_link_not_loggedin);
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
    
    /**
     * This function return the back office rendering for the template edition system
     * TODO
     */
    public function back()
    {
        return array();
    }
}
