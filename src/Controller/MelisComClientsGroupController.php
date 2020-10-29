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
     * @return mixed
     */
    private function getTool()
    {
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_clients_group');

        return $tool;
    }
}