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

class MelisComOrderController extends MelisAbstractActionController
{
    /**
     * renders the page container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersPageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $this->setOrderVariables($orderId);

        // reference that will be used anywhere, for now it will be used in documents
        $container = new Container('meliscommerce');
        $container['documents'] = array('docRelationType' => 'order', 'docRelationId' => $orderId);

        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersHeaderContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', ''); 
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders teh page header left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersHeaderLeftContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the page header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the page header right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersHeaderRightContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders header save button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersHeaderRightContainerSaveAction()
    {
        $orderStatus = '';
        if(!empty($this->layout()->order)){
            $orderStatus = $this->layout()->order->ord_status;
        }
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->orderStatus = $orderStatus;
        return $view;
    }
    
    /**
     * renders the orders header cancel button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersHeaderRightContainerCancelAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the orders content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders tabs container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders tab template
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the tabs content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the tabs content template
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the tabs content header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the tab content header left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentLeftHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the tab content header right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentRightHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the tab content header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentLeftHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the tab content details container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentDetailsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tab main left details container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentMainDetailsLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tab sub header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentDetailsSubHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tab sub header left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentDetailsSubHeaderLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tab sub header right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentDetailsSubHeaderRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tab sub header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentDetailsSubHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tabs content details sub contents container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentDetailsSubContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    
    /**
     * render the main sub content order form
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentMainOrderFormAction()
    {
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        
        $confOrder = $melisCoreConfig->getItem('meliscommerce/conf/orderStatus');
        $statusAttrib = array();
        $status = array();
        $statusButtons = '';
        $currStatus = '';
        $disabled = '';
        $class = 'mainOrderStatus';
        $button = '<a href="#" class="%s" data-statusid="%s" style="text-decoration:none">
                        <span %s class="btn order-status-%s">%s</span>
                </a>';
        
        if(isset($this->layout()->order)){
            $currStatus = $this->layout()->order->ord_status;
        }
        
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $langId = $this->getTool()->getCurrentLocaleID();
        
        $statuses = $orderSvc->getOrderStatusList($langId, true);
        
        $statuses = empty($statuses)? $orderSvc->getOrderStatusList(1, true) : $statuses;
        
        foreach($statuses as $orderStatus){
            $status[] = $orderStatus;
            $disabled = '';
            $class = 'mainOrderStatus';
            
            if($currStatus == $confOrder['cancelled']){
                $disabled = 'disabled';
                $class = '';
            }
            
            if($currStatus == $orderStatus->osta_id){
                $class = 'mainOrderStatus selectedStatus';
                $statusButtons .= sprintf($button, $class, $orderStatus->osta_id, $disabled, $orderStatus->osta_id, $orderStatus->ostt_status_name);
            }
            
            if(($orderStatus->osta_id != $confOrder['temporary'] && $orderStatus->osta_id != $confOrder['errorPayment']) 
                && $orderStatus->osta_id != $currStatus){
                
                $statusButtons .= sprintf($button, $class, $orderStatus->osta_id, $disabled, $orderStatus->osta_id, $orderStatus->ostt_status_name);
            }
            
        }
        
        $infoForm = 'meliscommerce_order_information_form';
    
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/'.$infoForm,$infoForm);
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        
        $orderInfoForm = $factory->createForm($appConfigForm);
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        $view->status = $status;
        $view->currStatus = $currStatus;
        $view->statusButtons = $statusButtons;
        $view->setVariable($infoForm, $orderInfoForm);
        return $view;
    }

    public function renderOrdersContentTabsContentFileAttachmentsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tab basket list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentBasketListAction()
    {
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => '', 'width' => '0%');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $tableConfig = $this->getTool()->getDataTableConfiguration('#'.$orderId.'_tableOrderBasketList', null, null, array('order' => '[[ 0, "desc" ]]'));
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $tableConfig;
        return $view;
    }
    
    /**
     * renders the orders content tab address left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentAddressDetailsLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
     * renders the orders content tab address tabs
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrdersContentTabsContentAddressDetailsTabsAction()
    {   
        $addTypeTable = $this->getServiceManager()->get('MelisEcomClientAddressTypeTransTable');
        $addressTypes = array();
        
        $types = $addTypeTable->getAddressTypeTransByLangId($this->getTool()->getCurrentLocaleID());
        
        $types = empty($types->count())? $addTypeTable->getAddressTypeTransByLangId(1) : $types;
        
        foreach($types as $type){
            $addressTypes[] = $type;
        }
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->addressTypes = $addressTypes;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the orders content tab address right container
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentAddressDetailsRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the orders content tab address form
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentAddressDetailsAddressFormAction()
    {
        $forms = array();
        $addTypeTable = $this->getServiceManager()->get('MelisEcomClientAddressTypeTransTable');
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_address_form','meliscommerce_order_address_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $addressForm = $factory->createForm($appConfigForm);
        $types = $addTypeTable->getAddressTypeTransByLangId($this->getTool()->getCurrentLocaleID());
        
        $types = empty($types->count())? $addTypeTable->getAddressTypeTransByLangId(1) : $types;
        
        foreach($types as $type){
            $addressTypes[] = $type;
            $form = clone $addressForm;
            $form->setName('address['.$type->catype_code.']');
            foreach($this->layout()->addresses as $address){                
                if($address->oadd_type == $type->catypt_type_id){                    
                    $form->setData((array)$address);                    
                }               
            }
            $form->prepare();
            $forms[] = $form;
        } 
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        $view->addressTypes = $addressTypes;
        $view->orderAdressForms = $forms;
        return $view;
    }
    
    /**
        * renders the orders content tab content details div col-xs-12 col-md-12
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentDetailsLargeAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the orders content tab content payment list
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentPaymentDetailsContentListAction()
    {        
        $payments = $this->layout()->payment;
        //order by most recent date
        usort($payments, function($a, $b) {
            return strtotime($b->opay_date_payment) - strtotime($a->opay_date_payment);
        });
        $coupons = array();
        $pays = array();
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        $couponOrderTable = $this->getServiceManager()->get('MelisEcomCouponOrderTable');
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        foreach($payments as $payment){
            $payment->opay_date_payment = $this->getTool()->dateFormatLocale($payment->opay_date_payment);
            $currency = $currencyTable->getEntryById($payment->opay_currency_id)->current();
            
            if(empty($currency)){
                $currency = $currencyTable->getEntryByField('cur_default', 1)->current();
            }
            
            $payment->cur_symbol = $currency->cur_symbol;
            
            foreach($couponSvc->getCouponList($payment->opay_order_id, null, null, null, 'coup_id asc', null) as $coupon){
                $tmp = $coupon->getCoupon();
                $qty = 0;
                foreach($couponOrderTable->getCouponDiscountedBasketItems($tmp->coup_id, $payment->opay_order_id) as $coup){
                    $qty += $coup->cord_quantity_used;
                }
                $tmp->qty_used = $qty;
                $coupons[] = $tmp;
            }
            $payment->{'coupons'} = $coupons;
            $pays[] = $payment;
        }
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->payments = $pays;
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;

        $melisGeneralService = $this->getServiceManager()->get('MelisGeneralService');
        $melisGeneralService->sendEvent('melisorder_payment_details_view', array('view' => $view));

        return $view;
    }
    
    /**
        * renders the content tab messages right head add shipping button
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentShippingRightHeaderAddAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the orders content tab content shipping list
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentShippingDetailsContentListAction()
    {
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $this->setOrderVariables($orderId);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_shipping_form','meliscommerce_order_shipping_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $shippingForm = $factory->createForm($appConfigForm);
        $ships = $this->layout()->shipping;
        //order by most recent date
        usort($ships, function($a, $b) {
            return strtotime($b->oship_date_sent) - strtotime($a->oship_date_sent);
        });
        $forms = array();
        $shippings = array();
        foreach($ships as $shipping){
            $shipping->oship_date_sent = $this->getTool()->dateFormatLocale($shipping->oship_date_sent);
            $form = clone $shippingForm;
            $form->setData((array)$shipping);
            $form->prepare();
            $forms[] = $form;
            $shippings[] = $shipping;
        }

        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');        
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        $view->orderShippingForm = $forms;
        $view->datePickerInit = $this->getTool()->datePickerInit('shippingDate');
        $view->shippings = $shippings;

        return $view;
    }
    
    /**
        * renders the content tab messages right head add message button
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentMessagesRightHeaderAddAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the orders content tab messages details container
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentMessagesDetailsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the orders content tab messages message form
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentMessagesMessageFormAction()
    {
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigMessageForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_message_form','meliscommerce_order_message_form');
        $messageform = $factory->createForm($appConfigMessageForm);
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        $view->messageForm = $messageform;
        return $view;
    }
    
    /**
        * renders the orders content tab messages timeline container
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentMessagesTimelineContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the order content basket table filter limit
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrderContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
        * renders the order content basket table filter search
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrderContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
        * renders the order content table filter refresh
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrderContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
        * renders the order content action info
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrderContentActionInfoAction()
    {
        return new ViewModel();
    }
    
    /**
        * renders the orders content tab messages timeline 
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrdersContentTabsContentMessagesTimelineAction()
    {
        $messages = array();
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $clientSvc  = $this->getServiceManager()->get('MelisComClientService');
        $this->setOrderVariables($orderId);
        $userTable = $this->getServiceManager()->get('MelisCoreTableUser');

        $image = '';
        foreach($this->layout()->messages as $orderMessage){
            foreach($clientSvc->getClientByIdAndClientPerson($orderMessage->omsg_client_id, $orderMessage->omsg_client_person_id)->getPersons() as $clientPerson){
                $person = $clientPerson;
            }
            $role = 'Client';
            $name = $person->cper_name. ' '.$person->cper_middle_name.' '.$person->cper_firstname;
            $email = $person->cper_email;
            $image = 'data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMOaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAyMSA3OS4xNTU3NzIsIDIwMTQvMDEvMTMtMTk6NDQ6MDAgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjdGMTg1NkM4Q0EyQjExRTVBMDVGRTA3NDczNUZDNEZCIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjdGMTg1NkM3Q0EyQjExRTVBMDVGRTA3NDczNUZDNEZCIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIEltYWdlUmVhZHkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0iNkE1QjI0NzYyRjREN0Y0NzdGNUIyRDJBNzlCMDVGMDciIHN0UmVmOmRvY3VtZW50SUQ9IjZBNUIyNDc2MkY0RDdGNDc3RjVCMkQyQTc5QjA1RjA3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4ADkFkb2JlAGTAAAAAAf/bAIQABgQEBAUEBgUFBgkGBQYJCwgGBggLDAoKCwoKDBAMDAwMDAwQDA4PEA8ODBMTFBQTExwbGxscHx8fHx8fHx8fHwEHBwcNDA0YEBAYGhURFRofHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8f/8AAEQgAMgAyAwERAAIRAQMRAf/EAGwAAQACAwEBAAAAAAAAAAAAAAAFBgIDBAEIAQEAAAAAAAAAAAAAAAAAAAAAEAABAwIEAwcDBQAAAAAAAAABAAIDEQQhMRIFoTITQWFxkUIjBlGB0VJTFBUWEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwD6pQEEVcb/AAseWwRmWmGutG/bOqDCP5CK+7AQ3tLDXgQEErDNHNG2SN2pjhUEIM0BAQcm6ue3bpy00Omle4mh4IKygIJv465/SnaeQPBb4kYoJZAQEHJu3U/r5tAqaYgCp01x4IKygIJr491OnPUe3qGk0zNMcfJBLoCAgIKvuNoba7cz0Pq+M9xOX2KDRHG+WRsTOd50trlUoLXbwtggjhbiGNDa/WiDYgICDXPcwQM1zPDB35nwCCt7hefy7kyAFrGjSwHOmdT4oNEUjopWSt5mODhXLBBZrTcba6aNDqSeqN2Dh+UHSgIK/d73cyktg9mPsdm8/hBHuLnOLnEucc3ONT5lB4gIFOGSDstt1vYCBr6sY9D8fJ2aCR/0Ft+0/lr2c36c+KCCQEBAQEBAQf/Z';
            if(!is_null($orderMessage->omsg_user_id)){
                $user = $userTable->getEntryById($orderMessage->omsg_user_id)->current();

                $role = 'Admin';
                $name = 'ID ('.$orderMessage->omsg_user_id.')';
                $email = '';

                if(!empty($user)){
                    $name = $user->usr_lastname.' '.$user->usr_firstname;
                    $email = $user->usr_email;
                    $image = ($user->usr_image) ? 'data:image/jpeg;base64,'. base64_encode($user->usr_image) : $image;
                }                                
            }
            $message = array(
                'day' => date("d" ,strtotime($orderMessage->omsg_date_creation)),
                'month' => date("M" ,strtotime($orderMessage->omsg_date_creation)),
                'date' => strtotime($orderMessage->omsg_date_creation),// used for sorting by date
                'urole_name' => $role,
                'usr_image'=> $image,
                'name' => $name,
                'email' => $email,
                'time' => date("H:i" ,strtotime($orderMessage->omsg_date_creation)),
                'omsg_message' => $orderMessage->omsg_message,
            );
            $messages[] = $message;
        }
        //sort by date descending
        usort($messages, function ($a,$b){  
        return $b['date'] - $a['date']; 
        });
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');       
        $view->messages = $messages;
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        return $view;
    }
    
    /**
        * renders the order modal container
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrderModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(true);
        return $view;
    }
    
    /**
        * renders the order modal content for adding new shipping
        * @return \Laminas\View\Model\ViewModel
        */
    public function renderOrderModalContentShippingFormAction()
    {
        $orderId = (int) $this->params()->fromQuery('orderId');
        
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_shipping_form','meliscommerce_order_shipping_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $shippingForm = $factory->createForm($appConfigForm);
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $orderId = (int) $this->params()->fromQuery('orderId', '');
        $view->melisKey = $melisKey;
        $view->orderId = $orderId;
        $view->orderShippingForm = $shippingForm;
        $view->datePickerInit = $this->getTool()->datePickerInit('shippingDate');
        return $view;
    }
    
