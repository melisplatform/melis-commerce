<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\Http\Headers;
use Laminas\Stdlib\ArrayUtils;
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

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_clients_list_add_client_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
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
        $clientCount = $clientSvc->getClientList(null, null, null, null,
            0, null, null, null, true);

        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = $clientCount->total ?? 0;
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
    public function renderClientListWidgetsActiveInactiveAction()
    {
        $clientSvc = $this->getServiceManager()->get('MelisComClientService');
        $clientActive = $clientSvc->getWidgetClients('activeInactive', 'active');
        $clientInactive = $clientSvc->getWidgetClients('activeInactive', 'inactive');

        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->clientActive = $clientActive->total ?? 0;
        $view->clientInActive = $clientInactive->total ?? 0;
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
     * Render Client custom page export button, this button attach to table plugin
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
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListTableExportAccountsAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_clients_list_export_client_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderClientListTableImportAccountsAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_clients_list_import_client_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
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
     * @return ViewModel
     */
    public function renderClientListTableStatusFilterAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $groups = [
            '1' => $translator->translate('tr_meliscommerce_client_status_active'),
            '0' => $translator->translate('tr_meliscommerce_client_status_inactive'),
        ];

        $options = '<option  value="">'.$translator->translate('tr_meliscommerce_clients_common_label_all').'</option>';
        foreach($groups as $val => $name){
            $selected = ($val == 1) ? 'selected' : '';
            $options .= '<option '.$selected.' value="'.$val.'">'.$name.'</option>';
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
     * @return ViewModel
     */
    public function renderClientListTableDeleteAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_clients_list_delete_client_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
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
     * @return ViewModel
     */
    public function renderClientListContentExportAccountsFormAction()
    {
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_client_list_export_accounts_form','meliscommerce_client_list_export_accounts_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $clientExportForm = $factory->createForm($appConfigForm);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_client_list_export_accounts_form', $clientExportForm);
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderClientListContentImportAccountsFormAction()
    {
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_client_list_import_accounts_form','meliscommerce_client_list_import_accounts_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $clientExportForm = $factory->createForm($appConfigForm);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_client_list_import_accounts_form', $clientExportForm);
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
            
            $melisTool = $this->getServiceManager()->get('MelisCoreTool');
            $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_clients_list');
            
            $colId = array_keys($melisTool->getColumns());
            
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
            if($selCol == 'default_contact')
                $selCol = 'car_default_person';

            $draw = $this->getRequest()->getPost('draw');
            
            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];

            $groupId = $this->getRequest()->getPost('cgroup_id', null);
            $clientStatus = $this->getRequest()->getPost('cli_status', null);
            
            $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientTable');
            $dataCount = $melisEcomClientPersonTable->getTotalData();
        
            $getData = $melisEcomClientPersonTable->getAccountToolList(array(
                'where' => array(
                    'key' => 'cli_id',
                    'value' => $search,
                ),
                'order' => array(
                    'key' => $selCol,
                    'dir' => $sortOrder,
                ),
                'start' => $start,
                'limit' => $length,
                'columns' => $melisTool->getSearchableColumns(),
                'date_filter' => array(),
                'groupId' => $groupId,
                'clientStatus' => $clientStatus,
                'count' => false
            ));

            $totalFiltered = $melisEcomClientPersonTable->getAccountToolList(array(
                'where' => array(
                    'key' => 'cli_id',
                    'value' => $search,
                ),
                'order' => array(
                    'key' => $selCol,
                    'dir' => $sortOrder,
                ),
                'start' => null,
                'limit' => null,
                'columns' => $melisTool->getSearchableColumns(),
                'date_filter' => array(),
                'groupId' => $groupId,
                'clientStatus' => $clientStatus,
                'count' => true
            ))->current();

            if($totalFiltered)
                $totalFiltered = $totalFiltered->total;

            // store fetched data for data modification (if needed)
            $contactData = $getData->toArray();

            $melisEcomOrderTable = $this->getServiceManager()->get('MelisEcomOrderTable');
            foreach ($contactData As $val)
            {
                $contactStatus = '<i class="fa fa-circle text-danger"></i>';
                // Generating contact status html form
                if ($val['cli_status'])
                {
                    $contactStatus = '<i class="fa fa-circle text-success"></i>';
                }
                
                // Getting the Contact number of Order(s)
                $contactOrderData = $melisEcomOrderTable->getEntryByField('ord_client_id', $val['cli_id']);
                $contactOrder = $contactOrderData->toArray();
                $contactNumOrders = count($contactOrder);
                $lastOrder = !empty($val['cli_last_order'])? mb_substr(strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($val['cli_last_order'])), 0, 10) : '';
                $clientCreated = !empty($val['cli_date_creation'])? mb_substr(strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($val['cli_date_creation'])), 0, 10) : '';

                $defaultContact = '';
                $accountContactList = $melisComClientService->getAccountAssocContactLists($val['cli_id'])->toArray();
                if(!empty($accountContactList)){
                    foreach($accountContactList as $key => $v){
                        if($v['car_default_person']) {
                            $defaultContact = $v['cper_firstname'] . ' ' . $v['cper_name'];
                            $defaultContact = "<span class='d-none td-tooltip'>".$defaultContact."</span>".mb_strimwidth($defaultContact, 0, 30, '...');
                            break;
                        }
                    }
                }

                $cliname = $melisComClientService->getAccountName($val['cli_id']);

                $rowdata = array(
                    'DT_RowId' => $val['cli_id'],
                    'cli_id' => $val['cli_id'],
                    'cgroup_name' => !empty($val['cgroup_name']) ? "<span class='d-none td-tooltip'>".$val['cgroup_name']."</span>".mb_strimwidth($val['cgroup_name'], 0, 30, '...') : null,
                    'cli_status' => $contactStatus,
                    'cli_company' => !empty($val['cli_company']) ? "<span class='d-none td-tooltip'>".$val['cli_company']."</span>".mb_strimwidth($val['cli_company'], 0, 30, '...') : null,
                    'cli_date_creation' => $clientCreated,
                    'cli_name' => !empty($cliname) ? "<span class='d-none td-tooltip'>".$cliname."</span>".mb_strimwidth($cliname, 0, 30, '...') : null,
                    'cli_num_orders' => $contactNumOrders,
                    'cli_last_order' => $lastOrder,
                    'default_contact' => $defaultContact,
                    'DT_RowAttr' => [
                        'data-hasorder' => !empty($contactNumOrders) ? 1 : 0
                    ]
                );
                
                array_push($tableData, $rowdata);
            }
        }

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => $totalFiltered,
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

    /**
     * Function to export accounts
     *
     * @return HttpResponse|string
     */
    public function exportAccountsAction()
    {
        ini_set('max_execution_time', -1);
        // set memory limit to infinte
        ini_set('memory_limit', '-1');
        $queryData = $this->request->getQuery()->toArray();

        $translator = $this->getServiceManager()->get('translator');
        // Client Service Managers
        $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
        $melisTranslation = $this->getServiceManager()->get('MelisCoreTranslation');

        // Get the locale used from meliscore session
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];

        $delimiter = $queryData['separator'] ?? ';';
        $status = $queryData['status'] ?? null;
        $groupId = $queryData['groupId'] ?? null;
        $search = $queryData['search'] ?? null;

        $fileName = date('Ymd').'_'.strtolower($translator->translate('tr_meliscommerce_clients_Clients')).'.csv';

        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_clients_list');

        $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientTable');
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');

        $getData = $melisEcomClientPersonTable->getAccountToolList(array(
            'where' => array(
                'key' => 'cli_id',
                'value' => $search,
            ),
            'order' => array(
                'key' => 'cli_id',
                'dir' => 'ASC',
            ),
            'start' => null,
            'limit' => null,
            'columns' => $melisTool->getSearchableColumns(),
            'date_filter' => array(),
            'groupId' => $groupId,
            'clientStatus' => $status,
            'count' => false
        ))->toArray();

        $data = [];
        //loop through each to modify or add new data
        if(!empty($getData)){
            foreach($getData as $key => $val){

                //check first the commerce account settings if we use contact name or company or the default client name
                $getData[$key]['cli_name'] = $melisComClientService->getAccountName($val['cli_id']);
                //get client default contact
                $defContact = $melisComClientService->getClientDefaultContactByClientId($val['cli_id'])->current();
                $defContactId = (!empty($defContact)) ? $defContact->cper_id : null;
                $defContactName = (!empty($defContact)) ? $defContact->cper_firstname.' '.$defContact->cper_name : null;
                $defContactEmail = (!empty($defContact)) ? $defContact->cper_email : null;
                $getData[$key]['default_contact_id'] = $defContactId;
                $getData[$key]['default_contact_name'] = $defContactName;
                $getData[$key]['default_contact_email'] = $defContactEmail;

                //get client country
                $countryData = $countryTable->getEntryById($val['cli_country_id'])->current();
                if(!empty($countryData)){
                    $getData[$key]['cli_country_id'] = $countryData->ctry_name;
                }
                //format dates
//                $lastOrder = !empty($val['cli_last_order'])? mb_substr(strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($val['cli_last_order'])), 0, 10) : '';
                $clientCreated = !empty($val['cli_date_creation'])? mb_substr(strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($val['cli_date_creation'])), 0, 10) : '';
                $getData[$key]['cli_date_creation'] = $clientCreated;
//                $getData[$key]['cli_last_order'] = $lastOrder;
                //we use ccomp_comp_creation_date as company creation date
                $companyCreated = !empty($val['ccomp_comp_creation_date'])? mb_substr(strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($val['ccomp_comp_creation_date'])), 0, 10) : '';
                $getData[$key]['ccomp_date_creation'] = $companyCreated;
            }

            //now we include billing address
            $accountBill = [];
            foreach($getData as $key => $val){
                //lets include billing address
                $this->processAccountAddress($accountBill, $key, $val['cli_id']);
            }

            //now we include delivery address
            $accountDel = [];
            foreach($getData as $key => $val){
                //lets include billing address
                $this->processAccountAddress($accountDel, $key, $val['cli_id'], 2);
            }

            //now we include orders
            $accountOrders = [];
            foreach($getData as $key => $val){
                //lets include billing address
                $this->processAccountOrders($accountOrders, $key, $val['cli_id']);
            }

            /**
             * This will match keys for every data
             * For example if field 1 doesn't exist in array 1, it will put field 1 in array 1 to match all the keys
             */
            $keys = [];
            $this->matchKeys($keys, $data);
            $this->matchKeys($keys, $accountBill);
            $this->matchKeys($keys, $accountDel);
            $this->matchKeys($keys, $accountOrders);

            $getData = ArrayUtils::merge($getData, $accountBill, true);
            $getData = $this->processKeysToMatch($keys, $getData);

            $getData = ArrayUtils::merge($getData, $accountDel, true);
            $getData = $this->processKeysToMatch($keys, $getData);

            $getData = ArrayUtils::merge($getData, $accountOrders, true);
            $getData = $this->processKeysToMatch($keys, $getData);

            //reposition fields
            foreach($getData as $key => $val){
                $getData[$key] = array_slice($val, 0, 5, true) +
                    array("cgroup_name" => $val['cgroup_name']) +
                    array_slice($val, 3, count($val) - 1, true) ;
            }

            $exportData = [];
            /**
             * Columns to exclude in the export
             */
            $excludeColumns = [
                'cli_group_id', 'cli_date_edit', 'cli_last_order',
                'car_id','car_client_id','car_client_person_id','car_default_person',
                'cper_firstname','cper_name','cper_id','cper_email',
                'ccomp_name', 'ccomp_logo','ccomp_id','ccomp_client_id', 'ccomp_comp_creation_date','ccomp_date_edit', 'ccomp_add_floor',
                'cadd_id', 'cadd_client_id', 'cadd_client_person', 'cadd_type',
                'catype_id', 'catype_code','civ_id'
            ];
            /**
             * Translate all fields
             */
            foreach($getData as $key => $val){
                $dt = [];
                foreach($val as $k => $d){
                    if(!in_array($k, $excludeColumns)) {
                        if(strpos($k, 'translated_') !== false)//check if field already translated
                            $fname = str_replace('translated_', '', $k);
                        else//translate field
                            $fname = $translator->translate('tr_client_accounts_export_col_' . $k);
                        $dt["$fname"] = $d;
                    }
                }
                $exportData[$key] = $dt;
            }
            $data = $exportData;
        }

        $data = $this->mbEncode($data);

        return $this->executeCompanyContactExport($data, $fileName, $delimiter);
    }

    private function matchKeys(&$keys, $data)
    {
        foreach($data as $i => $val){
            foreach($val as $k => $v){
                if(!array_key_exists($k, $keys)){
                    $keys[$k] = null;
                }
            }
        }
        return $keys;
    }

    private function processKeysToMatch($keys, $data)
    {
        foreach($data as $i => $val){
            foreach($keys as $k => $b){
                if(!array_key_exists($k, $val)){
                    $data[$i][$k] = null;
                }
            }
        }
        return $data;
    }

    private function processAccountOrders(&$orderDatas, $key, $accountId)
    {
        $langId = $this->getOrderTool()->getCurrentLocaleID();

        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        $translator = $this->getServiceManager()->get('translator');
        $orders = $melisComOrderService->getOrderList(null, true, null, $accountId);
        if(!empty($orders)){
            $orderCount = 1;
            foreach($orders as $order){
                $price = 0;
                $products = 0;
                $cper_firstname = '';
                $cper_name = '';

                $orderStatus = null;
                $statusTrans = $melisComOrderService->getOrderStatusByOrderId($order->getId());
                foreach($statusTrans as $trans){
                    if($trans->ostt_lang_id == $langId){
                        $orderStatus = $trans;
                    }
                }
                $orderStatus = empty($orderStatus)? $statusTrans[0] : $orderStatus;
                foreach($order->getBasket() as $basket){
                    $products = $products + $basket->obas_quantity;
                }

                foreach($order->getPayment() as $payment){
                    $price = $price + $payment->opay_price_total;
                }

                $client = $order->getPerson();

                if(!empty($client)){
                    $cper_firstname = $client->cper_firstname;
                    $cper_name = $client->cper_name;
                }

                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_ord_id').' '.$orderCount] = $order->getId();
                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_ord_reference').' '.$orderCount] = $order->getOrder()->ord_reference;
                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_ord_status').' '.$orderCount] = $orderStatus->ostt_status_name;
                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_products').' '.$orderCount] = number_format($products, 0);
                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_price').' '.$orderCount] = number_format($price, 2) . "€";
                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_cper_firstname').' '.$orderCount] = $this->getOrderTool()->escapeHtml($cper_firstname);
                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_cper_name').' '.$orderCount] = $this->getOrderTool()->escapeHtml($cper_name);
                $orderDatas[$key]['translated_'.$translator->translate('tr_client_accounts_export_col_ord_date_creation').' '.$orderCount] = $this->getOrderTool()->dateFormatLocale($order->getOrder()->ord_date_creation);

                $orderCount++;
            }
        }
    }

    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getOrderTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_order_list');

        return $melisTool;
    }

    private function processAccountAddress(&$getData, $key, $accountId, $type = 1)
    {
        $translator = $this->getServiceManager()->get('translator');
        $melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
        $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
        $clientAddressData = $melisEcomClientAddressTable->getClientAddressByClientId($accountId, $type);
        $clientAddress = array();
        foreach ($clientAddressData As $aVal)
        {
//            $aVal->civility_trans = $melisComClientService->getCivilityTransByCivilityIdAndLangId($aVal->civ_id);
//            $aVal->address_trans = $melisComClientService->getAddressTransByAddressTypeIdAndLangId($aVal->catype_id);
            array_push($clientAddress, $aVal);
        }

        $addressCount = 1;
        $type = $type == 1 ? $translator->translate('tr_meliscommerce_clients_common_billing') : $translator->translate('tr_meliscommerce_clients_common_delivery');
        foreach($clientAddress as $i => $val){
            foreach($val as $k => $v){
                if(!in_array($k, ['cadd_id', 'cadd_client_id', 'cadd_client_person', 'cadd_type','catype_id', 'catype_code','civ_id', 'cadd_creation_date'])) {
                    $fname = $translator->translate('tr_client_accounts_export_col_' . $k);
                    $getData[$key]['translated_' . $type . ' - ' . $fname . ' ' . $addressCount] = $v;
                }
            }
            $addressCount++;
        }
    }

    /**
     * applied utf8_encode
     */
    public function mbEncode($data)
    {
        $newData = [];
        if (! empty($data)) {
            foreach ($data as $idx => $val) {
                foreach (array_keys($val) as $key) {
                    $tmp = $val[$key];
                    // encode utf8_encode
                    $newData[$idx][utf8_encode($key)] = html_entity_decode($tmp);
                }
            }
        }

        return $newData;
    }

    /**
     * @param $data
     * @param $fileName
     * @param null $customSeparator
     * @param null $customIsEnclosed
     * @return HttpResponse|string
     */
    public function executeCompanyContactExport($data, $fileName, $customSeparator = null, $customIsEnclosed = null)
    {
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');

        $csvConfig = $melisCoreConfig->getItem('meliscore/datas/default/export/csv');
        $separator = empty($customSeparator) ? $csvConfig['separator'] : $customSeparator;

        if($customIsEnclosed != null)
            $enclosed = $customIsEnclosed == 0 ? '' : '"';
        else
            $enclosed = $csvConfig['enclosed'];

        $striptags = (int) $csvConfig['striptags'] == 1 ? true : false;
        $response = '';

        if ($data) {
            $csvColumn = $data[0];

            $content = '';

            // for columns
            foreach ($csvColumn as $key => $colText) {
                $content .= $key . $separator;
            }
            $content .= "\r\n";

            // for contents
            foreach ($data as $dataKey => $dataValue) {

                foreach ($dataValue as $key => $value) {

                    if ($striptags) {
                        $value = utf8_encode($value);
                    } else {
                        if (is_int($value)) {
                            $value = (string) $value;
                            $value = utf8_encode($value);
                        }
                    }

                    /**
                     * to solve the issue of 1 double quoute is to replace it with 2 double quote
                     * so that it will not break the csv file
                     */
                    $value = str_replace('"', '""', $value);
                    $value = str_replace(array("\r", "\n"), '', $value);
                    // content
                    $content .= $enclosed . $value . $enclosed . $separator;

                }
                $content .= "\r\n";
            }

            $response = new Response();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'text/csv; charset=utf-8');
            $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"" . $fileName . "\"");
            $headers->addHeaderLine('Accept-Ranges', 'bytes');
            $headers->addHeaderLine('Content-Length', strlen($content));
            $headers->addHeaderLine('fileName', $fileName);
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * @return Response\Stream
     */
    public function downloadImportTemplateAction()
    {
        $file = $_SERVER['DOCUMENT_ROOT'].'/../vendor/melisplatform/melis-commerce/public/template/sample_import _accounts.csv';

        $response = new Response\Stream();
        $response->setStream(fopen($file, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($file));
        $headers = new Headers();
        $headers->addHeaders(array(
            'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => filesize($file),
        ));
        $response->setHeaders($headers);
        return $response;
    }
}