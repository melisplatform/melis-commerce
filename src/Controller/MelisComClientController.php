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

        //prepare container to use when creating new account(for linking contact)
        $container = new Container('meliscommerce');
        $container['temp-linked-contacts'] = [];

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
    public function getAccountNameAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_clients_get_contact_name');
        $textMessage = $translator->translate('tr_meliscore_error_message');;
        $errors = array();
        
        $accountName = '';
        
        $request = $this->getRequest();
        $accountId = 0;
        
        if($request->isPost())
        {
            $postValues = $request->getPost()->toArray();
            
            if (!empty($postValues['accountId']))
            {
                $accountId = $postValues['accountId'];
                // Getting Client Data from Client Service
                $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
                $clientData = $melisComClientService->getClientById($accountId);

                if (!empty($clientData))
                {
                    $accountName = $clientData->cli_name;
                    $success = 1;
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'accountId' => $accountId,
            'accountName' => $accountName,
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

            $accountData = $clientData->getClient();
            
            if (!empty($accountData))
            {
                $accountName = $accountData->cli_name;

                $clientCompany = $clientData->getCompany();
                $comapanyName = '';
                
                if (!empty($clientCompany))
                {
                    if (!empty($clientCompany[0]->ccomp_name))
                    {
                        $comapanyName = ' ('.$clientCompany[0]->ccomp_name.')';
                    }
                }
                
                $title = $translator->translate('tr_meliscommerce_clients_common_label_client').' / '.$accountName.$comapanyName;
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
        $activateTab = $this->params()->fromQuery('activateTab', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        $view->activateTab = ($activateTab) ? 'active' : '';
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
     * @return ViewModel
     */
    public function renderAccountContactListTableUnlinkAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
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
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_clients_contact_list');

        $clientId = $this->params()->fromQuery('clientId', '');

        // DataTable costume configuration
        $columns = $melisTool->getColumns();
        $translator = $this->getServiceManager()->get('translator');
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_clients_common_label_action'));

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->clientId = $clientId;
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$clientId.'_accountContactList', null, null, array('order' => '[[ 0, "desc" ]]'));
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
        $ccomp_logo = '';
        
        if (!empty($clientId))
        {
            // Getting Client Data for Company and set/bind to Company Form
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $client = $melisComClientService->getClientByIdAndClientPerson($clientId);
            // Getting Company from Client Object
            $clientCompany = $client->getCompany();

            if (! empty($clientCompany[0]->ccomp_logo))
                $ccomp_logo = 'data:image/png;base64,'.base64_encode($clientCompany[0]->ccomp_logo);
            
            if (!empty($clientCompany))
            {
                if (! empty($clientCompany[0]->ccomp_comp_creation_date)) {
                    // format the company creation date
                    $companyCreationDate = \DateTime::createFromFormat('Y-m-d', $clientCompany[0]->ccomp_comp_creation_date);
                    $clientCompany[0]->ccomp_comp_creation_date = $companyCreationDate->format('m/d/Y');
                }

                $propertyForm->bind($clientCompany[0]);
            }
        }

        // initialize date picker
        $datePickerInit = $this->getTool()->datePickerInit('client_company_creation_date');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        $view->setVariable('meliscommerce_clients_company_form', $propertyForm);
        $view->datePickerInit = $datePickerInit;
        $view->ccomp_logo = $ccomp_logo;
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
     * @return ViewModel
     */
    public function renderAccountContactListTableRefreshAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $accountId = $this->params()->fromQuery('clientId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->accountId = $accountId;
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

            //check account settings
            $settings = $melisComClientService->getAccountNameSetting();
            if(!empty($settings)){
                if($settings->sa_type == 'company_name' || $settings->sa_type == 'contact_name'){
                    $propertyForm->get('cli_name')->setAttribute('disabled', true);
                }
            }
        }
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $clientId;
        $view->setVariable('meliscommerce_clients_main_form', $propertyForm);
        return $view;
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
        $clientName = '';
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

            /**
             * Contacts data is already in separate tool
             * we saved it as a linked only between client and contact
             */
            $contactsData = $datas['clientContacts'];

            $companyData = $this->getTool()->sanitizeRecursive($datas['clientCompany']);
            // reapply company logo data because we can't use the sanitized one
            if (! empty($datas['clientCompany']['ccomp_logo']))
                $companyData['ccomp_logo'] = $datas['clientCompany']['ccomp_logo'];
            $addressesData = $this->getTool()->sanitizeRecursive($datas['clientAddresses']);
            
            // Getting Data from Post in array form
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);


//            for($i = 0; $i < count($contactsData); $i++) {
//                $contactsData[$i]['reset_pass_flag'] = true;
//            }

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
                    $client = $client->getClient();

                    if (!empty($client))
                    {
                        // returning the Client name as title tab of the page
                        $clientName = $client->cli_name;
                        $success = 1;
                    }
                    $success = 1;
                    //remove session for linked contacts
                    unset($container['temp-linked-contacts']);
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
            'clientName' => $clientName
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

            /**
             * Check account settings for form validation
             */
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            //check account settings
            $settings = $melisComClientService->getAccountNameSetting();
            if(!empty($settings)){
                if($settings->sa_type == 'company_name' || $settings->sa_type == 'contact_name'){
                    $propertyForm->getInputFilter()->remove('cli_name');
                    $propertyForm->remove('cli_name');
                }
            }

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

        $request = $this->getRequest();
        $clientContactsData = [];
        
        if($request->isPost())
        {
            $postValues = $request->getPost()->toArray();
            //we validate only contact if account is new
            if(empty($postValues['clientId'])) {
                $container = new Container('meliscommerce');
                if (!empty($container['temp-linked-contacts'])) {
                    $clientContactsData = $container['temp-linked-contacts'];
                } else {
                    // This error will occured if no entry of Contact
                    $errors['clientContactEmpty'] = array(
                        'label' => $translator->translate('tr_meliscommerce_clients_common_label_contact'),
                        'isEmpty' => $translator->translate('tr_meliscommerce_client_Contact_must_atleast_one')
                    );
                }
            }
        }

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
            $postValues = array_merge($postValues, $this->params()->fromFiles());
            if (! empty($postValues['ccomp_logo']['tmp_name']))
                $companyLogoTmpPath = $postValues['ccomp_logo']['tmp_name'];
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            // reapply company logo data because we can't use the sanitized one
            if (! empty($companyLogoTmpPath))
                $postValues['ccomp_logo']['tmp_name'] = $companyLogoTmpPath;
            
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
            
            if ($propertyForm->isvalid()) {
                // Getting Validated datas from From
                $clientCompanyData = $propertyForm->getData();

                if (! empty($clientCompanyData['ccomp_comp_creation_date'])) {
                    // Format company creation date
                    $companyCreationDate = \DateTime::createFromFormat('m/d/Y', $clientCompanyData['ccomp_comp_creation_date']);
                    $clientCompanyData['ccomp_comp_creation_date'] = $companyCreationDate->format('Y-m-d');
                }

                // convert company logo to blob
                if (! empty($companyLogoTmpPath))
                    $clientCompanyData['ccomp_logo'] = file_get_contents($companyLogoTmpPath);
                
                // Flag that indicates if Company Name Field is mandatory
                $companyNameRequired = false;

                // clean data
                foreach ($clientCompanyData as $key => $value) {
                    if (empty($value))
                        unset($clientCompanyData[$key]);
                }
                
                // Fields names that excluded on checkin value
                $excludeFields = array('ccomp_id', 'ccomp_client_id', 'ccomp_name', 'ccomp_date_creation', 'ccomp_date_edit');
                foreach ($appConfigFormElements as $keyForm => $valueForm) {
                    // checking if the element name is exist on exclueded fields
                    if (!in_array($valueForm['spec']['name'], $excludeFields)) {
                        if (!empty($clientCompanyData[$valueForm['spec']['name']])) {
                            // if other fields has value, then Company Name will flag as Mandatory/Required Field
                            $companyNameRequired = true;
                        }
                    }
                }
                
                if (empty($clientCompanyData['ccomp_name'])) {
                    if ($companyNameRequired) {
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

            $postValues = $this->getRequest()->getPost()->toArray();
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

    public function renderClientPageTabFilesAction()
    {
        $clientId = $this->params()->fromQuery('clientId', '');
        $activeTab = $this->params()->fromQuery('activateTab');

        $container = new Container('meliscommerce');
        $container['documents'] = array('docRelationType' => 'client', 'docRelationId' => $clientId);

        $view = new ViewModel();
        $view->activeTab = ($activeTab) ? 'active' : '';
        $view->clientId = $clientId;

        return $view;
    }

    /**
     * Function to delete account
     *
     * @return JsonModel
     */
    public function deleteAccountAction()
    {
        $accountId = $this->getRequest()->getPost('accountId', '');
        $success = 0;
        $error = [];
        $title = 'tr_meliscommerce_client_delete_account';
        $message = 'tr_meliscommerce_client_delete_account_failed';

        $translator = $this->getServiceManager()->get('translator');
        $clientService = $this->getServiceManager()->get('MelisComClientService');
        if($this->request->isPost()){
            $res = $clientService->physicallyDeleteAccount($accountId);
            if($res){
                $success = 1;
                $message = 'tr_meliscommerce_client_delete_account_success';
            }
        }

        return new JsonModel([
            'success' => $success,
            'error' => $error,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message)
        ]);
    }

    /**
     * @return JsonModel
     */
    public function linkAccountContactAction()
    {
        $accountId = $this->getRequest()->getPost('accountId', '');
        $contactId = $this->getRequest()->getPost('contactId', '');

        $success = 0;
        $error = [];
        $title = 'tr_meliscommerce_client_link_contact';
        $message = 'tr_meliscommerce_client_link_contact_failed';

        $translator = $this->getServiceManager()->get('translator');
        $contactService = $this->getServiceManager()->get('MelisComContactService');
        if($this->request->isPost()){
            if(!empty($contactId)) {
                if(!empty($accountId)) {//insert new linked contact
                    $res = $contactService->linkAccountContact(['cpr_client_id' => $accountId, 'cpr_client_person_id' => $contactId]);
                    if ($res) {
                        $success = 1;
                        $message = 'tr_meliscommerce_client_link_contact_success';
                    }
                }else{//temp store contact to session
                    $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
                    $data = $melisEcomClientPersonTable->getEntryById($contactId)->toArray();
                    if(!empty($data)) {
                        $container = new Container('meliscommerce');

                        //remove unneccessary fields
                        unset($data[0]['cper_password']);
                        unset($data[0]['cper_password_recovery_key']);
                        unset($data[0]['cper_date_creation']);
                        unset($data[0]['cper_date_edit']);

                        //set row id
                        $data[0]['DT_RowId'] = $data[0]['cper_id'];
                        $container['temp-linked-contacts'][] = $data[0];

                        $success = 1;
                        $message = 'tr_meliscommerce_client_link_contact_success';
                    }
                }
            }
        }

        return new JsonModel([
            'success' => $success,
            'accountId' => $accountId,
            'error' => $error,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message)
        ]);
    }

    /**
     * Function to get contact lists
     * Used in contact lists inside the account tool
     *
     * @return JsonModel
     */
    public function getAccountContactListAction()
    {
        $dataCount = 0;
        $draw = 0;
        $tableData = array();

        if($this->getRequest()->isPost())
        {
            $accountId = $this->getRequest()->getPost('clientId');
            if(!empty($accountId)) {
                $defaultAccountOnly = false;

                $melisTool = $this->getServiceManager()->get('MelisCoreTool');
                $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_contact_list');

                $colId = array_keys($melisTool->getColumns());

                $sortOrder = $this->getRequest()->getPost('order');
                $sortOrder = $sortOrder[0]['dir'];

                $selCol = $this->getRequest()->getPost('order');
                $selCol = $colId[$selCol[0]['column']];

                $draw = $this->getRequest()->getPost('draw');

                $start = $this->getRequest()->getPost('start');
                $length = $this->getRequest()->getPost('length');

                $search = $this->getRequest()->getPost('search');
                $search = $search['value'];

                $contactService = $this->getServiceManager()->get('MelisComContactService');

                $tableData = $contactService->getContactLists($accountId, $search, $melisTool->getSearchableColumns(), $start, $length, $selCol, $sortOrder, $defaultAccountOnly)->toArray();
                $dataCount = $contactService->getContactLists($accountId, $search, $melisTool->getSearchableColumns(), null, null, null, 'ASC', $defaultAccountOnly, true)->current();
                $dataCount = $dataCount->totalRecords;

            }else{
                $container = new Container('meliscommerce');
                $tableData = $container['temp-linked-contacts'];
                $dataCount = count($tableData);
            }

            $contactStatus = '<i class="fa fa-circle text-danger"></i>';
            foreach ($tableData as $key => $val)
            {
                // Generating contact status html form
                if ($val['cper_status'])
                {
                    $contactStatus = '<i class="fa fa-circle text-success"></i>';
                }

                $tableData[$key]['cper_status'] = $contactStatus;
                $tableData[$key]['DT_RowAttr']    = ['data-accountid' => $accountId];
            }
        }
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($tableData),
            'recordsFiltered' => $dataCount,
            'data' => $tableData,
        ));
    }

    /**
     * @return JsonModel
     */
    public function validateAccountsImportsFormAction()
    {
        $success = 0;
        $errors = [];
        $message = '';

        $translator = $this->getServiceManager()->get('translator');
        $request = $this->getRequest();

        //merge the file for validation
        $postData = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_client_list_import_accounts_form','meliscommerce_client_list_import_accounts_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $importsForm = $factory->createForm($appConfigForm);

        //set data to the form
        $importsForm->setData($postData);

        if ($importsForm->isValid()){
            $success = 1;
        }else{
            $errors = $importsForm->getMessages();
            $appConfigForm = $appConfigForm['elements'];

            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigForm as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError &&
                        !empty($valueForm['spec']['options']['label']))
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }

        $result = [
            'title' => $translator->translate('tr_meliscommerce_contact_import_title'),
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
        ];

        return new JsonModel($result);
    }

    public function importAccountsAction()
    {
        $success = 0;
        $message = 'tr_meliscommerce_accounts_import';
        $title = 'tr_meliscommerce_contact_import_title';
        $errors = [];
        $request = $this->getRequest();
        $defaultDelimiter = ';';
        $translator = $this->getServiceManager()->get('translator');

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $accountService = $this->getServiceManager()->get('MelisComClientService');

            $delimiter = !empty($post['separator']) ? $post['separator'] : $defaultDelimiter;

            $file = $this->params()->fromFiles('account_file');
            $fileContents = $this->readImportedCsv($file);

            $result = $accountService->importFileValidator($fileContents, $delimiter);

            if (empty($result['errors'])) {
                //execute saving records with transactions
                $adapter = $this->getServiceManager()->get('Laminas\Db\Adapter\Adapter');
                $con = $adapter->getDriver()->getConnection();//get db driver connection
                $con->beginTransaction();//begin transaction
                try{
                    $accountService->importAccounts($fileContents, $post, $delimiter);
                    $con->commit();
                    $success = 1;
                    $message = 'tr_meliscommerce_accounts_import_success';
                }catch (\Exception $ex){
                    $success = 0;
                    $message = 'tr_meliscommerce_accounts_import_failed';
                    $con->rollback();
                }
            } else
                $errors = $result['errors'];
        }

        $response = [
            'success' => $success,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message),
            'errors' => $errors,
            'typeCode' => 'IMPORT_ACCOUNTS'
        ];

        return new JsonModel($response);
    }

    /**
     * Reads the imported CSV file
     * Returns null if the file's encoding is not in UTF-8
     * @param null $fileParameters
     * @return array|bool|null|string
     */
    private function readImportedCsv($fileParameters = null)
    {
        $data = array();

        if (!empty($fileParameters['tmp_name'])) {
            $data = file_get_contents($fileParameters['tmp_name']);
            //encode data to utf
            $data = utf8_encode($data);
            if (!mb_check_encoding($data, 'UTF-8')) {
                $data = null;   // NOT IN UTF-8
            }
        }

        return $data;
    }
}