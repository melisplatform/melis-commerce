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
class MelisComAssociateVariantController extends AbstractActionController
{
    private function getPrefix()
    {
        $variantId = (int) $this->params()->fromQuery('variantId', '');
        return $variantId;
    }

    public function renderTabAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;

    }

    public function renderTabContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;

        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentAssocVarListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $columns = $this->getTool1()->getColumns();

        $columns['action'] =  array(
            'text' => $this->getTool2()->getTranslation('tr_meliscommerce_assoc_var_col_action'),
            'css' => array('width' => '10%')
        );

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool1()->getDataTableConfiguration('#tableAssocVariantList1_'.$this->getPrefix(), null, null, array('order' => '[[ 0, "asc" ]]'));
        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentAssocVarListFilterLimitAction()
    {
        return new ViewModel();
    }

    public  function renderTabContentAssocVarListFilterRefreshAction()
    {
        return new ViewModel();
    }

    public  function renderTabContentAssocVarListFilterSearchAction()
    {
        return new ViewModel();
    }

    public function renderTabContentAssocVarListActionRemoveAction()
    {
        return new ViewModel();
    }

    public function renderTabContentVarListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $columns = $this->getTool2()->getColumns();

        $columns['action'] =  array(
            'text' => $this->getTool2()->getTranslation('tr_meliscommerce_assoc_var_col_action'),
            'css' => array('width' => '10%')
        );

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool2()->getDataTableConfiguration('#tableAssocVariantList2_'.$this->getPrefix(), null, null, array('order' => '[[ 0, "asc" ]]'));
        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentVarListActionAssignAction()
    {
        return new ViewModel();
    }

    public function renderTabContentVarListFilterLimitAction()
    {
        return new ViewModel();
    }

    public  function renderTabContentVarListFilterRefreshAction()
    {
        return new ViewModel();
    }

    public  function renderTabContentVarListFilterSearchAction()
    {
        return new ViewModel();
    }

    public function renderTabContentVarListActionViewAction()
    {
        return new ViewModel();
    }

    public function renderTabContentAssocVarListActionViewAction()
    {
        return new ViewModel();
    }


    public function getVariantsListAction()
    {
        $variantsTable = $this->getServiceLocator()->get('MelisEcomVariantTable');

        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();

        if($this->getRequest()->isPost()) {

            $colId = array_keys($this->getTool2()->getColumns());
            $variantId = (int) $this->getRequest()->getPost('variantId');
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];

            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];

            $draw = $this->getRequest()->getPost('draw');

            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
            $length = ($length>0) ? $length : null;
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'] ? $search['value'] : null;

            $dataCount =  $variantsTable->getTotalData();

            $getData = $variantsTable->getAssocVariantsList($variantId, $search, $start, $length, $selCol, $sortOrder);
            
            foreach($getData->toArray() as $key => $value) {
                if( (int) $value['var_id'] !== $variantId) {
                    $tableData[] = $value;
                }
            }
            $activeDom     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
            $inactiveDom   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
            $attributesDom = '<span class="btn btn-default cell-val-table" style="border-radius: 4px;color: #7D7B7B;">%s</span>';
            for($ctr = 0; $ctr < count($tableData); $ctr++)
            {
                // apply text limits
                foreach($tableData[$ctr] as $vKey => $vValue)
                {
                    $tableData[$ctr][$vKey] = $this->getTool2()->limitedText($vValue);
                }

                // manually modify value of the desired row
                // add DataTable RowID, this will be added in the <tr> tags in each rows
                $assigned = '';
                $varAttrText = '';
                $tableData[$ctr]['DT_RowId'] = $tableData[$ctr]['var_id'];

//                 if((isset($tableData[$ctr]['avar_id_1']) && !empty($tableData[$ctr]['avar_id_1'])) ||
//                     (isset($tableData[$ctr]['avar_id_2']) && !empty($tableData[$ctr]['avar_id_2']))
//                 ) {
//                     $assigned = '<span class="text-success"><i class="fa fa-check"></i></span>';
//                 }
                
                if($this->checkAssociation($variantId,$tableData[$ctr]['var_id'] )){
                    $assigned = '<span class="text-success"><i class="fa fa-check"></i></span>';
                }
                
                $varTextData = $this->getVarAttributeText((int) $tableData[$ctr]['var_id']);
                if($varTextData) {
                    foreach($varTextData as $vText) {
                        if(isset($vText))
                            $varAttrText .= sprintf($attributesDom, $vText);
                    }
                }

                $status = (int) $tableData[$ctr]['var_status'];
                $tableData[$ctr]['var_sku'] = '<span data-sku="'.$tableData[$ctr]['var_sku'].'">'.$tableData[$ctr]['var_sku'].'</span>';
                $tableData[$ctr]['var_status'] = $status ? $activeDom : $inactiveDom;
                $tableData[$ctr]['var_attributes'] = $varAttrText;
                $tableData[$ctr]['var_assigned'] = $assigned;
                $tableData[$ctr]['var_product_name'] = '<span data-prod-id="'.$this->getProductIdByVarId($tableData[$ctr]['var_id']).'">'.$this->getProductTextByVarId($tableData[$ctr]['var_id']).'</span>';

            }

        }

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => $variantsTable->getVarTotalFiltered(),
            'data' => $tableData,
        ));
    }

    public function getAssocVariantListAction()
    {
        $variantsTable = $this->getServiceLocator()->get('MelisEcomAssocVariantTable');

        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();

        if($this->getRequest()->isPost()) {

            $colId = array_keys($this->getTool1()->getColumns());

            $variantId = (int) $this->getRequest()->getPost('variantId');
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];

            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];

            $draw = $this->getRequest()->getPost('draw');

            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
            $length = ($length>0) ? $length : null;
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'] ? $search['value'] : null;

            $dataCount =  $variantsTable->getVariantAssociationById($variantId)->count();
            
            $getData = $variantsTable->getVariantAssociationById($variantId, $search, $start, $length, $selCol, $sortOrder);
            if($getData) {
                foreach($getData->toArray() as $key => $value) {
                    if( (int) $value['var_id'] !== $variantId) {
                        $tableData[] = $value;
                    }
                }

                $activeDom     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
                $inactiveDom   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
                $attributesDom = '<span class="btn btn-default cell-val-table" style="border-radius: 4px;color: #7D7B7B;">%s</span>';
                for($ctr = 0; $ctr < count($tableData); $ctr++)
                {
                    // apply text limits
                    foreach($tableData[$ctr] as $vKey => $vValue)
                    {
                        $tableData[$ctr][$vKey] = $this->getTool1()->limitedText($vValue);

                    }
                    $dataVarId = (int) $tableData[$ctr]['var_id'];

                    // manually modify value of the desired row
                    // add DataTable RowID, this will be added in the <tr> tags in each rows

                    $varAttrText = '';
                    $tableData[$ctr]['DT_RowId'] = $dataVarId;

                    $varTextData = $this->getVarAttributeText((int) $tableData[$ctr]['var_id']);
                    if($varTextData) {
                        foreach($varTextData as $vText) {
                            if(!empty($vText))
                                $varAttrText .= sprintf($attributesDom, $vText);
                        }
                    }

                    $status = (int) $tableData[$ctr]['var_status'];
                    $tableData[$ctr]['var_sku'] = '<span data-sku="'.$tableData[$ctr]['var_sku'].'">'.$tableData[$ctr]['var_sku'].'</span>';
                    $tableData[$ctr]['var_status'] = $status ? $activeDom : $inactiveDom;
                    $tableData[$ctr]['var_attributes'] = $varAttrText;
                    $tableData[$ctr]['var_product_name'] = $this->getProductTextByVarId($dataVarId);
                    $tableData[$ctr]['var_product_name'] = '<span data-prod-id="'.$this->getProductIdByVarId($dataVarId).'">'.$this->getProductTextByVarId($dataVarId).'</span>';
                }
            }

        }

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => $getData->count(),
            'data' => $tableData,
        ));
    }

    public function assignVariantAction()
    {
        $variantAssId = null;
        $success = 0;
        $textTitle = 'tr_meliscommerce_assoc_var_assoc_title';
        $textMessage = 'tr_meliscommerce_assoc_var_assoc_ko';
        $errors = array();

        if($this->getRequest()->isPost()) {
            $this->getEventManager()->trigger('meliscommerce_assoc_var_assoc_start', $this, $this->getRequest()->getPost());
            $assignedVar = (int) $this->getRequest()->getPost('assignVariantid');
            $assignTo  = (int) $this->getRequest()->getPost('assignToVariantId');

            $table = $this->getServiceLocator()->get('MelisEcomAssocVariantTable');
            $assocVarTable = $this->getServiceLocator()->get('MelisEcomAssocVariantTable');
            $isAssociated = $assocVarTable->getVariantAssociationData($assignTo, $assignedVar)->current();
            if(empty($isAssociated)) {
                $variantAssId = $assocVarTable->save(array(
                    'avar_one' =>  $assignTo, 
                    'avar_two' => $assignedVar, 
                    'avar_type_id' => 1
                ));
                
                $success = 1;
            }
            else {
                $textMessage = 'tr_meliscommerce_assoc_var_assoc_duplicate';
            }
            
            if($success) {
                $success = 1;
                $textMessage = 'tr_meliscommerce_assoc_var_assoc_ok';
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors
        );
        
        $this->getEventManager()->trigger('meliscommerce_assoc_var_assoc_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_VARIANT_ASSOCIATE', 'itemId' => $variantAssId)));
        
        return new JsonModel($response);
    }

    public function removeAssociationAction()
    {
        $variantAssId = null;
        $success = 0;
        $textTitle = 'tr_meliscommerce_assoc_var_remove_title';
        $textMessage = 'tr_meliscommerce_assoc_var_remove_ko';
        $errors = array();

        if($this->getRequest()->isPost()) {

            $this->getEventManager()->trigger('meliscommerce_assoc_var_remove_assoc_start', $this, $this->getRequest()->getPost());

            $assignedVar = (int) $this->getRequest()->getPost('assignedVariantId');
            $variantId   = (int) $this->getRequest()->getPost('variantId');
            $assocVarTable = $this->getServiceLocator()->get('MelisEcomAssocVariantTable');

            $data = $assocVarTable->getVariantAssociationData($variantId, $assignedVar)->current();
            if($data) {
                $variantAssId = $assocVarTable->deleteById($data->avar_id);
                if($variantAssId) {
                    $success = 1;
                    $textMessage = 'tr_meliscommerce_assoc_var_remove_ok';
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors
        );

        $this->getEventManager()->trigger('meliscommerce_assoc_var_remove_assoc_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_VARIANT_ASSOCIATE_REMOVE', 'itemId' => $variantAssId)));

        return new JsonModel($response);
    }

    public function getTool1()
    {
        $tool = $this->getServiceLocator()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_assoc_var');

        return $tool;
    }

    public function getTool2()
    {
        $tool = $this->getServiceLocator()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_assoc_var2');

        return $tool;
    }

    public function getVarAttributeText($variantId)
    {
        $assocVarTable = $this->getServiceLocator()->get('MelisComVariantService');
        $data = $assocVarTable->getVariantAttributesValuesById($variantId, $this->getTool2()->getCurrentLocaleID());
        $text = array();
        $ctr = 0;
        foreach($data as $attr) {
            $attrReference = $attr->atval_reference;
            $attTrans = $attr->atval_trans;
            foreach($attTrans as $attrText) {
                $text[$ctr] = $attrText->avt_v_varchar;
                if(empty($text)) {
                    $text[$ctr] = $attrReference;
                }
            }
            $ctr++;
        }

        return $text;
    }

    public function getProductTextByVarId($varId)
    {
        $varSvc = $this->getServiceLocator()->get('MelisComVariantService');
        $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
        $getData = $varSvc->getProductByVariantId( (int) $varId);
        $variantData = $getData->getVariant();
        if($variantData) {
            $prodId = $variantData->var_prd_id;
            return $prodSvc->getProductName($prodId, $this->getTool1()->getCurrentLocaleID());
        }

        return null;
    }

    public function getProductIdByVarId($varId)
    {
        $varSvc = $this->getServiceLocator()->get('MelisComVariantService');
        $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
        $getData = $varSvc->getProductByVariantId( (int) $varId);
        if($getData) {
            $varData = $getData->getVariant();
            if($varData) {
                return $varData->var_prd_id;
            }
        }

        return null;
    }
    
    public function checkAssociation($var_one, $var_two )
    {
        $variantAssocTable = $this->getServiceLocator()->get('MelisEcomAssocVariantTable');
        return $variantAssocTable->getVariantAssociationData($var_one, $var_two)->toArray();
    }


    public function testAction()
    {

        echo $this->getProductIdByVarId(5);
        $table = $this->getServiceLocator()->get('MelisEcomVariantTable');
        $data = $table->getAssocVariantsListById(6)->toArray();

        print '<pre>';
        print_r($data);
        print '</pre>';



        die;
    }
}