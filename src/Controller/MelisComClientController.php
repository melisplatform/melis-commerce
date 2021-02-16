<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;
use Laminas\View\Model\JsonModel;
use Laminas\Stdlib\ArrayUtils;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComClientController extends MelisAbstractActionController
{
    const PLUGIN_INDEX = 'meliscommerce';
    
    /**
     * Render Client Page
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * This method will return the Client first Person Name
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function getClientContactNameAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_clients_get_contact_name');
        $textMessage = $translator->translate('tr_meliscore_error_message');;
        $errors = array();
        
        $clientContactName = '';
        
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $postValues = $request->getPost()->toArray();
            
            if (!empty($postValues['clientId']))
            {
                $clientId = $postValues['clientId'];
                // Getting Client Data from Client Service
                $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
                $clientData = $melisComClientService->getClientByIdAndClientPerson($clientId);
                // Getting data from Client object
                $clientPerson = $clientData->getPersons();
                
                if (!empty($clientPerson))
                {
                    foreach ($clientPerson As $row)
                    {
                        // First Person would use as default Name of the client
                        $clientContactName = $row->cper_firstname.' '.$row->cper_name;
                        break;
                    }
                    $success = 1;
                }
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'clientId' => $textTitle,
            'clientContactName' => $clientContactName,
            'errors' => $errors,
        );
        
        return new JsonModel($response);
    }
    
    /**
     * Render Client Page Header
     * This method return also the Title of the Page
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        $translator = $this->getServiceManager()->get('translator');
        
        $title = $translator->translate('tr_meliscommerce_clients_add_client');
        
        if ($clientId)
        {
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $clientData = $melisComClientService->getClientByIdAndClientPerson($clientId);
            
            $clientPerson = $clientData->getPersons();
            
            $mainContactName = '';
            
            if (!empty($clientPerson))
            {
                foreach ($clientPerson As $row)
                {
                    $mainContactName = $row->cper_firstname.' '.$row->cper_name;
                    break;
                }
                $success = 1;
                
                $clientCompany = $clientData->getCompany();
                $comapanyName = '';
                
                if (!empty($clientCompany))
                {
                    if (!empty($clientCompany[0]->ccomp_name))
                    {
                        $comapanyName = ' ('.$clientCompany[0]->ccomp_name.')';
                    }
                }
                
                $title = $translator->translate('tr_meliscommerce_clients_common_label_client').' / '.$mainContactName.$comapanyName;
            }
        }
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        $view->title = $title;
        return $view;
    }
    
    /**
     * Render Client Page Content
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Page Header Button Save
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageSaveAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tabulation Container
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabMainAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tab Main Header
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabMainHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tab Main Content
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabMainContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tab Main Content Left Zone
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabMainContentLeftAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tab Main Content Right Zone
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabMainContentRightAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tab Contact Content
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabContactAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tab Contact Header
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabContactHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Contact header button Add Contact
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientAddContactAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client tab Contact Content
     * This method return Contact Form and Addresses Form
     *  
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabContactContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        $clientPerson = array();
        if (!empty($clientId))
        {
            // Getting the Client Data from Service
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $clientPerson = $melisComClientService->getClientByIdAndClientPerson($clientId);
            // Getting Client Person form Client Object
            $clientPerson = $clientPerson->getPersons();
            $this->placeMainEmailFirstInList($clientPerson);
            
            // Preparing Form for Contact and Addresses Forms
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            
            // Melis Core Config Service Manager
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            
            // Getting Client Contact Form from Config using CoreConfig
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
            $propertyForm = $factory->createForm($appConfigForm);
            $view->setVariable('meliscommerce_clients_contact_form', $propertyForm);
            
            // Getting Client Address Form from Config using CoreConfig
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
            $propertyForm = $factory->createForm($appConfigForm);
            $view->setVariable('meliscommerce_clients_addresses_form', $propertyForm);
        }
        
        $view->clientPerson = $clientPerson;
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Company Tab
     * This method return Client Company Form from Config
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabCompanyAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_company_form','meliscommerce_clients_company_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        if (!empty($clientId))
        {
            // Getting Client Data for Company and set/bind to Company Form
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $client = $melisComClientService->getClientByIdAndClientPerson($clientId);
            // Getting Company from Client Object
            $clientCompany = $client->getCompany();
            
            if (!empty($clientCompany))
            {
                $propertyForm->bind($clientCompany[0]);
            }
        }
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        $view->setVariable('meliscommerce_clients_company_form', $propertyForm);
        return $view;
    }
    
    /**
     * Render Client Tab Addresses
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabAddressAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Addresses Header
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabAddressHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Addresses Content
     * This method return Client Address Form if there is availabel data base on ClientId requested
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabAddressContentAction()
    {
        $view = new ViewModel();
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        $clientAddresses = array();
        
        if (!empty($clientId))
        {
            // Getting Client Data form Client Service
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $client = $melisComClientService->getClientByIdAndClientPerson($clientId);
            // Getting Company Data from Client Object
            $clientAddresses = $client->getAddresses();
            
            // Getting Client Addres from from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);
            
            $view->setVariable('meliscommerce_clients_addresses_form', $propertyForm);
        }
        
        $view->clientAddresses = (!empty($clientAddresses)) ? $clientAddresses : array();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Address Header Button Add Address
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientAddAddressAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Tab Order Content
     * This method return Order List table from Tool Config
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientPageTabOrdersAction()
    {
        $status = array();
        $orderStatusTable = $this->getServiceManager()->get('MelisEcomOrderStatusTable');
        foreach($orderStatusTable->fetchAll() as $orderStatus){
            $status[] = $orderStatus;
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $activateTab = $this->params()->fromQuery('activateTab');
        
        $translator = $this->getServiceManager()->get('translator');
        
        // Getting Client Order List Table Config
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_client_order_list');
        // Getting the Columns of the table
        $columns = $melisTool->getColumns();
        // Setting the label transalated for Action column
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_clients_common_label_action'));
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$clientId.'_tableClientOrderList', true, null, array('order' => '[[ 0, "desc" ]]'));
        $view->activateTab = ($activateTab) ? 'active' : '';
        $view->status = $status;
        return $view;
    }
    
    /**
     * Render Client Order Table Limit filter
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientTableLimitAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Order Table Serach input filter
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientTableSearchAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Order Table Refresh Button
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientTableRefreshAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Order table Action View
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientTableViewAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Status 
     * This method return Client data to be fillup to Client Status switch
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientStatusAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        $status = '';
        if (!empty($clientId))
        {
            // Getting Client Data from Client Service
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $clieData = $melisComClientService->getClientByIdAndClientPerson($clientId);
            // Getting Client data form Client Object
            $client = $clieData->getClient();
            // Assigning Data to init the Client Status Swithc plugin
            $status = ($client->cli_status) ? 'checked' : '';
        }
        
        $view = new ViewModel();
        $view->status = $status;
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render CLient Main Contact view
     * This method return Client Data base on request
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientMainContactAction()
    {
        $view = new ViewModel();
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        $clientPerson = array();
        
        if (!empty($clientId))
        {
            // Getting Current Langauge ID
            $melisTool = $this->getServiceManager()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();
            
            // Getting Client Data from Service
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $clientMainPerson = $melisComClientService->getClientMainPersonByClientId($clientId, $langId);
            // Getting Persion Data form Client Object
            $clientPerson = $clientMainPerson->getPerson();
        }
        
        $view->clientPerson = $clientPerson;
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        return $view;
    }
    
    /**
     * Render Client Main Form
     * This method return Client Form forom Config
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientMainFormAction()
    {
        // Grtting Client Form From Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_main_form','meliscommerce_clients_main_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');
        
        if (!empty($clientId))
        {
            
            // Getting Client Data from Client Service
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $clieData = $melisComClientService->getClientByIdAndClientPerson($clientId);
            // Getting Client data form Client Object
            $client = $clieData->getClient();
            
            if (!empty($client))
            {
                // Binding Client data to Client Form
                $propertyForm->bind($client);
            }
        }
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        $view->setVariable('meliscommerce_clients_main_form', $propertyForm);
        return $view;
    }
    
    /**
     * This method validate Client Contact Form and Add to the Client Contact list
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function addClientContactAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_clients_add_contact');
        $textMessage = '';
        $errors = array();
        
        $clientContactDom = array();
        
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            // Getting Client Contact Form from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);
            $clientSvc = $this->getServiceManager()->get('MelisComClientService');
            
            // Getting the Elements/fields of the Client COntact form
            $appConfigFormElements = $appConfigForm['elements'];
            
            // Gtting Data from Post and Set as values to the Client Contact Form
            $postValues = $request->getPost()->toArray();
            $propertyForm->setData($postValues);
            $emailList = explode(',', $postValues['emailList']);

            if (! empty($postValues['cper_id'])) {
                // email checking
                if (! empty($postValues['cper_email'])) {
                    // check if email is not used twice
                    $personWithSameEmail = $clientSvc->getPersonsByEmail($postValues['cper_email']);

                    foreach ($personWithSameEmail as $mail) {
                        if ($mail['cpmail_cper_id'] != $postValues['cper_id']) {
                            $errors['cper_email'] = [
                                'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                                'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                            ];
                        }
                    }
                }
            } else {
                if (! empty($postValues['cper_email'])) {
                    // check if email is not used twice
                    if (! in_array($postValues['cper_email'], $emailList)) {
                        $emailList[] = $postValues['cper_email'];
                        $personWithSameEmail = $clientSvc->getPersonsByEmail($postValues['cper_email']);

                        if (! empty($personWithSameEmail)) {
                            $errors['cper_email'] = [
                                'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                                'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                            ];
                        }
                    } else {
                        $errors['cper_email'] = [
                            'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                            'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                        ];
                    }
                }
            }
            
            // Cehcking if the Client Contact form is valid after set the Data from Post
            if ($propertyForm->isValid())
            {
                // Getting Validated Data from Client Contact Form
                $data = $propertyForm->getData();
                
                // Encrypting datetime as unique ID for tabulation Navigation
                $tabId = md5(date('YmdHis'));
                
                // Generating tabulation navigation for to new Added Client Contact
                $tabNav = '<li class="'.$tabId.'_client_contact">
                    			<a class="clearfix" data-toggle="tab" id="nav_'.$tabId.'" href="#'.$tabId.'" aria-expanded="false">
                    				<span>
                    			        '.$data['cper_firstname'].' '.$data['cper_name'].'
                				        <label class="label label-success">'.$translator->translate('tr_meliscommerce_clients_common_label_new').'</label>
                				    </span>
            			            <i class="fa fa-times deleteClientContactAddress" data-tabclass="'.$tabId.'" ></i>
                    			</a>
                    		</li>';
                
                // Getting Client Contact Tabulation Content from dispatcher,
                // Return would be in HTML code and return as ajax request
                $dispatchHandler = array(
                    'module' => 'MelisCommerce',
                    'controller' => 'MelisComClient',
                    'action' => 'render-client-contact-tab-content'
                );
                $melisTool = $this->getServiceManager()->get('MelisCoreTool');
                $tabContent = $melisTool->getViewContent($dispatchHandler);
                
                // Assigning New Client Contact data to one array container
                $clientContactDom = array(
                    'tabId' => $tabId,
                    'tabNav' => $tabNav,
                    'tabContent' => $tabContent,
                );
                
                $success = 1;
            }
            else 
            {
                $textMessage = $translator->translate('tr_meliscommerce_client_Contact_unable_to_add');
                $errors = ArrayUtils::merge($errors, $propertyForm->getMessages());
            }
            
            // Preparing the error messages if error is occured
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigFormElements as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                    {
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
            }
        }
        
        if(!empty($errors)){
            $success = 0;
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'clientContactDom' => $clientContactDom
        );
        
        return new JsonModel($response);
    }
    
    /**
     * Render Client Contact Tab Content
     * This method return Client Contact form with binded data form Post 
     * this method is requested through dispatch
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientContactTabContentAction()
    {
        // Getting Validated Data from Client Contact Form
        $tabId = md5(date('YmdHis'));
        
        $clientId = '';
        
        // Getting Data form Post
        $request = $this->getRequest();
        
        $translator = $this->getServiceManager()->get('translator');
        
        // Getting Client Contact Form form Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        if ($request->getPost())
        {
            $postValues = $request->getPost()->toArray();
            $propertyForm->setData($postValues);
            // Client Id
            $clientId = $postValues['clientId'];
        }
        
        $view = new ViewModel();
        $view->clientId = $clientId;
        $view->tabId = $tabId;
        $view->setVariable('meliscommerce_clients_contact_form', $propertyForm);
        return $view;
    }
    
    /**
     * This method add Client Address to Client Content
     * This will also validate Data assign to Client Address and return Form with validated datas
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function addClientContactAddressAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_clients_add_contact_address');
        $textMessage = '';
        $errors = array();
        
        $clientContactAddressDom = array();
        
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            // Geting Client Address from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);

            // Getting Data from Post and set data to Client Address Form
            $postValues = $request->getPost()->toArray();
            $propertyForm->setData($postValues);
            // Getting Client Address form elements/fields
            $appConfigFormElements = $appConfigForm['elements'];
        
            // Checking if Data setted to Form is valid
            if ($propertyForm->isValid())
            {
                // Getting Validated Data from Form
                $data = $propertyForm->getData();
                
                // Encrypting dattime as unique Id
                $contactAddressId = md5(date('YmdHis'));
                
                $clientId = $postValues['clientId'];
                $tabId = $postValues['tabId'];
            
                // Getting Client Contact Tabulation Content from dispatcher,
                // Return would be in HTML code and return as ajax request
                $dispatchHandler = array(
                    'module' => 'MelisCommerce',
                    'controller' => 'MelisComClient',
                    'action' => 'render-client-contact-address-accordion-content'
                );
                $melisTool = $this->getServiceManager()->get('MelisCoreTool');
                $accordionContent = $melisTool->getViewContent($dispatchHandler);
                
                // Assigning New Address data to one array container
                $clientContactAddressDom = array(
                    'tabId' => $tabId,
                    'contactAddressId' => $contactAddressId,
                    'accordionContent' => $accordionContent,
                );
                
                $success = 1;
            }
            else
            {
                $textMessage = $translator->translate('tr_meliscommerce_client_Contact_unable_to_add_address');
                $errors = $propertyForm->getMessages();
            }
            
            // Preparing Error message if error occured
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigFormElements as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                    {
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'clientContactAddressDom' => $clientContactAddressDom,
        );
        
        return new JsonModel($response);
    }
    
    /**
     * Render Client Contact Address in Accordion Contect
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientContactAddressAccordionContentAction()
    {
        // Encrypting dattime as unique Id
        $contactAddressId = md5(date('YmdHis'));
        
        // Getting values
        $request = $this->getRequest();
        $postValues = $request->getPost()->toArray();
            
        $translator = $this->getServiceManager()->get('translator');
        
        // Getting Client Contact Address form from Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        $propertyForm->setData($postValues);
        
        // Adding label "new" to new added Contact Address
        $newAddedLabel = '';
        if (empty($postValues['cadd_id']))
        {
            $newAddedLabel = '<label class="label label-success">'.$translator->translate('tr_meliscommerce_clients_common_label_new').'</label>';
        }
        // Client Id
        $clientId = $postValues['clientId'];
        // Tab Id
        $tabId = $postValues['tabId'];
        // Contact Address Name
        $contactAddressName = $postValues['cadd_address_name'];
        
        $view = new ViewModel();
        $view->newAddedLabel = $newAddedLabel;
        $view->clientId = $clientId;
        $view->tabId = $tabId;
        $view->contactAddressId = $contactAddressId;
        $view->contactAddressName = $contactAddressName;
        $view->setVariable('meliscommerce_clients_addresses_form', $propertyForm);
        return $view;
    }
    
    /**
     * This method validate Datas for Client Address and return as Validated Datas
     * This method also return tabulation for Client Addresses
     *  
     * @return \Laminas\View\Model\JsonModel
     */
    public function addClientAddressAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_clients_add_contact_address');
        $textMessage = '';
        $errors = array();
        
        $clientAddressDom = array();
        
        // Getting Data from Post
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            // Getting Client Address Form from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);
            
            // Getting Posted data in a form of array and set Data to the Client Address form
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            $propertyForm->setData($postValues);
            
            // Gettiing Client Address elements/fields
            $appConfigFormElements = $appConfigForm['elements'];
        
            // Checking if the Data setted to Form is Valid
            if ($propertyForm->isValid())
            {
                // Getting Data from From as Validated Datas
                $data = $propertyForm->getData();
                
                // Encrypting dattime as unique Id
                $addressId = md5(date('YmdHis'));
        
                $clientId = $postValues['clientId'];
                $addressName = $postValues['cadd_address_name'];
                
                // Generating tabulation navigation for to new Added Client Contact
                $tabNav = '<li class="'.$addressId.'_address">
                    			<a class="clearfix" data-toggle="tab" id="nav_add_'.$addressId.'" href="#'.$addressId.'_address" aria-expanded="false">
                    				<span>
                    			        '.$addressName.'
                				        <label class="label label-success">'.$translator->translate('tr_meliscommerce_clients_common_label_new').'</label>
                				    </span>
                    				<i class="fa fa-times deleteClientAddress" data-addressid="0" data-tabclass="'.$addressId.'_address"  data-isnewadded="1"></i>
                    			</a>
                    		</li>';
                
                // Assigning New Address data to one array container
                $dispatchHandler = array(
                    'module' => 'MelisCommerce',
                    'controller' => 'MelisComClient',
                    'action' => 'render-client-address-tab-content'
                );
                $melisTool = $this->getServiceManager()->get('MelisCoreTool');
                $tabContent = $melisTool->getViewContent($dispatchHandler);
                
                $clientAddressDom = array(
                    'addressId' => $addressId,
                    'tabNav' => $tabNav,
                    'tabContent' => $tabContent,
                );
        
                $success = 1;
            }
            else
            {
                $textMessage = $translator->translate('tr_meliscommerce_client_Contact_unable_to_add_address');
                $errors = $propertyForm->getMessages();
            }
            
            // Preparing Error message if error occured
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigFormElements as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                    {
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'clientAddressDom' => $clientAddressDom,
        );
        
        return new JsonModel($response);
    }
    
    /**
     * Render Client Address tab Content
     * This method return Client Address form with binded data form Post 
     * this method is requested through dispatch
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientAddressTabContentAction()
    {
        $addressId = md5(date('YmdHis'));
        
        // Getting Data from post in a form of array
        $request = $this->getRequest();
        $postValues = $request->getPost()->toArray();
        
        $translator = $this->getServiceManager()->get('translator');
        
        // Getting Client Address form and Bind Posted data to the form
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        $propertyForm->setData($postValues);
        
        $view = new ViewModel();
        $view->addressId = $addressId;
        $view->setVariable('meliscommerce_clients_addresses_form', $propertyForm);
        return $view;
    }
    
    /**
     * Client Page modal container
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $melisKey = $this->params()->fromQuery('melisKey');
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->id = $id;
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client Contact Form modal contect
     * This method return Client Contact Form
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientModalContactFormAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId');
        $tabId = $this->params()->fromQuery('tabId');
        
        // Getting Client Contact Form from Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);

        $clientTempData = array(
            'cper_status' => 1,
        );
        $propertyForm->setData($clientTempData);
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tabId = $tabId;
        $view->setVariable('meliscommerce_clients_contact_form', $propertyForm);
        $view->clientId = ($clientId) ? $clientId : 0;
        return $view;
    }
    
    /**
     * Render Client Contact Address Form modal
     * This method return Client Contact Address Form
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientModalContactAddressFormAction()
    {
        $translator = $this->getServiceManager()->get('translator');
    
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId');
        $tabId = $this->params()->fromQuery('tabId');
        
        // Getting Client Contact Address Form from Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tabId = $tabId;
        $view->setVariable('meliscommerce_clients_addresses_form', $propertyForm);
        $view->clientId = ($clientId) ? $clientId : 0;
        return $view;
    }
    
    /**
     * Render Client Address Form modal
     * This method return Contact Address Form
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientModalAddressFormAction()
    {
        $translator = $this->getServiceManager()->get('translator');
    
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId');
        
        // Getting Client Address Form from Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
    
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_clients_addresses_form', $propertyForm);
        $view->clientId = ($clientId) ? $clientId : 0;
        return $view;
    }
    
    /**
     * Saving Client Data
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function saveClientAction()
    {
        $success = 0;
        $textTitle = 'tr_meliscommerce_clients_common_label_client';
        $textMessage = '';
        $errors = array();
        $clientId = null;
        $clientContactName = '';
        $logTypeCode = '';
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            // Client Saving Listener 
            $this->getEventManager()->trigger('meliscommerce_clients_save_start', $this, $request);
            
            // Get the MelisCommerce Module session as Data Container after Data Validation from Listener
            $container = new Container('meliscommerce');
            
            if (!empty($container['action-client-tmp']))
            {
                if (!empty($container['action-client-tmp']['errors']))
                {
                    $errors = $container['action-client-tmp']['errors'];
                }
                    
                if (!empty($container['action-client-tmp']['datas']))
                {
                    $datas = $container['action-client-tmp']['datas'];
                }
            }
            
            // Unset Temporary Data on Session
            unset($container['action-client-tmp']);
            // Getting Datas validated
            $clientData = $this->getTool()->sanitizeRecursive($datas['client']);
            $contactsData = $this->getTool()->sanitizeRecursive($datas['clientContacts']);
            $companyData = $this->getTool()->sanitizeRecursive($datas['clientCompany']);
            $addressesData = $this->getTool()->sanitizeRecursive($datas['clientAddresses']);
            
            // Getting Data from Post in array form
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);


            for($i = 0; $i < count($contactsData); $i++) {
                $contactsData[$i]['reset_pass_flag'] = true;
            }

            $clientId = $postValues['clientId'];
            
            if (!empty($postValues['clientId']))
            {
                $logTypeCode = 'ECOM_CLIENT_UPDATE';
            }
            else
            {
                $logTypeCode = 'ECOM_CLIENT_ADD';
            }
             
            // Cehcking if error occured during validation of submitted datas
            if (empty($errors)){
                // Saving Client data using Client Servive
                $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
                $clientId = $melisComClientService->saveClient($clientData, $contactsData, $addressesData, $companyData, $clientId);
                // Checking if Saving of datas is success if the return is not null
                // return should be the Client Id
                if (!is_null($clientId))
                {
                    $textMessage = 'tr_meliscommerce_client_save_success';
                    // Getting Client Data
                    $client = $melisComClientService->getClientByIdAndClientPerson($clientId);
                    $clientPerson = $client->getPersons();
                    
                    if (!empty($clientPerson))
                    {
                        foreach ($clientPerson As $row)
                        {
                            // returning the Client firm contact as title tab of the page
                            $clientContactName = $row->cper_firstname.' '.$row->cper_name;
                            break;
                        }
                        $success = 1;
                    }
                    $success = 1;
                }
                else
                {
                    $textMessage = 'tr_meliscore_error_message';
                }
            }else{
                $textMessage = 'tr_meliscommerce_client_unable_to_save';
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'clientId' => $clientId,
            'clientContactName' => $clientContactName
        );
        
        $this->getEventManager()->trigger('meliscommerce_clients_save_end',
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $clientId)));
        
        return new JsonModel($response);
    }
    
    /**
     * Validating Client data from Posted Datas and return as validated Data
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function validateClientAction()
    {
        $success = 0;
        $errors = array();
        
        $request = $this->getRequest();
        
        $clientData = array();
        
        if($request->isPost())
        {
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            // Getting Client Form from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_main_form','meliscommerce_clients_main_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);
            // Adding Client Status as New field of Client Form
            // This will be the reciever of the Data of the Client Status which is not part of the Form
            $propertyForm->add(array(
                'name' => 'cli_status',
            ));
            
            // Setting the Posted Data to Form as Form Data
            $propertyForm->setData($postValues);
            
            // Checking if Form with datas is Valid
            if ($propertyForm->isValid())
            {
                // Getting validated Data from Form
                $clientData = $propertyForm->getData();
            }
            else 
            {
                // Getting Form errors if errors is occured
                $errors = $propertyForm->getMessages();
            }
            
            // Getting From Elements/fields
            $appConfigFormElements = $appConfigForm['elements'];
            
            // Preparing Error messages 
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigFormElements as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                    {
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
            }
        }
        
        if (empty($errors)){
            $success = 1;
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('client_err' => $errors),
            'datas' => array('client' => $clientData),
        );
        
        return new JsonModel($result);
    }
    
    /**
     * Validating Client Contact Posted Data and return as validated Datas
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function validateClientContactsAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $errors = array();
        $errors_1 = array();
        $errors_2 = array();
        
        $formErrors = array();
        $errors_1_temp = array();
        
        $request = $this->getRequest();
        
        $clientContactsData = array();
        $clientContactsAddressesData = array();
        
        if($request->isPost())
        {
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            
            // Getting Client Conatct Form from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm_1 = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
            $factory_1 = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory_1->setFormElementManager($formElements);
            
            // Getting Client Contact Address Form from Config
            $appConfigForm_2 = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
            $factory_2 = new \Laminas\Form\Factory();
            $factory_2->setFormElementManager($formElements);

            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            
            if (!empty($postValues['clientContacts']))
            {
                $clientContacts = $postValues['clientContacts'];
                
                // Flags for Main Contact
                $hasMainContact = false;
                $findMainContact = false;

                foreach ($clientContacts As $key => $val)
                {
                    // To ensure Client contact has only one main contact
                    if ($hasMainContact)
                    {
                        $val['cper_is_main_person'] = 0;
                    }
                    
                    // Checking if finding new Main contact is activated
                    if ($findMainContact)
                    {
                        // Checking if Contact is Active Status
                        if ($val['cper_status'])
                        {
                            $val['cper_is_main_person'] = 1;
                            // Deactivate Find Main Contact Flag
                            $findMainContact = false;
                        }
                    }
                    
                    // Checking if Contact list has selected the main Contact
                    if ($val['cper_is_main_person'])
                    {
                        if (!$val['cper_status'])
                        {
                            // Deselect as Main Contact if the Status is not Active
                            $val['cper_is_main_person'] = 0;
                            // Activate find Main Contact Flag
                            $findMainContact = true;
                        }
                        else 
                        {
                            $hasMainContact = true;
                        }
                    }
                    
                    // PropertyFrom 1 assign as Client Contact Form
                    $propertyForm_1 = $factory_1->createForm($appConfigForm_1);
                    $propertyForm_1->setData($val);
                    
                    // Checking if Cleint Contact is existing by checking the Primary Id of the Contact
                    if (! empty($val['cper_id'])) {
                        // Checking if the Contact Form has data of the password
                        if (empty($val['cper_password'])) {
                            // If the existing Contact password empty, this means contact not updating the current password
                            // removing Input from Contact form will also remove from the validation
                            $propertyForm_1->getInputFilter()->remove('cper_password');
                            $propertyForm_1->getInputFilter()->remove('cper_confirm_password');
                        }
                        
                        if (! empty($val['cper_email'])) {
                            $personWithSameEmail = $melisComClientService->getPersonsByEmail($val['cper_email']);

                            foreach ($personWithSameEmail as $mail) {
                                if ($mail['cpmail_cper_id'] != $val['cper_id']) {
                                    $errors_1_temp['cper_email'] = array(
                                        'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                                        'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                                    );
                                }
                            }
                        }
                    }
                    
                    // Checking if Contact Form is valid
                    if ($propertyForm_1->isValid()) {
                        if (empty($errors_1_temp)) {
                            $clientContactsDataTemp = $propertyForm_1->getData();
                            // After validation confirm password would remove from final and validated data for contact datas
                            unset($clientContactsDataTemp['cper_confirm_password']);
                            
                            // Client Contact Addresses validations
                            if (!empty($val['contact_address'])) {
                                $clientContactAddresses = $val['contact_address'];
                            
                                foreach ($clientContactAddresses As $akey => $aVal) {
                                    // PropertyFrom 2 assign as Client Contact Address Form
                                    $propertyForm_2 = $factory_2->createForm($appConfigForm_2);
                                    $propertyForm_2->setData($aVal);
                            
                                    // Checking if datas are valid
                                    if ($propertyForm_2->isValid()) {
                                        // Client Contact Validated data added to static index "contact_address" array
                                        $clientContactsDataTemp['contact_address'][] = $propertyForm_2->getData();
                                    } else {
                                        // Getting Client Contact Address Form errors if errors is occured
                                        $errors_2_temp = $propertyForm_2->getMessages();
                                        foreach ($errors_2_temp as $keyError => $valueError) {
                                            $errors_2_temp[$keyError]['form'][] = $akey.'_contact_address_form';
                                        }
                                        $errors_2 = array_merge_recursive($errors_2, $errors_2_temp);
                                    }
                            
                                    // Getting From Elements/fields
                                    $appConfigFormElements_2 = $appConfigForm_2['elements'];
                                    // Preparing Error messages for Client Contact Address
                                    foreach ($errors_2 as $keyError => $valueError) {
                                        foreach ($appConfigFormElements_2 as $keyForm => $valueForm) {
                                            if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])) {
                                                $errors_2[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                            }
                                        }
                                    }
                                }
                            }

                            // Client Contact Details validated datas added to final Array Container
                            array_push($clientContactsData, $clientContactsDataTemp);
                        }
                    } else {
                        // Getting Client Contact Form errors if errors is occured
                        $errors_1_temp = ArrayUtils::merge($errors_1_temp, $propertyForm_1->getMessages());
                        foreach ($errors_1_temp as $keyError => $valueError) {
                            $errors_1_temp[$keyError]['form'][] = $key.'_contact_form';
                        }
                        $errors_1 = array_merge_recursive($errors_1, $errors_1_temp);
                        
                    }
                    
                    if (!empty($errors_1_temp) && empty($errors_1)) {
                        $errors_1 = $errors_1_temp;
                    }
                    
                    // Getting From Elements/fields
                    $appConfigFormElements_1 = $appConfigForm_1['elements'];
                    // Preparing Error messages for Client Contact
                    foreach ($errors_1 as $keyError => $valueError) {
                        foreach ($appConfigFormElements_1 as $keyForm => $valueForm) {
                            if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])) {
                                $errors_1[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                    }
                }
                
                if (! $hasMainContact) {
                    if (!empty($clientContactsData)) {
                        // Client with no main contact will set the first contact to main contact
                        $hasNewRandomMainContact = false;
                        foreach ($clientContactsData As $key => $val) {
                            // Checking if Contact is Active status
                            if ($val['cper_status']) {
                                // Set main contact
                                $clientContactsData[$key]['cper_is_main_person'] = 1;
                                $hasNewRandomMainContact = true;
                            }
                        }
                        
                        if (! $hasNewRandomMainContact) {
                            // This error will occured if no entry of Contact
                            $errors['clientNoMaincontact'] = array(
                                'label' => $translator->translate('tr_meliscommerce_clients_common_label_contact'),
                                'isEmpty' => $translator->translate('tr_meliscommerce_client_Contact_active_must_atleast_one')
                            );
                        }
                    }
                }
            } else {
                // This error will occured if no entry of Contact
                $errors['clientContactEmpty'] = array(
                    'label' => $translator->translate('tr_meliscommerce_clients_common_label_contact'),
                    'isEmpty' => $translator->translate('tr_meliscommerce_client_Contact_must_atleast_one')
                );
            }
        }
        // Merging all error messages
        $errors = array_merge_recursive($errors, $errors_1, $errors_2);
        
        if (empty($errors)) {
            $success = 1;
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('clientContacts_err' => $errors),
            'datas' => array('clientContacts' => $clientContactsData),
        );
        
        return new JsonModel($result);
    }
    
    /**
     * Validating Client Company Data from post and return as Validated Datas
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function validateClientCompanyAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $errors = array();
        
        $request = $this->getRequest();
        
        $clientCompanyData = array();
        
        if($request->isPost())
        {
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            
            // Getting Client Company Form from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_company_form','meliscommerce_clients_company_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);
            // Setting Posted Datas to Form
            $propertyForm->setData($postValues);
            // Getting Form Elements/fields
            $appConfigFormElements = $appConfigForm['elements'];
            
            if ($propertyForm->isvalid())
            {
                // Getting Validated datas from From
                $clientCompanyData = $propertyForm->getData();
                
                // Flag that indicates if Company Name Field is mandatory
                $companyNameRequired = false;
                
                // Fields names that excluded on checkin value
                $excludeFields = array('ccomp_id', 'ccomp_client_id', 'ccomp_name', 'ccomp_date_creation', 'ccomp_date_edit');
                foreach ($appConfigFormElements as $keyForm => $valueForm)
                {
                    // checking if the element name is exist on exclueded fields
                    if (!in_array($valueForm['spec']['name'], $excludeFields))
                    {
                        if (!empty($clientCompanyData[$valueForm['spec']['name']]))
                        {
                            // if other fields has value, then Company Name will flag as Mandatory/Required Field
                            $companyNameRequired = true;
                        }
                    }
                }
                
                if (empty($clientCompanyData['ccomp_name']))
                {
                    if ($companyNameRequired)
                    {
                        // Return Error if Company Name is Flag as Mandatory and no value
                        $errors['ccomp_name'] = array(
                            'label' => $translator->translate('tr_meliscommerce_client_Company_name'),
                            'notEmpty' => $translator->translate('tr_meliscommerce_client_Contact_input_empty')
                        );
                        
                        $clientCompanyData = array();
                    }
                }
            }
            else 
            {
                // Getting Form Errors
                $errors = $propertyForm->getMessages();
            }
            
            // Preparing Errors messages
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigFormElements as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                    {
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
            }
        }
        
        if (empty($errors)){
            $success = 1;
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('clientCompany_err' => $errors),
            'datas' => array('clientCompany' => $clientCompanyData),
        );
        
        return new JsonModel($result);
    }
    
    /**
     * Validating Client Addresses and return Validated Datas
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function validateClientAddressesAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $errors = array();
        
        $request = $this->getRequest();
        
        $clientAddressesData = array();
        
        if($request->isPost())
        {
            // Getting Calient Address From from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            
            // Getting Datas from Post
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            
            if (!empty($postValues['clientAddresses']))
            {
                // Client Addresses Array
                $clientAddresses = $postValues['clientAddresses'];
                
                // Using Loop Client Addresses will validated one by one
                foreach ($clientAddresses As $key => $val)
                {
                    $propertyForm = $factory->createForm($appConfigForm);
                    $propertyForm->setData($val);
                    
                    if ($propertyForm->isValid())
                    {
                        array_push($clientAddressesData, $propertyForm->getData());
                    }
                    else 
                    {
                        // Getting Client Address Form errors if errors is occured
                        $errors_temp = $propertyForm->getMessages();
                        foreach ($errors_temp as $keyError => $valueError)
                        {
                            $errors_temp[$keyError]['form'][] = $key.'_address_form';
                        }
                        $errors = array_merge_recursive($errors, $errors_temp);
                    }
                    
                    // Preparing Errors Messages
                    $appConfigFormElements = $appConfigForm['elements'];
                    
                    foreach ($errors as $keyError => $valueError)
                    {
                        foreach ($appConfigFormElements as $keyForm => $valueForm)
                        {
                            if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                            {
                                $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                    }
                }
            }
        }
        
        if (empty($errors)){
            $success = 1;
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('clientAddresses_err' => $errors),
            'datas' => array('clientAddresses' => $clientAddressesData),
        );
        
        return new JsonModel($result);
    }
    
    /**
     * Deleteing Existing Client Address
     * A hidden form from render and submitted using Post
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function deleteClientAddressesAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $errors = array();
        
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            
            if (!empty($postValues['deletedaddresses']))
            {
                // ID's of the Addresses deleted on the frontent
                $clientAddressesIds = $postValues['deletedaddresses'];
                
                if (is_array($clientAddressesIds))
                {
                    // Deleting one by one by looping and pass to Service to execute deletion of the Address
                    $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
                    foreach ($clientAddressesIds As $val)
                    {
                        $melisComClientService->deleteClientAddress($val);
                    }
                    $success = 1;
                }
                else 
                {
                    // This error return if no form entry of Contact Address
                    $errors['clientAddressIdsInvalid'] = array(
                        'label' => $translator->translate('tr_meliscommerce_clients_delete_contact_address'),
                        'inValidFormat' => $translator->translate('tr_meliscore_error_message')
                    );
                }
            }
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('deleteedClientAddresses_err' => $errors),
            'datas' => array(),
        );
        
        return new JsonModel($result);
    }

    private function getTool()
    {
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        return $tool;
    }

    public function getClientBasketSaveAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientId = $this->params()->fromQuery('clientId', '');

        $view->clientId = $clientId;
        return $view;
    }

    public function deleteClientPersonEmailAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        $success = 0;
        $errors = [];
        $request = $this->getRequest();
        $textMessage = 'tr_meliscommerce_clients_contact_person_email_message_fail';

        if ($request->isPost()) {
            $this->getEventManager()->trigger(
                'meliscommerce_clients_delete_client_person_email_start',
                $this,
                $request
            );

            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $result = $melisComClientService->deleteClientPersonEmail($postValues['cpmail_id']);

            if (! empty($result)) {
                $success = 1;
                $textMessage = 'tr_meliscommerce_clients_contact_person_email_message_success';
            } else {
                $errors[] = $translator->translate('tr_meliscommerce_clients_contact_person_email_message_success');
            }
        }

        $result = [
            'success' => $success,
            'errors' => $errors,
            'datas' => [],
            'textTitle' => 'tr_meliscommerce_clients_contact_person_email',
            'textMessage' => $textMessage,
        ];

        $this->getEventManager()->trigger(
            'meliscommerce_clients_delete_client_person_email_end',
            $this,
            array_merge($result, ['typeCode' => 'ECOM_CLIENT_PERSON_EMAIL_DELETE', 'itemId' => $postValues['cpmail_id']])
        );

        return new JsonModel($result);
    }

    private function placeMainEmailFirstInList (&$clientPersons)
    {
        $mainEmail = [];

        foreach ($clientPersons as $pkey => $person) {
            if (count($person->emails) > 1) {
                foreach ($person->emails as $ekey => $email) {
                    if ($person->cper_email === $email['cpmail_email']) {
                        $mainEmail = $email;
                        unset($person->emails[$ekey]);
                    }
                }

                array_unshift($person->emails, $mainEmail);
            }
        }
    }
}