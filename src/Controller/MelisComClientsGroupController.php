<?php


namespace MelisCommerce\Controller;


use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComClientsGroupController extends MelisAbstractActionController
{
    /**
     * @return ViewModel
     */
    public function renderClientsGroupToolContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupToolHeaderContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupToolHeaderAddButtonAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupContentActionDeleteAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupContentActionEditAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupTableFilterLimitAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupTableFilterRefreshAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupTableFilterSearchAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupToolContentModalAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $groupId = $this->params()->fromRoute('groupId', $this->params()->fromQuery('groupId', null));
        $title = $this->getTool()->getTranslation('tr_meliscommerce_clients_group_add_group');
        $icon = 'plus';

        if($groupId) {
            $title = $this->getTool()->getTranslation('tr_meliscommerce_clients_group_edit_group');
            $icon = 'pencil';
        }

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->title = $title;
        $view->icon = $icon;
        $view->groupId = $groupId;

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupToolContentModalFormAction()
    {
        $groupId = $this->params()->fromRoute('groupId', $this->params()->fromQuery('groupId', null));

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $data = array();
        $form = $this->getForm();
        if(!empty($groupId)){
            $groupSrv = $this->getServiceManager()->get('MelisComClientGroupsService');
            $formData = get_object_vars($groupSrv->getClientsGroupById($groupId));
            $form->setData($formData);
        }

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->form = $form;
        $view->groupId = $groupId;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderClientsGroupToolContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $columns = $this->getTool()->getColumns();

        $columns['action'] =  array(
            'text' => $this->getTool()->getTranslation('Action'),
            'css' => array('width' => '10%')
        );

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration(null, null, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }

    /**
     * @return JsonModel
     */
    public function getClientsGroupListAction()
    {
        $clientGroupService = $this->getServiceManager()->get('MelisComClientGroupsService');

        $colId = [];
        $dataCount = 0;
        $draw = 0;
        $tableData = [];
        $recordsFiltered = 0;

        if($this->getRequest()->isPost()) {

            $colId = array_keys($this->getTool()->getColumns());

            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];

            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];

            $draw = $this->getRequest()->getPost('draw');

            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');

            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];

            $dataCount =  $clientGroupService->getClientGroupsTotalData();

            $tableData =  $clientGroupService->getClientsGroupList(
                $start, $length, $sortOrder, $selCol, $search,
                $this->getTool()->getSearchableColumns()
            );

            $recordsFiltered =  $clientGroupService->getClientsGroupList(
                null, null, null, null, $search,
                $this->getTool()->getSearchableColumns()
            );

            $coreSrv = $this->getServiceManager()->get('MelisGeneralService');
            foreach($tableData as $key => $val){
                $attrArray = array(
                    'data-cgroup-id' => $tableData[$key]['cgroup_id'],
                );

                $tableData[$key]['cgroup_status'] = $coreSrv->sendEvent('melis_tool_column_display_dot_color', ['data' => $val['cgroup_status']])['data'];

                //assign attribute data to table row
                $tableData[$key]['DT_RowAttr'] = $attrArray;
            }
        }

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => count($recordsFiltered),
            'data' => $tableData,
        ));
    }

    /**
     * @return JsonModel
     */
    public function saveClientsGroupAction()
    {
        $success = 0;
        $message = 'tr_meliscommerce_clients_group_save_ko';
        $textTitle = 'tr_meliscommerce_clients_group';
        $errors = [];

        $translator = $this->getServiceManager()->get('translator');
        $postData = $this->getRequest()->getPost()->toArray();
        $groupId = $postData['groupId'] ?? null;
        //remove id in the post
        if(isset($postData['groupId']))
            unset($postData['groupId']);

        // get group form
        $groupForm = $this->getForm();
        // set data to validated the posted data
        $groupForm->setData($postData);
        // is valid
        if ($groupForm->isValid()) {
            $groupSrv = $this->getServiceManager()->get('MelisComClientGroupsService');
            $res = $groupSrv->saveClientsGroup($postData, $groupId);
            if($res){
                $success = 1;
                $message = $translator->translate('tr_meliscommerce_clients_group_save_ok');
            }
        }else{
            $errors = $groupForm->getMessages();
            foreach ($errors as $keyError => $valueError){
                $errors[$keyError]['label'] = $translator->translate("tr_meliscommerce_clients_group_".$keyError."_fld");
            }
        }

        $results = [
            'textTitle' => $translator->translate($textTitle),
            'success' => $success,
            'textMessage' => $translator->translate($message),
            'errors' => $errors,
            'typeCode' => !empty($groupId) ? 'UPDATE_CLIENTS_GROUP' : 'ADD_CLIENTS_GROUP',
            'itemId' => $groupId
        ];

        return new JsonModel($results);
    }

    /**
     * @return JsonModel
     */
    public function deleteClientsGroupAction()
    {
        $success = 0;
        $message = 'tr_meliscommerce_clients_group_delete_ko';
        $textTitle = 'tr_meliscommerce_clients_group';
        $errors = [];

        $translator = $this->getServiceManager()->get('translator');
        $postData = $this->getRequest()->getPost()->toArray();
        $groupId = $postData['groupId'] ?? 0;

        $groupSrv = $this->getServiceManager()->get('MelisComClientGroupsService');
        $res = $groupSrv->deleteClientsGroupById($groupId);
        if($res){
            $success = 1;
            $message = $translator->translate('tr_meliscommerce_clients_group_delete_ok');
        }

        $results = [
            'textTitle' => $translator->translate($textTitle),
            'success' => $success,
            'textMessage' => $translator->translate($message),
            'typeCode' => 'DELETE_CLIENTS_GROUP',
            'itemId' => $groupId
        ];

        return new JsonModel($results);
    }

    /**
     * @return mixed
     */
    private function getForm()
    {
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/tools/meliscommerce_clients_group/forms/meliscommerce_clients_group_form','meliscommerce_clients_group_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($appConfigForm);

        return $form;
    }

    /**
     * @return mixed
     */
    private function getTool()
    {
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_clients_group');

        return $tool;
    }
}