    /**
        * returns the basket data list
        * @return \Laminas\View\Model\JsonModel
        */
    public function getBasketDataAction()
    {   
        $getValues = $this->getRequest()->getQuery()->toArray();
        $orderId = $this->getRequest()->getPost('orderId');
        $translator = $this->getServiceManager()->get('translator');
        $melisTool = $this->getTool();
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $docService = $this->getServiceManager()->get('MelisComDocumentService');
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();
        $prodId = null;
        $image      = '<img src="%s" width="60" height="60" class="rounded-circle img-fluid"/>';
        $categoryDom    = '<span class="cell-val-table" style="margin:0 2px 4px 0;display:inline-block;padding: 3px 10px;background: #ECEBEB;border-radius: 4px;color: #7D7B7B;">%s</span>';
        
        if($this->getRequest()->isPost()) {
        
            $colId = array_keys($melisTool->getColumns());
        
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
        
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
            $order = $selCol. ' '. $sortOrder;
            $draw = $this->getRequest()->getPost('draw');
        
            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
        
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];


            $toolTipTextTag = '<a id="row-%s" class="price-log-data-tooltip" data-order-basket-id="%s" data-hasqtip="1" aria-describedby="qtip-%s"><span class="hidden price-log-data">%s</span>%s</a>';

            
            $basketList = $orderSvc->getOrderBasketByOrderId($orderId, $start, $length, $search, $order);
            
            $dataCount = count($basketList);
            foreach($basketList as $key => $basket){
                $default = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAgAAZABkAAD/7AARRHVja3kAAQAEAAAAPAAA/+EDLWh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8APD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMTQgNzkuMTUxNDgxLCAyMDEzLzAzLzEzLTEyOjA5OjE1ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozRkNFMzU3RDg2QUYxMUU1OEM4OENCQkI2QTc0MTkwRSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozRkNFMzU3Qzg2QUYxMUU1OEM4OENCQkI2QTc0MTkwRSIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChNYWNpbnRvc2gpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MDEwNzlDODNCQThDMTFFMjg5NTlFMDAzODgzMjZDMkIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MDEwNzlDODRCQThDMTFFMjg5NTlFMDAzODgzMjZDMkIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAAGBAQEBQQGBQUGCQYFBgkLCAYGCAsMCgoLCgoMEAwMDAwMDBAMDg8QDw4MExMUFBMTHBsbGxwfHx8fHx8fHx8fAQcHBw0MDRgQEBgaFREVGh8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx//wAARCAHgAoADAREAAhEBAxEB/8QAgQABAAMBAQEBAQAAAAAAAAAAAAYHCAUEAwIBAQEAAAAAAAAAAAAAAAAAAAAAEAEAAAQBBgoHBQgBBQAAAAAAAQIDBQQRkwY2BxchMXHREtKzVHRVQVETU7QVFmGBInLDkaEyQlKCIxSx4WKSosIRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AL4AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABGLztG0Xs9yrW7HVqkmKodH2kstOaaH45YTw4YfZNAHj3u6E95q5qYDe7oT3mrmpgN7uhPeauamA3u6E95q5qYDe7oT3mrmpgN7uhPeauamA3u6E95q5qYDe7oT3mrmpgN7uhPeauamA3u6E95q5qYDe7oT3mrmpgN7uhPeauamA3u6E95q5qYDe7oT3mrmpgN7uhPeauamA3u6E95q5qYDe7oT3mrmpgN7uhPeauamA3u6E95q5qYDe7oT3mrmpgN7uhPeauamA3u6E95q5qYH2wO1HRDG43D4PD4ipNXxNSSjShGnNCEZ54wllyx5YgloAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM97VtfbnyUPh6YIkAAAAAAAAAAAAAAAAAAAAADr6H62WXx2G7WUGmgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAZ72ra+3PkofD0wRIHpwtruWLkjUwuErYiSWPRmnpU554Qj6oxlhEH2+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVA+n795bisxU6oH0/fvLcVmKnVB+K9mu+HpTVq+BxFKlL/FUnpTyywy8HDGMMgPGDr6H62WXx2G7WUGmgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAZ72ra+3PkofD0wRIF17D9XMb4qPZygsYAAAAAAAAAAAAAAAAAEW2nai3T8tPtZAZ3B19D9bLL47DdrKDTQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM97VtfbnyUPh6YIkC69h+ruN8VHs5QWMAAAAAAAAAAABGMIQyx4IQ44gg1+2vaM2yvNh8PCpcK0kck8aOSFOEfV048f3QB8bPtl0axteFHGU6tvjNHJLUqZJ6f3zS8MP2AntOpTqSS1Kc0J6c8ITSTyxywjCPDCMIwB+gARbafqLdPy0+1kBncHX0P1ssvjsN2soNNAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAz3tW19ufJQ+HpgiQLr2H6u43xUezlBYwAAAAAAAAAAAK+2x6R4i3WWhbsLPGnVuM00Ks8sckYUZIQ6UP7ozQhyZQUeAC4NimkWIr0MVZMRPGeXDQhWwkYxyxlkjHJPJyQjGEYcoLRABFtp+ot0/LT7WQGdwdfQ/Wyy+Ow3ayg00AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADPe1bX258lD4emCJAuvYfq7jfFR7OUFjAAA5mkl/wlhs9e54mHSkpQhCSnCOSM880ckssOUH7sN9t98ttK4YCp06NSH4pY/wAUk0OOSaHojAHQAAAAAABVu3K11qmEt1ykhGalQmno1ow/l9pkjJH/ANYwBUAALP2HWyvNcbhc4yxhQp0oYeWb0RnnmhNGEOSEv7wXEACLbT9Rbp+Wn2sgM7g6+h+tll8dhu1lBpoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGe9q2vtz5KHw9MESBdWw/V7HeK/TlBY4AAKQ2w6U/MbxLaMPPlwlujH2sYcU1eP8X/AIQ4OXKCPaGaZY/Rm5Qr0stXB1Ywhi8Ll4J5fXD1TQ9EQaEtN2wF2t9LH4CrCrhq0Mss0OOEfTLND0Rh6YA9gAAAAAPPcLfhLhgq2CxlOFbDV5YyVKcfTCIKhvuxO7UsRNPZsRTxOGmjGMlKtH2dSWHqy5OjNy8APjZ9il/r15fmlelhMNCP4/Zze0qRh6oQh+GH3xBb9ms1vs1upW/AU/Z4elDghxzTRjxzTR9MYg9oAIttP1Fun5afayAzuDr6H62WXx2G7WUGmgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAZ72ra+3PkofD0wRIF1bD9Xsd4r9OUFjgAj+nOksmj2j2IxsIw/2p/8AFg5Y+mrNDgjk/wC2H4gZvqVJ6lSapUmjNPPGM000eGMYx4YxiD+Ak+gum+M0ZuHDlq22vGH+1hv3dOT1TQ/eDQVvx+DuGDpYzB1YVsNXlhNTqS8UYc/rB6AAAR2xad2C83PF23DVejisNPNLThNkhCtLLxz04+mH2feCRAAAAAAAi20/UW6flp9rIDO4OvofrZZfHYbtZQaaAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABnvatr7c+Sh8PTBEgXVsP1ex3iv05QWOACgdqelPzrSGbD0J+lgLdlo0cnFNUy/5J/wBsMkPsgCGAAAl+z7T3EaN4z2GIjNVtFeb/AD0ocMacY8HtJIev1w9IL9wuKw2Lw1PE4apLWw9aWE9KrJHLLNLHijAH1BCdqulfyWxRweHn6NwuMI06eTjkpcVSf/5h/wBAUPQr1qFaStRnmp1qcYTU6kkYwmlmhxRhGALp2e7UKN1hTtd5nlpXLglo4iOSWSv9kfRLP+6ILFAAAAABFtp+ot0/LT7WQGdwdfQ/Wyy+Ow3ayg00AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADPe1bX258lD4emCJAurYfq9jvFfpygscET2laUwsOjtT2M/Rx+Ny0MLk45csPx1P7Zf35AZ6AAAABN9nO0Gro/iYYDHTTT2etNw+mNGaP88sP6f6offyheVTH4OTAzY+atL/py041o14Ryy+zhDpdLL6sgM36XaR19IL7iLjUywpTR6GGpx/kpS/ww5fTH7QcYCEYwjlhwRhxRBa2z3ar0PZ2nSCrll4JMNcJo8XohLWj/wATft9YLalmhNCE0scsI8MIw4owB/QAAARbafqLdPy0+1kBncHX0P1ssvjsN2soNNAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAz3tW19ufJQ+HpgiQLq2H6vY7xX6coLGjGEIRjGOSEOGMY8WQGdNoWlEdINIq1enNlwWHy0cHD0dCWPDP8A3x4eQEaAAAAAB2ael17k0cq6PwrZbfUnhNkjl6UssI5YySx/pjHhyA4wAAALA2fbTsRZo07bdppq9qj+GlV/inocn9Un2ej0eoF2YbE4fFYeniMPUlq0KssJqdSSOWWaEfTCMAfUAAEW2n6i3T8tPtZAZ3B19D9bLL47DdrKDTQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM97VtfbnyUPh6YIkC6th+r2O8V+nKCRbSMViMLoVdKuHnjTqRpyydKHH0ak8sk0PvlmjAGcwAAAAAAAAAAAAS3QbaDcNGq8KFTLiLVUmy1cNGPDJGPHPTy8UfXDiiC+LTdrfdsBTx2ArQrYarD8M0OOEfTLND0Rh6YA9gAIttP1Fun5afayAzuDr6H62WXx2G7WUGmgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAZ72ra+3PkofD0wRIF1bD9Xsd4r9OUHf2m06lTQi5SU5Jp54wp5JZYRmjH/LL6IAz/wDLLl3Stm5+YD5Zcu6Vs3PzAfLLl3Stm5+YD5Zcu6Vs3PzAfLLl3Stm5+YD5Zcu6Vs3PzAfLLl3Stm5+YD5Zcu6Vs3PzAfLLl3Stm5+YD5Zcu6Vs3PzAfLLl3Stm5+YD5Zcu6Vs3PzAfLLl3Stm5+YD5Zcu6Vs3PzA7uid90o0ax3t8Jhq0+HnjD/Ywk0k/QqQh93BN6ogvjR+/4K94CXF4aWenHirUKssZJ6c+TLGWaEf+YA6YIttP1Fun5afayAzuDr6H62WXx2G7WUGmgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAZ72ra+3PkofD0wRIF1bD9Xsd4r9OUFjgAAAAAAAAAAAAAAAAAi20/UW6flp9rIDO4OvofrZZfHYbtZQaaAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABnvatr7c+Sh8PTBEgSHRzTvSDR7CVMLbZ6UtKrP7Sbp04Tx6WSEOOPIDrb4tNfe0MzDnA3xaa+9oZmHOBvi0197QzMOcDfFpr72hmYc4G+LTX3tDMw5wN8WmvvaGZhzgb4tNfe0MzDnA3xaa+9oZmHOBvi0197QzMOcDfFpr72hmYc4G+LTX3tDMw5wN8WmvvaGZhzgb4tNfe0MzDnA3xaa+9oZmHOBvi0197QzMOcDfFpr72hmYc4G+LTX3tDMw5wN8WmvvaGZhzg8V42maU3e217djKlGOGxEIQqQlpwljklmhNDJHlgCKg6+h+tll8dhu1lBpoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGe9q2vtz5KHw9MESAAAAAAAAAAAAAAAAAAAAAB19D9bLL47DdrKDTQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIHpPsowd+vmJutS4VKE+I6GWlLTlmhDoU5afHGMOPo5QcvcVbvNq2al6wG4q3ebVs1L1gNxVu82rZqXrAbird5tWzUvWA3FW7zatmpesBuKt3m1bNS9YDcVbvNq2al6wG4q3ebVs1L1gNxVu82rZqXrAbird5tWzUvWA3FW7zatmpesBuKt3m1bNS9YDcVbvNq2al6wG4q3ebVs1L1gNxVu82rZqXrAbird5tWzUvWA3FW7zatmpesBuKt3m1bNS9YDcVbvNq2al6wG4q3ebVs1L1gNxVu82rZqXrAbird5tWzUvWB6rVsZwNuumDuElzq1JsJXp14U405YQmjTmhNky5fTkBYwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP/9k=';
                $imageSrc = '';    
                $category = '';
                $var = $variantSvc->getVariantById($basket->obas_variant_id, $this->getTool()->getCurrentLocaleID());
                
                if(!empty($basket->obas_variant_id)){
                    $imageSrc = $docService->getDocDefaultImageFilePath('variant', $basket->obas_variant_id);
                }
                
                // if no variant image then get product image
                if($imageSrc == $default){
                    $prod = $prodSvc->getProductById($var->getVariant()->var_prd_id, $this->getTool()->getCurrentLocaleID());                    
                    $imageSrc = $docService->getDocDefaultImageFilePath('product', $prod->getId());
                    $prodId = $prod->getId();
                }
                
                // if no image exist for variant and product, assign a default one
                if(empty($imageSrc)){
                    $imageSrc = $default;
                }
                
                if(!empty($basket->obas_category_name)){
                    $category = sprintf($categoryDom, $this->getTool()->escapeHtml($basket->obas_category_name));
                }

                $tableData[$key]= array(                    
                    'obas_id' => $basket->obas_id,                    
                    'obas_sku' => $this->getTool()->escapeHtml($basket->obas_sku),
                    'image' => sprintf($image, $imageSrc),
                    'obas_quantity' => $basket->obas_quantity,
                    'obas_price_net' => $basket->obas_price_net . "€",
                    'obas_product_name' => $this->getTool()->escapeHtml($basket->obas_product_name),
                    'obas_category_name' => $category,
                    'DT_RowId' => $basket->obas_id,
                    'DT_RowAttr' => array('data-variantid' => $basket->obas_variant_id, 'data-productid' => $prodId, 'data-sku' => $basket->obas_sku),                    
                );

                $itemPrice = $basket->obas_price_net . "€";
                // Detect if Mobile remove qTipTable
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
                    $tableData[$key]['obas_price_net'] = sprintf($toolTipTextTag, ($key+1), $basket->obas_id, ($key+1), $basket->obas_price_log, $itemPrice);
            }
        }
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataCount,
            'data' => $tableData,
        ));
    }
    
    public function saveOrderAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $orderId = null;
        $clientId = null;
        $textMessage = 'tr_meliscommerce_order_page_save_fail';
        $textTitle = 'tr_meliscommerce_order_page';   
        
        $container = new Container('meliscommerce');
        unset($container['order-valid-data']);
        //get services        
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        // trigger validate and saving
        if($this->getRequest()->isPost()){
            $this->getEventManager()->trigger('meliscommerce_order_save_start', $this, array());

            if (!empty($container['order-valid-data'])){
                if (!empty($container['order-valid-data']['success'])){
                    $success = $container['order-valid-data']['success'];
                    $textMessage = 'tr_meliscommerce_order_page_save_success';
                    
                    // Getting Order Client details
                    $postValues = $this->getRequest()->getPost()->toArray();
                    $postValues = $this->getTool()->sanitizeRecursive($postValues);
                    $orderId =  $postValues['orderId'];
                    $clientData = $orderSvc->getOrderById($orderId);
                    $client = $clientData->getClient();
                    $clientId = $client->cli_id;
                }
                if (!empty($container['order-valid-data']['errors']))
                    $errors = $container['order-valid-data']['errors'];
                if (!empty($container['order-valid-data']['datas']))
                    $data = $container['order-valid-data']['datas'];
            }
            unset($container['order-valid-data']);            
        }       
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
            'clientId' => $clientId,
        );
        
        $this->getEventManager()->trigger('meliscommerce_order_save_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_ORDER_UPDATE', 'itemId' => $orderId)));
        
        return new JsonModel($response);
    }
    
    public function saveOrderDataAction()
    {
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $variantStockTbl = $this->getServiceManager()->get('MelisEcomVariantStockTable');
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $confOrder = $melisCoreConfig->getItem('meliscommerce/conf/orderStatus');
        $success = false;
        $errors = array();
        $data = array();
        $container = new Container('meliscommerce');
        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);
        
        if (!empty($container['order-valid-data'])){
            if (!empty($container['order-valid-data']['success'])){
                $success = $container['order-valid-data']['success'];
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_order_page_save_success');
            }
            if (!empty($container['order-valid-data']['errors']))
                $errors = $container['order-valid-data']['errors'];
            if (!empty($container['order-valid-data']['datas']))
                $formData = $container['order-valid-data']['datas'];
        }
        unset($container['order-valid-data']);

        if(!$errors && !empty($formData)){
            $formData['baskets'] = array();
            $formData['payment'] = array();
            // no data for baskets and payment yet
            $orderId =  isset($formData['order']['ord_id']) ? $formData['order']['ord_id'] : null;
            
            unset($formData['order']['ord_id']);
            $reference = $formData['order']['ord_reference'];
            $result = $orderSvc->saveOrder($formData['order'], $formData['baskets'], $formData['billing'],
                $formData['delivery'], $formData['payment'], $formData['shipping'], $orderId);
            
            if($result){
                $success = true;
                $data['ord_reference'] = $reference;
                
                //if status was cancelled replenish stocks
                if($formData['order']['ord_status'] == $confOrder['cancelled'] && $postValues['lastStatus'] != $confOrder['cancelled']){
                    $orderEntity = $orderSvc->getOrderById($orderId);
                    $order = $orderEntity->getOrder();
                    $basket = $orderEntity->getBasket();
                    
                    foreach($basket as $item){
                        $tmp = array();
                    
                        $variantStock = $variantStockTbl->getStocksByVariantId($item->obas_variant_id, $order->ord_country_id)->current();
                        if(!empty($variantStock)){
                            $tmp['stock_quantity'] = $item->obas_quantity + $variantStock->stock_quantity;
                            $variantSvc->saveVariantStocks($tmp, $variantStock->stock_id);
                        }else{
                            $variantStock = $variantStockTbl->getStocksByVariantId($item->obas_variant_id, 0)->current();
                            $tmp['stock_quantity'] = $item->obas_quantity + $variantStock->stock_quantity;
                            $variantSvc->saveVariantStocks($tmp, $variantStock->stock_id);
                        }
                    }
                }
            }
        }        
        
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }
    
