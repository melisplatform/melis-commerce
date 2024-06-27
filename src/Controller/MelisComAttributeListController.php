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
        $success = 0;
        $colId = array();
        $dataCount = 0;
        $countFiltered = 0;
        $draw = 0;
        $tableData = array();
        $langId = $this->getTool()->getCurrentLocaleID();
        $attributeSvc = $this->getServiceManager()->get('MelisComAttributeService');
        $attributeTypeTable = $this->getServiceManager()->get('MelisEcomAttributeTypeTable');
        $checked = '<span class="text-danger"><i class="fa fa-check"></i></span>';
        
        if($this->getRequest()->isPost()) {
            $colId = array_keys($this->getTool()->getColumns());
            
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
            $colOrder = $selCol. ' ' . $sortOrder;
            
            $draw = (int) $this->getRequest()->getPost('draw');
            
            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
            
            $attributeList = $attributeSvc->getAttributes(null, null, null, null, $start, $length, $colOrder, $search);
            $countFiltered = count($attributeList);
            $tmp = $attributeSvc->getAttributes(null, null, null, null, null, null, null, $search);
            $dataCount = count($tmp);
//             echo '<pre>'; print_r($attributeList); echo '</pre>'; die();
            $c = 0;
            foreach($attributeList as $attribute){
                $status = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
                $visible = '&nbsp;';
                $searchable = '&nbsp;';
                if($attribute->getAttribute()->attr_status){
                    $status = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
                }
                
                if($attribute->getAttribute()->attr_visible){
                    $visible = $checked;
                }
                
                if($attribute->getAttribute()->attr_searchable){
                    $searchable = $checked;
                }
                
                if(!empty($attribute->getAttribute()->attr_trans)){
                    $foundTrans = false;
                    foreach($attribute->getAttribute()->attr_trans as $attrTrans){
                        if($attrTrans->atrans_lang_id == $langId){
                            $foundTrans = true;
                            $atrans_name = $attrTrans->atrans_name;
                        }
                    }
                    if(!$foundTrans){
                        $atrans_name = $attribute->getAttribute()->attr_trans[0]->atrans_name;
                    }                    
                }else{
                    $atrans_name = $attribute->getAttribute()->attr_reference;
                }
                
                $atype_name = $attributeTypeTable->getEntryById($attribute->getAttribute()->attr_type_id)->current()->atype_name;
                
                $tableData[$c]['DT_RowId']          = $attribute->getId();
                $tableData[$c]['attr_id']           = $attribute->getId();
                $tableData[$c]['attr_status']       = $status;
                $tableData[$c]['attr_visible']      = $visible;
                $tableData[$c]['attr_searchable']   = $searchable;
                $tableData[$c]['atrans_name']       = $this->getTool()->escapeHtml($atrans_name);
                $tableData[$c]['attr_reference']    = $this->getTool()->escapeHtml($attribute->getAttribute()->attr_reference);
                $tableData[$c]['atype_name']        = $this->getTool()->escapeHtml($atype_name);
                $c++;
            }
        }
        
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $countFiltered,
            'recordsFiltered' =>  $dataCount,
            'data' => $tableData,
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
        
        if($this->getRequest()->isPost()){
            $postValues = $this->getRequest()->getPost()->toArray();
            $attributeId = $postValues['attributeId'];
            $success = $attributeSvc->deleteAttributeById($attributeId);
            if($success){
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
        
        $this->getEventManager()->trigger('meliscommerce_attribute_delete_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_ATTRIBUTE_DELETE', 'itemId' => $attributeId)));
        
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