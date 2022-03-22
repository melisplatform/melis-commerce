<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Session\Container;
use Laminas\Http\Response;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComClientListController extends MelisAbstractActionController
{
    const PLUGIN_INDEX = 'meliscommerce';
    
    /**
     * Render Client List Page
     * 
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListWidgetsNumClientsAction()
    {
        $clientSvc = $this->getServiceManager()->get('MelisComClientService');
        $clientCount = $clientSvc->getClientList();
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = count($clientCount);
        return $view;
    }
    
    /**
     * renders the client list page monthly clients
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListWidgetsMonthClientsAction()
    {
        $clientSvc = $this->getServiceManager()->get('MelisComClientService');
        $clientCount = $clientSvc->getWidgetClients('curMonth');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = $clientCount;
        return $view;
    }
    
    /**
     * renders the client list page average client count
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListWidgetsAvgClientsAction()
    {
        $clientSvc = $this->getServiceManager()->get('MelisComClientService');
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
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListTableAction()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_clients_list');
        
        // DataTable costume configuration
        $columns = $melisTool->getColumns();
        $translator = $this->getServiceManager()->get('translator');
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
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListTableRefreshAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderClientListTableGroupFilterAction()
    {
        $groupService = $this->getServiceManager()->get('MelisComClientGroupsService');
        $groups = $groupService->getClientsGroupList(null,null,'ASC','cgroup_name',null,null, $status = 1);

        $translator = $this->getServiceManager()->get('translator');
        $options = '<option  value="">'.$translator->translate('tr_meliscommerce_clients_common_label_all').'</option>';
        foreach($groups as $val){
            $options .= '<option value="'.$val['cgroup_id'].'">'.$val['cgroup_name'].'</option>';
        }

        $view =  new ViewModel();
        $view->options = $options;
        return $view;
    }
    
    /**
     * Render Client List view button for client info
     * 
     * @return \Laminas\View\Model\ViewModel
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
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(true);
        return $view;
    }
    
    public function renderClientListContentExportFormAction()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $datepickerInit = $melisTool->datePickerInit('date_start');
        $datepickerInit .= $melisTool->datePickerInit('date_end');
        
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_client_list_export_form','meliscommerce_client_list_export_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
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
     * @return \Laminas\View\Model\JsonModel
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
            $melisTranslation = $this->getServiceManager()->get('MelisCoreTranslation');
            // Client Service Managers
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
            
            $melisTool = $this->getServiceManager()->get('MelisCoreTool');
            $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_clients_list');
            
            $colId = array_keys($melisTool->getColumns());
            
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
            
            $draw = $this->getRequest()->getPost('draw');
            
            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];

            $groupId = $this->getRequest()->getPost('cgroup_id', null);
            
            $melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
        
            $getData = $melisEcomClientTable->clientList([
                'where' => [
                    'key' => 'cli_id',
                    'value' => $search,
                ],
                'order' => [
                    'key' => $selCol,
                    'dir' => $sortOrder,
                ],
                'start' => $start,
                'limit' => $length,
                'columns' => $melisTool->getSearchableColumns(),
                'groupId' => $groupId
            ]);
            
            $contactData = $getData->toArray();

            foreach ($contactData As $val) {
                // Generating contact status html form
                $contactStatus = '<i class="fa fa-circle text-danger"></i>';
                if ($val['cli_status'])
                    $contactStatus = '<i class="fa fa-circle text-success"></i>';
                
                $clientCreated = !empty($val['cli_date_creation']) ? mb_substr(strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($val['cli_date_creation'])), 0, 10) : '';
                
                $tableData[] = [
                    'DT_RowId' => $val['cli_id'],
                    'cli_id' => $val['cli_id'],
                    'client_group' => $val['client_group'],
                    'cli_status' => $contactStatus,
                    'client_company' => $val['client_company'],
                    'cli_date_creation' => $clientCreated,
                    'contact_person' => $val['contact_person'],
                    'total_num_order' => $val['total_num_order'],
                    'date_last_order' => $val['date_last_order'],
                ];

                $dataCount = $val['total_records'];
            }
        }
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => $dataCount,
            'data' => $tableData,
        ));
    }

    public function sendExportToCsvAction()
    {

        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');

        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['clientFileName'];
        $dir = $csvConfig['dir'];

        $csvData = file_get_contents($dir.$csvFileName);

        // Getting Current Langauge ID
        $response = new Response();
        $headers  = $response->getHeaders();
        $response->setContent($csvData);

        return $response;

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
        
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        $clientSvc = $this->getServiceManager()->get('MelisComClientService');
        
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_client_list_export_form','meliscommerce_client_list_export_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $clientExportForm = $factory->createForm($appConfigForm);
        
        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['clientFileName'];
        $dir = $csvConfig['dir'];
        
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        $lang = $clientSvc->getEcomLang();
        
        if($this->getRequest()->isPost()){
            $postValues = $this->getRequest()->getPost()->toArray();
            
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
        
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $tool = $this->getServiceManager()->get('MelisCoreTool');

        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['clientFileName'];
        $dir = $csvConfig['dir'];
        
        $csvData = file_get_contents($dir.$csvFileName);
        //fixed special characters problem
        $csvData = $tool->sanitize($csvData);
        $csvData = mb_convert_encoding($csvData, 'UTF-16LE', 'UTF-8');

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