/**
    * validates the order form
    * @param unknown $orderValues
    * @return [] result
    */
    public function validateOrderFormAction()
    {
        $data['order'] = array();
        $errors = array();
        $success = true;
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigOrderForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_information_form','meliscommerce_order_information_form');
        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);

        // form validations
        if(!empty($postValues['order'])){
            foreach($postValues['order'] as $order){
                $orderForm = $factory->createForm($appConfigOrderForm);
                $orderForm->setData($order);
                if(!$orderForm->isValid()){
                    $success = 0;
                    $errors[] = $this->getFormErrors($orderForm, $appConfigOrderForm);
                }
            
                $data['order'] = $order;
            }        
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }
    
    /**
        * validates the address form
        * @param [] $addressValues array of address values
        * @return [] result
        */
    public function validateAddressFormAction()
    {
        $errors = array();
        $success = true;
        $address['delivery'] = array();
        $address['billing'] = array();
        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigAddressForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_address_form','meliscommerce_order_address_form');
        if(!empty($postValues['address'])){
            foreach($postValues['address'] as $key => $value){               
                foreach($value as $addr){
                    $addressForm = $factory->createForm($appConfigAddressForm);
                    if(!array_filter($addr)&& $key == 'BIL'){                       
                        $success = 0;
                        $errors[]['address'] = $this->getTool()->getTranslation('tr_meliscommerce_address_billing_form_empty');
                    }
                    if(array_filter($addr)){
                        $addressForm->setData($addr);                        
                        if(!$addressForm->isValid()){
                            $success = 0;
                            $errors[] = $this->getFormErrors($addressForm, $appConfigAddressForm);
                        }
                        $data = $addressForm->getData();
                        $data['oadd_civility'] = !empty($data['oadd_civility'])? $data['oadd_civility'] : 0;
                        if(empty($data['oadd_id'])){
                            $data['oadd_creation_date'] = date('Y-m-d H:i:s');
                        }
                        if($key == 'DEL')
                            $address['delivery'][] = $data;
                        if($key == 'BIL')
                            $address['billing'][]  = $data;
                    }                    
                }
            }
        }       
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $address,
        );
        return new JsonModel($results);        
    }
    
    /**
        * validates the shippign form
        * @param [] $shippingForms array of shipping values
        * @return \MelisCommerce\Controller\errors[][]
        */
    public function validateShippingFormAction()
    {
        $data['shipping'] = array();
        $errors = array();
        $success = true;
        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigShippingForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_shipping_form','meliscommerce_order_shipping_form');
        
        if(!empty($postValues['orderShippingForm'])){
            foreach($postValues['orderShippingForm'] as $tracking){
                $shippingForm = $factory->createForm($appConfigShippingForm);
                $shippingForm->setData($tracking);
                if(!$shippingForm->isValid()){
                    $success = 0;
                    $errors[] = $this->getFormErrors($shippingForm, $appConfigShippingForm);
                }
                $shipping = $shippingForm->getData();
                $shipping['oship_date_sent'] = $this->getTool()->localeDateToSql($shipping['oship_date_sent']);
                $data['shipping'][] = $shipping;
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results); 
    }
    
    public function saveOrderMessageAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $orderId = null;
        $orderMsgId = null;
        $textMessage = 'tr_meliscommerce_order_message_save_fail';
        $textTitle = 'tr_meliscommerce_order_page';
        $this->getEventManager()->trigger('meliscommerce_order_message_save_start', $this, array());
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigMessageForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_message_form','meliscommerce_order_message_form');
        $messageForm = $factory->createForm($appConfigMessageForm);
        
        if($this->getRequest()->isPost()){
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            $order = $melisComOrderService->getOrderById($postValues['orderId'], $this->getTool()->getCurrentLocaleID())->getOrder();
            $userId = !empty($this->getTool()->getCurrentUserId())? $this->getTool()->getCurrentUserId(): '';
            $messageForm->setData($postValues);
            if(!$messageForm->isValid()){
                $errors = $this->getFormErrors($messageForm, $appConfigMessageForm);
            }
            
            if(empty($errors)){
                $orderMesasge = [];
                //prepare order msg data
                foreach($postValues as $key => $val){
                    if (strpos($key, 'omsg_') !== false) {
                        $orderMesasge[$key] = $val;
                    }
                }

                $orderMesasge['omsg_order_id'] = $postValues['orderId'];
                $orderMesasge['omsg_type'] = !empty($postValues['omsg_type']) ? $postValues['omsg_type'] : 'MSG';
                $orderMesasge['omsg_client_id'] = $order->ord_client_id;
                $orderMesasge['omsg_client_person_id'] = $order->ord_client_person_id;
                $orderMesasge['omsg_user_id'] = $userId;
                $orderMesasge['omsg_date_creation'] = date('Y-m-d H:i:s');

                $orderMsgId = $melisComOrderService->saveOrderMessage($orderMesasge);
                if($orderMsgId){
                    $success = 1;
                    $textMessage = 'tr_meliscommerce_order_message_save_success';
                }
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_order_message_save_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_ORDER_MESSAGE_ADD', 'itemId' => $orderMsgId)));
        
        return new JsonModel($response);
        
    }
    
    /**
        * Retrieves  form errors
        * @param object $form the form object
        * @param object $formConfig the app config of the form
        * @return errors[] | null
        */
    private function getFormErrors($form, $formConfig)
    {
        $appConfigFormElements = $formConfig['elements'];
        $errors = $form->getMessages();

        foreach ($errors as $keyError => $valueError)
        {
            foreach ($appConfigFormElements as $keyForm => $valueForm)
            {
                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                {
                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }
        
        return $errors;
    }
    
    /**
        * set the order variables
        * @param unknown $orderId
        */
    private function setOrderVariables($orderId)
    {
        $layoutVar = array();
        $langId =  $this->getTool()->getCurrentLocaleID();
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        
        $resultData = $melisComOrderService->getOrderById($orderId, $this->getTool()->getCurrentLocaleID());
        
        $statusTrans = $melisComOrderService->getOrderStatusByOrderId($orderId);
        
        foreach($statusTrans as $trans){
            if($trans->ostt_lang_id == $langId){
                $orderStatus = $trans;
            }
        }
        
        $orderStatus = empty($orderStatus)? $statusTrans[0] : $orderStatus;
        
        $layoutVar['order']     = $resultData->getOrder();
        $layoutVar['status']    = $orderStatus;
        $layoutVar['client']    = $resultData->getClient();
        $layoutVar['person']    = $resultData->getPerson();
        $layoutVar['addresses'] = $resultData->getAddresses();
        $layoutVar['basket']    = $resultData->getBasket();
        $layoutVar['payment']   = $resultData->getPayment();
        $layoutVar['shipping']  = $resultData->getShipping();
        $layoutVar['messages']  = $resultData->getMessages();
        $this->layout()->setVariables( array_merge( array(
            'orderId' => $orderId,
        ), $layoutVar));
    }
    
    /**
        * Returns the Tool Service Class
        * @return MelisCoreTool
        */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_order_basket_list');
    
        return $melisTool;
    }

    public function getOrderFormAction()
    {
        $data['order'] = array();
        $errors = array();
        $success = true;
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigOrderForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_orders/meliscommerce_order_information_form','meliscommerce_order_information_form');



        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }
}