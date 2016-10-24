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

class MelisComCouponListController extends AbstractActionController
{
    /**
     * renders the coupon list page container
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponListAddCouponAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list page content container
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponListContentTableAction()
    {
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#tableCouponList', true);
        return $view;
    }
    
    /**
     * renders the coupon list content table filter limit
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponListContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list content table filter search
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponListContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list content table filter refresh
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponListContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list content table action info
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponListContentActionInfoAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the coupon list content table action delete
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponListContentActionDeleteAction()
    {
        return new ViewModel();
    }
    
    /**
     * retrieves the data for the coupon list table
     * @return \Zend\View\Model\JsonModel
     */
    public function getCouponListDataAction()
    {
        $success = 0;
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();
        $langId = $this->getTool()->getCurrentLocaleID();
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');        
        
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
            
            $couponList = $couponSvc->getCouponList(null, null, $start, $length, $colOrder, $search);
            $dataCount = count($couponList);
            $c = 0;
            foreach($couponList as $coupon){
                $status = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
                $coupon = $coupon->getCoupon();
                if($coupon->coup_status){
                    $status = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
                }
                
                $tableData[$c]['DT_RowId'] = $coupon->coup_id;
                $tableData[$c]['coup_id'] = $coupon->coup_id;
                $tableData[$c]['coup_status'] = $status;
                $tableData[$c]['coup_code'] = $coupon->coup_code;
                $tableData[$c]['coup_percentage'] = $coupon->coup_percentage;
                $tableData[$c]['coup_discount_value'] = $coupon->coup_discount_value;
                $tableData[$c]['coup_current_use_number'] = $coupon->coup_current_use_number;
                $c++;
            }
            
            
        }
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataCount,
            'data' => $tableData,
        ));
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_list');
    
        return $melisTool;
    
    }
    public function testAction()
    {
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');
        $result = $couponSvc->getCouponList();
        echo '<pre>';
        print_r($result);
        echo '</pre>';die();
    }
}