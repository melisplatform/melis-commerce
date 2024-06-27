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

class MelisComCouponListController extends MelisAbstractActionController
{
    /**
     * renders the coupon list page container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListPageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the coupon list page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListHeaderContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the coupon list page left header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListHeaderLeftContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the coupon list page right header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListHeaderRightContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the add new coupon button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListAddCouponAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the coupon list page table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListContentTableAction()
    {
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#tableCouponList', true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /**
     * renders the coupon list content table filter limit
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list content table filter search
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list content table filter refresh
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list content table action info
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListContentActionInfoAction()
    {
        return new ViewModel();
    }
    private function getMelisKeyTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_list');

        return $melisTool;

    }

    /**
     * renders the coupon list content table action delete
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCouponListContentActionDeleteAction()
    {
        return new ViewModel();
    }
    
    /**
     * retrieves the data for the coupon list table
     * @return \Laminas\View\Model\JsonModel
     */
    public function getCouponListDataAction()
    {
        $success = 0;
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $dataFiltered = 0;
        $tableData = array();
        $langId = $this->getTool()->getCurrentLocaleID();
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        $couponOrderTable = $this->getServiceManager()->get('MelisEcomCouponOrderTable');
        
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
            
            $postValues = $this->getRequest()->getPost();
            
            $tmp = $couponSvc->getCouponList(null, null, null, null, null, $search);
            $dataFiltered = count($tmp);
            
            $couponList = $couponSvc->getCouponList(null, null, $start, $length, $colOrder, $search);
            $dataCount = count($couponList);
            $c = 0;
            foreach($couponList as $coupon){
                $status = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
                $coupon = $coupon->getCoupon();
                $couponClient = array();
                $couponOrder = array();
                
                if($coupon->coup_status){
                    $status = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
                }
                
                foreach($couponOrderTable->getEntryByField('cord_coupon_id', $coupon->coup_id) as $coupOrder){
                    $couponOrder[] = $coupOrder;
                }
                
                // Identify used coupons
                if(array_filter($couponOrder)){
                    $tableData[$c]['DT_RowClass'] = "couponUsed";
                }

                $copCode = $this->getTool()->escapeHtml($coupon->coup_code);
                $copCode = "<span class='d-none td-tooltip'>".$copCode."</span>".mb_strimwidth($copCode, 0, 30, '...');
                $tableData[$c]['DT_RowId'] = (int) $coupon->coup_id;
                $tableData[$c]['coup_id'] =  (int) $coupon->coup_id;
                $tableData[$c]['coup_status'] = $status;
                $tableData[$c]['coup_code'] = $copCode;
                $tableData[$c]['coup_percentage'] = !empty($coupon->coup_percentage)? $coupon->coup_percentage : '&nbsp;';
                $tableData[$c]['coup_discount_value'] = !empty($coupon->coup_discount_value)? $coupon->coup_discount_value . "€": '&nbsp;';
                $tableData[$c]['coup_current_use_number'] = $coupon->coup_current_use_number;
                $tableData[$c]['DT_RowClass'] = ($coupon->coup_current_use_number) ? 'couponListNoDeleteButton' : '';
                $c++;
            }
            
            
        }
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataFiltered,
            'data' => $tableData,
        ));
    }
    
    /**
     * This method deletes coupons that are not assigned or unused coupons
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function deleteCouponAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $couponId = null;
        $textMessage = 'tr_meliscommerce_coupon_delete_fail';
        $textTitle = 'tr_meliscommerce_coupon_list_page_coupon';
        
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        if($this->getRequest()->isPost()){
            $this->getEventManager()->trigger('meliscommerce_coupon_delete_start', $this, array());
            $postValues = $this->getRequest()->getPost()->toArray();
            $couponId = $postValues['couponId'];
            if($couponSvc->deleteCouponById($couponId)){
                $success = 1;
                $textMessage = 'tr_meliscommerce_coupon_delete_success';
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_coupon_delete_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_COUPON_DELETE', 'itemId' => $couponId)));
        
        return new JsonModel($response);
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_list');
    
        return $melisTool;
    
    }
    public function testAction()
    {
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        $result = $couponSvc->getCouponList();
        echo '<pre>';
        print_r($result);
        echo '</pre>';die();
    }


}