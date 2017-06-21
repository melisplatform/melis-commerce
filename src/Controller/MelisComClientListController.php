<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Http\Response;

class MelisComClientListController extends AbstractActionController
{
    const PLUGIN_INDEX = 'meliscommerce';
    
    /**
     * Render Client List Page
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client List Header
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client Add Client Button
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListAddClientAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render client widgets container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListWidgetsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the client list page client count widget
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListWidgetsNumClientsAction()
    {
        $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
        $clientCount = $clientSvc->getClientList();
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = count($clientCount);
        return $view;
    }
    
    /**
     * renders the client list page monthly clients
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListWidgetsMonthClientsAction()
    {
        $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
        $clientCount = $clientSvc->getWidgetClients('curMonth');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = $clientCount;
        return $view;
    }
    
    /**
     * renders the client list page average client count
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListWidgetsAvgClientsAction()
    {
        $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
        $clientCount = $clientSvc->getWidgetClients('avgMonth');
    
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = (float) $clientCount['average'];
        return $view;
    }
    
    /**
     * Render Client List Content
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client List Table, This will generate DataTable plugin for listing of Clients
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListTableAction()
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_clients_list');
        
        // DataTable costume configuration
        $columns = $melisTool->getColumns();
        $translator = $this->serviceLocator->get('translator');
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_clients_common_label_action'));
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration(null, null, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /**
     * Render Client List custom Table Limit dropdown
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListTableLimitAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client List Custom Table Search input
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListTableSearchAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client custom page refresh button, this button attach to table plugin
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListTableExportAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client custom page refresh button, this button attach to table plugin
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListTableRefreshAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Client List view button for client info
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListTableViewAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the client list modal container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderClientListModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(false);
        return $view;
    }
    
    public function renderClientListContentExportFormAction()
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $datepickerInit = $melisTool->datePickerInit('date_start');
        $datepickerInit .= $melisTool->datePickerInit('date_end');
        
        $view = new ViewModel();
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_client_list_export_form','meliscommerce_client_list_export_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $clientExportForm = $factory->createForm($appConfigForm);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->datePickerInit = $datepickerInit;
        $view->setVariable('meliscommerce_client_list_export_form', $clientExportForm);
        return $view;
    }
    
    /**
     * This method will return the List of Clients
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getClientListAction()
    {
        $dataCount = 0;
        $totalFiltered = 0;
        $draw = 0;
        $tableData = array();
        
        if($this->getRequest()->isPost())
        {
            // Get the locale used from meliscore session
            $container = new Container('meliscore');
            $locale = $container['melis-lang-locale'];
            
            // Melis Translation Service Manager
            $melisTranslation = $this->getServiceLocator()->get('MelisCoreTranslation');
            
            // Client Service Managers
            $melisComClientService = $this->getServiceLocator()->get('MelisComClientService');
            $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
            
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $draw = $this->getRequest()->getPost('draw');
            
            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
            
            // Getting Client List from Client Service with filters, such as search, start, limit and order
            $clientData = $melisComClientService->getClientList(null, null, null, null, $start, $length, $sortOrder, $search);
            // Getting Client List from Client Service with less filters, this will return the exact number of requested result 
            $clientDataFiltered = $melisComClientService->getClientList(null, null, null, null, null, null, $sortOrder, $search);
            
            $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
            $melisTool           = $this->getServiceLocator()->get('MelisCoreTool');
            foreach ($clientData As $val)
            {
                // Client ID
                $clientId = $val->getId();
                
                // Client Status
                $clientData = $val->getClient();
                if ($clientData->cli_status==1)
                {
                    $clientStatus = '<i class="fa fa-circle text-success"></i>';
                }
                else
                {
                    $clientStatus = '<i class="fa fa-circle text-danger"></i>';
                }
                
                // Client Person Name
                $clientPersons = $val->getPersons();
                $clientPersonArray = array();
                $pCtr = 0;
                foreach ($clientPersons As $pVal)
                {
                    $tempPersonName = $melisTool->escapeHtml($pVal->cper_firstname).' '.$melisTool->escapeHtml($pVal->cper_name);
                    
                    if ($pVal->cper_is_main_person==1)
                    {
                        // Placing the data to the top/first index of the array
                        array_unshift($clientPersonArray, '<b>'.$tempPersonName.'</b>');
                    }
                    else
                    {
                        array_push($clientPersonArray, $tempPersonName);
                    }
                    $pCtr++;
                    if ($pCtr >= 3)
                    {
                        if (count($clientPersons)>3)
                        {
                            // Adding additional dots if the number of Clinet Contacts is more than three (3)
                            array_push($clientPersonArray, '...');
                        }
                        break;
                    }
                }
                $clientPersonStr = implode(', ', $clientPersonArray);
                
                // Client Company
                $clientCompany = $val->getCompany();
                $clientCompanyArray = array();
                foreach ($clientCompany As $cVal)
                {
                    array_push($clientCompanyArray, $melisTool->escapeHtml($cVal->ccomp_name));
                }
                $clientCompanyStr = implode(',', $clientCompanyArray);
                
                // Client Number of Orders
                $clientNumOrders = $melisEcomOrderTable->getTotalData('ord_client_id',$clientId);
                
                // Client Last Order Date 
                $clientLastOrderData = $melisEcomOrderTable->getClientLastOrderByClientId($clientId);
                $clientLastOrder = $clientLastOrderData->current();
                $clientLastOrderStr = '';
                if (!empty($clientLastOrder))
                {
                    $clientLastOrderStr = strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($clientLastOrder->ord_date_creation));
                }
                
                $dateCreated = strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($clientData->cli_date_creation));
                
                $tempData = array(
                    'DT_RowId' => $clientId,
                    'cli_id' => $clientId,
                    'cli_status' => $clientStatus,
                    'cli_person' => $clientPersonStr,
                    'cli_company' => $clientCompanyStr,
                    'cli_num_orders' => $clientNumOrders,
                    'cli_last_order' => $clientLastOrderStr,
                    'cli_date_creation' => $dateCreated,
                );
                
                array_push($tableData, $tempData);
            }
        }
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($tableData),
            'recordsFiltered' => count($clientDataFiltered),
            'data' => $tableData,
        ));
    }
    
    public function clientsExportValidateAction()
    {
        $errors = array();
        $dateErrors = array();
        $permErrors = array();
        $data = array();
        $success = 0;
        $textMessage = 'tr_meliscommerce_client_export_fail';
        $textTitle = 'tr_meliscommerce_clients_Client_listing';
        
        $tool = $this->getServiceLocator()->get('MelisCoreTool');
        $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
        
        $view = new ViewModel();
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_client_list_export_form','meliscommerce_client_list_export_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $clientExportForm = $factory->createForm($appConfigForm);
        
        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['clientFileName'];
        $dir = $csvConfig['dir'];
        
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        $lang = $clientSvc->getEcomLang();
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
            
            // convert date to generic for date comparison
            if(!empty($postValues['date_start'])){
                $startDate = $postValues['date_start'];
                $startDate = ($locale == 'fr_FR')? str_replace('/','-', $startDate) : $startDate;
            }
            
            if(!empty($postValues['date_end'])){
                $endDate = $postValues['date_end'];
                $endDate = ($locale == 'fr_FR')? str_replace('/','-', $endDate) : $endDate;
            }
            
            if( !empty($postValues['date_start']) && !empty($postValues['date_end'])){
            
                if(strtotime($startDate) > strtotime($endDate)){
                    $dateErrors['date_end'] = array(
                        'isGreaterThan' => $tool->getTranslation('tr_meliscommerce_orders_date_end_error'),
                        'label' => $tool->getTranslation('tr_meliscommerce_orders_date_end'),
                    );
                }
            }
            
            $clientExportForm->setData($postValues);
            if(!$clientExportForm->isValid()){
                $exportError = $clientExportForm->getMessages();
                foreach ($exportError as $keyError => $valueError)
                {
                    foreach ($appConfigForm['elements'] as $keyForm => $valueForm)
                    {
                        if ($valueForm['spec']['name'] == $keyError &&
                            !empty($valueForm['spec']['options']['label']))
                            $exportError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
                $errors = $exportError;
            }
            
            $errors = array_merge($dateErrors, $errors);
            
            // check file access and permission
            
            if(!is_dir($dir)){
                $dirCreate = mkdir($dir);
                if(!$dirCreate){
                    $permErrors['date_end'] = array(
                        'permissionError' => $tool->getTranslation('tr_meliscommerce_general_csv_permission_error', $dir),
                        'label' => $tool->getTranslation('tr_meliscommerce_general_permission_error_label'),
                    );
                }
            }
            
            $content = '';
            if(file_exists($dir)) {
                $test = file_put_contents($dir.$csvFileName, $content, LOCK_EX);
            }
            
            $errors = array_merge($errors, $permErrors);
            
            if(empty($errors)){
                $success = 1;
                $textMessage = 'tr_meliscommerce_client_export_success';
                $data = $clientExportForm->getData();
                $data['orderExportEncapse'] = ($postValues['orderExportEncapse'])? '"' : '';
                
                $data['date_start'] = !empty($data['date_start'])? $tool->localeDateToSql($data['date_start']) : $data['date_start'];
                if(!empty($endDate)){
                    $data['date_end'] = $tool->localeDateToSql($data['date_end']);
                    $data['date_end'] = date("Y-m-d", strtotime($data['date_end'] . "+1 days"));
                }
                
                $result = $clientSvc->exportClientList($data['cli_status'], $data['date_start'], $data['date_end'], $data['separator'], $data['orderExportEncapse'], $lang->elang_id);
                if(!$result){
                    $errors[]['unknown'] = array(
                        'permissionError' => 'unknown error',
                        'label' => $tool->getTranslation('tr_meliscommerce_general_permission_error_label'),
                    );
                }
            }
        }
        
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'chunk' => $data,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
        );
        return new JsonModel($results);
    }
    
    public function clientsExportToCsvAction()
    {
        
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        
        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['clientFileName'];
        $dir = $csvConfig['dir'];
        
        $csvData = file_get_contents($dir.$csvFileName);
        
        // Getting Current Langauge ID
        $response = new Response();
        $headers  = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/csv; charset=utf-8');
        $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"".$csvFileName."\"");
        $headers->addHeaderLine('Accept-Ranges', 'bytes');
        $headers->addHeaderLine('Content-Length', strlen($csvData));
        $response->setContent($csvData);
        
        return $response;

    }
    
}