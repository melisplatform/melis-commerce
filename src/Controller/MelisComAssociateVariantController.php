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
        $variantId = $this->params()->fromQuery('variantId');
        
        $columns = $this->getTool1()->getColumns();

        $columns['action'] =  array(
            'text' => $this->getTool2()->getTranslation('tr_meliscommerce_assoc_var_col_action'),
            'css' => array('width' => '10%')
        );

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool1()->getDataTableConfiguration('#tableAssocVariantList1_'.$this->getPrefix());
        $view->prefixId = $this->getPrefix();
        $view->variantId = $variantId;
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
        $variantId = $this->params()->fromQuery('variantId');
        $columns = $this->getTool2()->getColumns();

        $columns['action'] =  array(
            'text' => $this->getTool2()->getTranslation('tr_meliscommerce_assoc_var_col_action'),
            'css' => array('width' => '10%')
        );

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool2()->getDataTableConfiguration('#tableAssocVariantList2_'.$this->getPrefix());
        $view->prefixId = $this->getPrefix();
        $view->variantId = $variantId;
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
    
    public function getProductListAction()
    {
        $draw = 0;
        $dataCount = 0;
        $tableData = array();
        $productFilter = array();
        $productList = array();
        
        if($this->getRequest()->isPost()) {
        
            // Getting Current Langauge ID
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();
    
            $productTable = $this->getServiceLocator()->get('MelisEcomProductTable');
    
            $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
            $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
    
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
    
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
    
            $draw = (int) $this->getRequest()->getPost('draw');
    
            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');
    
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
    
            $productList = $productTable->getProductList(null, null, true,  $start, $length, $sortOrder, $search);
    
            $productFilter = $productTable->getProductList(null, null, true,  null, null, $sortOrder, $search)->toArray();
    
            $activeDom     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
            $inactiveDom   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
            $prodImage = '<img src="%s" width="60" height="60" class="img-rounded img-responsive"/>';
    
            foreach($productList as $val) {
                $productId = $val->prd_id;
                
                $rowData = array(
                    'DT_RowId' => $productId,
                    'DT_RowClass' => 'showPrdVariants',
                    'prd_id' => $productId,
                    'var_status' => $val->prd_status ? $activeDom : $inactiveDom,
                    'prd_name' => $prodSvc->getProductName($productId, $langId),
                );
                array_push($tableData, $rowData);
            }
        }
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($productList),
            'recordsFiltered' =>  count($productFilter),
            'data' => $tableData,
        ));
    }
    
    /**
     * This will return the Product variant list
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderVariantAssocProductVariantsAction()
    {
        $productId = $this->params()->fromQuery('productId');
        $search = $this->params()->fromQuery('search');
        $curVariantId = $this->params()->fromQuery('variantId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        
        $hasVariant = false;
        $variants = array();
    
        $translator = $this->getServiceLocator()->get('translator');

        // Getting Current Langauge ID
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();


        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        // Getting the list of Activated Variant from Variant Service using the ProductId
        $variantData = $melisComVariantService->getVariantListByProductId($productId, $langId, null, null, null, 0, null, $search);

        foreach ($variantData As $val)
        {
            $variantId = $val->getId();
            
            // Excluding the current/selected variant on variant list
            if ($curVariantId != $variantId)
            {
                $hasVariant = true;
                
                $variant = $val->getVariant();
                
                // Checking if the variant is already associated to the current/selected variant
                $assigned = false;
                if($this->checkAssociation($curVariantId, $variantId)){
                    $assigned = true;
                }
                
                $variantRow = array(
                    'var_id' => $variant->var_id,
                    'label' => $translator->translate('tr_meliscommerce_order_checkout_common_sku'),
                    'var_sku' => $variant->var_sku,
                    'var_status' => $variant->var_status,
                    'var_assigned' => $assigned,
                );
                
                array_push($variants, $variantRow);
            }
        }
        
        $view->hasVariant = $hasVariant;
        $view->variants = $variants;
        $view->productId = $productId;
        $view->curVariantId = $curVariantId;
        $view->melisKey = $melisKey;
        return $view;
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

                
                if($this->checkAssociation($variantId,$tableData[$ctr]['var_id'] )){
                    $assigned = '<span class="text-success"><i class="fa fa-check"></i></span>';
                }
                
                $varTextData = $this->getVarAttributeText((int) $tableData[$ctr]['var_id']);
                if($varTextData) {
                    foreach($varTextData as $vText) {
                        if(isset($vText))
                            $varAttrText .= sprintf($attributesDom, $this->getTool2()->escapeHtml($vText));
                    }
                }

                $status = (int) $tableData[$ctr]['var_status'];
                $tableData[$ctr]['var_sku'] = '<span data-sku="'.$this->getTool2()->escapeHtml($tableData[$ctr]['var_sku']).'">'.$this->getTool2()->escapeHtml($tableData[$ctr]['var_sku']).'</span>';
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
                        $tableData[$ctr][$vKey] = $this->getTool1()->limitedText($this->getTool1()->escapeHtml($vValue));

                    }
                    $dataVarId = (int) $tableData[$ctr]['var_id'];

                    // manually modify value of the desired row
                    // add DataTable RowID, this will be added in the <tr> tags in each rows

                    $varAttrText = '';
                    $tableData[$ctr]['DT_RowId'] = $dataVarId;
                    $tableData[$ctr]['DT_RowAttr'] = array('data-productid' => $tableData[$ctr]['var_prd_id']);

                    $varTextData = $this->getVarAttributeText((int) $tableData[$ctr]['var_id']);
                    if($varTextData) {
                        foreach($varTextData as $vText) {
                            if(!empty($vText))
                                $varAttrText .= sprintf($attributesDom, $this->getTool1()->escapeHtml($vText));
                        }
                    }

                    $status = (int) $tableData[$ctr]['var_status'];
                    $tableData[$ctr]['var_sku'] = '<span data-sku="'.$this->getTool1()->escapeHtml($tableData[$ctr]['var_sku']).'">'.$this->getTool1()->escapeHtml($tableData[$ctr]['var_sku']).'</span>';
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
                $varAttrCol = 'avt_v_'.$attr->atype_column_value;
                $text[$ctr] = $attrText->$varAttrCol;
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
        $getData = $varSvc->getVariantById( (int) $varId);
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
        $getData = $varSvc->getVariantById( (int) $varId);
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


}