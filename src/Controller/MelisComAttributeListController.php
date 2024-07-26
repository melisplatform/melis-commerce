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
use MelisCore\Controller\MelisAbstractActionController;
use MelisCommerce\Model\Attribute;

class MelisComAttributeListController extends MelisAbstractActionController
{
    /**
     * renders the attribute list page container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListPageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the attribute list page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListHeaderContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the attribute list page left header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListHeaderLeftContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    public function searchAttributeAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $attributeId = null;

        $textMessage = 'tr_meliscommerce_attribute_delete_fail';
        $textTitle = 'tr_meliscommerce_attribute_list_page';
        $this->getEventManager()->trigger('meliscommerce_attribute_delete_start', $this, array());

        $attributeSvc = $this->getServiceManager()->get('MelisComAttributeService');

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );


        return new JsonModel($response);
    }

    /**
     * renders the attribute list page left header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListHeaderRightContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the attribute list page header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the add new attribute button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListAddAttributeAction()
    {
        return new ViewModel();
    }

    /**
     * renders the attribute list page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the attribute list page table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListContentTableAction()
    {
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#tableAttributeList', true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }

    /**
     * renders the attribute list content table filter limit
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListContentFilterLimitAction()
    {
        return new ViewModel();
    }

    /**
     * renders the attribute list content table filter search
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListContentFilterSearchAction()
    {
        return new ViewModel();
    }

    /**
     * renders the attribute list content table filter refresh
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListContentFilterRefreshAction()
    {
        return new ViewModel();
    }

    /**
     * renders the attribute list content table action info
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListContentActionInfoAction()
    {
        return new ViewModel();
    }

    /**
     * renders the attribute list content table action delete
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderAttributeListContentActionDeleteAction()
    {
        return new ViewModel();
    }

    /**
     * retrieves the data for the attribute list table
     * @return \Laminas\View\Model\JsonModel
     */
    public function getAttributeListDataAction()
    {
        $colId = array();
        $draw = 0;
        $langId = $this->getTool()->getCurrentLocaleID();
        $paginated = [];
        $attributes = [];

        if ($this->getRequest()->isPost()) {
            $colId = array_keys($this->getTool()->getColumns());

            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];

            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];

            $draw = (int) $this->getRequest()->getPost('draw');

            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');

            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];

            $attributes = Attribute::getAttributesList($langId, null, null, null, $start, $length, $sortOrder, $search, false, $selCol)->get();
            $paginated = Attribute::getAttributesList(null, null, null, null, $start, $length, $sortOrder, $search, true, $selCol);
        }

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $attributes->count(),
            'recordsFiltered' =>  $paginated->count(),
            'data' => $attributes,
        ));
    }

    public function deleteAttributeAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $attributeId = null;

        $textMessage = 'tr_meliscommerce_attribute_delete_fail';
        $textTitle = 'tr_meliscommerce_attribute_list_page';
        $this->getEventManager()->trigger('meliscommerce_attribute_delete_start', $this, array());

        $attributeSvc = $this->getServiceManager()->get('MelisComAttributeService');

        if ($this->getRequest()->isPost()) {
            $postValues = $this->getRequest()->getPost()->toArray();
            $attributeId = $postValues['attributeId'];
            $success = $attributeSvc->deleteAttributeById($attributeId);
            if ($success) {
                $textMessage = 'tr_meliscommerce_attribute_delete_success';
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );

        $this->getEventManager()->trigger(
            'meliscommerce_attribute_delete_end',
            $this,
            array_merge($response, array('typeCode' => 'ECOM_ATTRIBUTE_DELETE', 'itemId' => $attributeId))
        );

        return new JsonModel($response);
    }

    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_attribute_list');

        return $melisTool;
    }
}

