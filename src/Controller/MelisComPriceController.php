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

class MelisComPriceController extends MelisAbstractActionController
{
    private function getPrefix()
    {
        $productId = (int) $this->params()->fromQuery('productId', '');
        $variantId = (int) $this->params()->fromQuery('variantId', '');
        $page = $this->params()->fromQuery('page', '');
        if($productId || $page = 'newprod'){
            $prefixId = $productId.'_product';
        }
        if(!empty($variantId) || $page == 'newvar'){
            $prefixId = $variantId.'_variant';
        }
        
        return $prefixId;
    }
    /**
     * renders the tab head main tab
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab individual contents container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab individual content header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentHeaderContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab individual content header left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentHeaderLeftAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab individual content header left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentHeaderRightAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab individual content header
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab individual general container, for main,price,stocks
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentGeneralContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab prices left content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentLeftContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the sub content heading container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabSubHeadingAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the sub content header text
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabSubHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab prices right content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesTabContentRightContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prefixId = $this->getPrefix();
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the prices country list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesCountryListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');
        $variantId = (int) $this->params()->fromQuery('variantId', '');
        $page = $this->params()->fromQuery('page', '');
        $prefixId = '';
        if($productId || $page = 'newprod'){
            $prefixId = $productId.'_product';
            $hrefGeneral = $productId.'_product_price_General';
            $hrefCountry = $productId.'_product_price_'; 
        }
        if(!empty($variantId) || $page == 'newvar'){
            $prefixId = $variantId.'_variant';
            $hrefGeneral = $variantId.'_variant_price_General';
            $hrefCountry = $variantId.'_variant_price_';
        }
        $ctyGeneral =   '<li class="nav-item">
                    		<a class="nav-link" data-bs-toggle="tab" data-bs-target="#'.$hrefGeneral.'" href="#'.$hrefGeneral.'" data-country="General" data-symbol="" aria-expanded="true"><span>General</span>
                    			<i class="fa fa-globe"></i>
                    		</a>
                    	</li>';
        $ctyFormat =    '<li class="nav-item">
                    		<a class="nav-link" data-bs-toggle="tab" data-bs-target="#%s" href="#%s" data-country="%s" data-symbol="%s" aria-expanded="true"><span>%s</span>
                                %s
                    		</a>
                    	</li>';
    
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $countries = $countryTable->getCountries();
        $ctyData[] = $ctyGeneral;
        foreach ($countries as $country){

            $imageData = $country->ctry_flag;
            $image = !empty($imageData) ? '<span class="float-right"><img src="data:image/jpeg;base64,'. ($imageData) .'" class="imgDisplay float-right"/></span>' : '<i class="fa fa-globe"></i>';
            $ctyData[] = sprintf($ctyFormat, $hrefCountry.str_replace(' ', '', $country->ctry_name), $country->ctry_name, $country->cur_symbol, $country->ctry_name, $image);
        }
        $view = new ViewModel();
        $view->countries = $ctyData;
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }

    private function trackPriceHistory()
    {
        $errors = array();
        foreach ($form->getMessages() as $keyError => $valueError){
            foreach ($formConfig['elements'] as $keyForm => $valueForm){
                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])){

                }
            }
        }
        return $errors;
    }

    /**
     * renders the tab prices country form for prices
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderPricesFormAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');
        $variantId = (int) $this->params()->fromQuery('variantId', '');
        $page = $this->params()->fromQuery('page', '');
        $prefixId = '';
        $forms = [];
        $formId = '';
        
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $productSvc = $this->getServiceManager()->get('MelisComProductService');
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $translator = $this->getServiceManager()->get('translator');

        //get clients group list
        $groupService = $this->getServiceManager()->get('MelisComClientGroupsService');
        $groups = $groupService->getClientsGroupList(null,null,null, null,null,null, $status = 1);

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_prices/meliscommerce_prices_form','meliscommerce_prices_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $pricesForm = $factory->createForm($appConfigForm);
        
        if($productId || $page = 'newprod'){
            $prefixId = $productId.'_product';
            $formId = $productId.'_meliscommerce_product_prices_form_';
            $tabIdGen = $productId.'_product_price_General';
            $tabId = $productId.'_product_price_';
            $priceList = $productSvc->getProductPricesById($productId, null, -1);
        }
        if(!empty($variantId) || $page == 'newvar'){
            $prefixId = $variantId.'_variant';
            $formId = $variantId.'_meliscommerce_variant_prices_form_';
            $tabIdGen = $variantId.'_variant_price_General';
            $tabId = $variantId.'_variant_price_';
            $priceList = $variantSvc->getVariantPricesById($variantId, null, -1);
            
            $pricesForm->get('price_net')->setOption('tooltip', $translator->translate('tr_meliscommerce_variant_prices_variant_price_label_var tooltip'));
            $pricesForm->get('price_gross')->setOption('tooltip', $translator->translate('tr_meliscommerce_variant_prices_variant_price_gross_label_var tooltip'));
            $pricesForm->get('price_vat_percent')->setOption('tooltip', $translator->translate('tr_meliscommerce_variant_prices_variant_price_vat_percent_label_var tooltip'));
            $pricesForm->get('price_vat_price')->setOption('tooltip', $translator->translate('tr_meliscommerce_variant_prices_variant_price_vat_price_label_var tooltip'));
            $pricesForm->get('price_other_tax_price')->setOption('tooltip', $translator->translate('tr_meliscommerce_variant_prices_variant_price_other_tax_price_label_var tooltip'));
        }

        $countries = $countryTable->getCountries();
        $formatOnly = array('price_net', 'price_gross', 'price_vat_percent', 'price_vat_price', 'price_other_tax_price');
        $data = array();
        $c = 1;
        //set general price
        foreach($groups as $genGroup) {
            foreach ($priceList as $price) {
                if ($price->price_country_id == -1) {
                    if($genGroup['cgroup_id'] == $price->price_group_id)
                        $data[0]['data'][$genGroup['cgroup_id']] = (array)$price;
                    else
                        $data[0]['data'][$genGroup['cgroup_id']]['price_group_id'] = $genGroup['cgroup_id'];
                }
            }
            $data[0]['data'][$genGroup['cgroup_id']]['name'] = 'General';
            $data[0]['data'][$genGroup['cgroup_id']]['price_country_id'] = '-1';
            $data[0]['data'][$genGroup['cgroup_id']]['price_currency'] = '-1';
            $data[0]['tab_id'] = $tabIdGen;
            $data[0]['data'][$genGroup['cgroup_id']]['group_name'] = $genGroup['cgroup_name'];
            if(empty($data[0]['data'][$genGroup['cgroup_id']]['price_group_id']))
                $data[0]['data'][$genGroup['cgroup_id']]['price_group_id'] = $genGroup['cgroup_id'];
        }

        foreach($countries as $country){
            foreach($groups as $genGroup) {
                foreach((array)$priceList as $price){
                    if($price->price_country_id == $country->ctry_id){
                        if($genGroup['cgroup_id'] == $price->price_group_id)
                            $data[$c]['data'][$genGroup['cgroup_id']] = (array)$price;
                        else
                            $data[$c]['data'][$genGroup['cgroup_id']]['price_group_id'] = $genGroup['cgroup_id'];
                    }
                }
                $data[$c]['data'][$genGroup['cgroup_id']]['name'] = $country->ctry_name;
                $data[$c]['data'][$genGroup['cgroup_id']]['price_country_id'] = $country->ctry_id;
                $data[$c]['data'][$genGroup['cgroup_id']]['price_currency'] = $country->ctry_currency_id;
                $data[$c]['tab_id'] = $tabId.str_replace(' ', '', $country->ctry_name);
                $data[$c]['data'][$genGroup['cgroup_id']]['group_name'] = $genGroup['cgroup_name'];
                if(empty($data[$c]['data'][$genGroup['cgroup_id']]['price_group_id']))
                    $data[$c]['data'][$genGroup['cgroup_id']]['price_group_id'] = $genGroup['cgroup_id'];
            }
            $c++;
        }
        $c = 0;

        foreach ($data as $formValue){
            foreach($formValue['data'] as $groupId => $group) {
                if(isset($group['price_net']))
                    $group['price_net'] = $productSvc->formatPrice((float) $group['price_net']);

                if(isset($group['price_gross']))
                    $group['price_gross'] = $productSvc->formatPrice((float) $group['price_gross']);

                if(isset($group['price_vat_percent']))
                    $group['price_vat_percent'] = $productSvc->formatPrice((float) $group['price_vat_percent']);

                if(isset($group['price_vat_price']))
                    $group['price_vat_price'] = $productSvc->formatPrice((float) $group['price_vat_price']);

                if(isset($group['price_other_tax_price']))
                    $group['price_other_tax_price'] = $productSvc->formatPrice((float) $group['price_other_tax_price']);

                $form = clone($pricesForm);
                $form->setAttribute('id', $formId . $group['name'].'_'.$group['group_name']);
                $form->setData($group);
                $forms[$c]['data'][$groupId] = $form;
            }
            $c++;
        }

        $view = new ViewModel();
        $view->data = $data;
        $view->melisKey = $melisKey;
        $view->forms = $forms;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    public function validatePriceFormAction()
    {
        $data = array(
            'prices' => array()
        );
        $errors = array();
        $success = true;
        $priceTable = $this->getServiceManager()->get('MelisEcomPriceTable');
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigPriceForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_prices/meliscommerce_prices_form','meliscommerce_prices_form');
        $priceForm = $factory->createForm($appConfigPriceForm);

        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);
        if(!empty($postValues['priceForm'])){
            foreach($postValues['priceForm'] as $price){
                $tmp = $price;
                
                unset($tmp['price_country_id']); // unset autofill datas
                unset($tmp['price_currency']);
                unset($tmp['price_var_id']);
                unset($tmp['price_id']);
                
                if (array_filter($tmp)){        
                    $price['price_country_id'] = (int) $price['price_country_id'];
                    $price['price_currency'] = (int) $price['price_currency'];
                    $priceForm->setData($price);
        
                    if(!$priceForm->isValid()){
                        $success = false;
                        $priceError = $priceForm->getMessages();
                        foreach ($priceError as $keyError => $valueError)
                        {
                            foreach ($appConfigPriceForm['elements'] as $keyForm => $valueForm)
                            {
                                if ($valueForm['spec']['name'] == $keyError &&
                                    !empty($valueForm['spec']['options']['label']))
                                    $priceError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                        array_push($errors, $priceError);
                    }
                    $price = array_map(function($item) { return is_numeric($item) ? $item: NULL; }, $priceForm->getData());
                    $data['prices'][] = $price;
                }else{
                    if(isset($price['price_id'])){
                        $priceTable->deleteById($price['price_id']);
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
    * This method deletes the price entry if the country its affected is deleted
    * @return \Laminas\View\Model\JsonModel
    */
    public function priceCountryDeletedAction()
    {
        $success = 0;
        $errors = array();
        $data = array();
        $countryId = -1;
   
        $priceTable = $this->getServiceManager()->get('MelisEcomPriceTable');
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        
        $countryId = (int) $this->getRequest()->getPost('id');
        
        //check if country is already deleted
        $country = $countryTable->getEntryById($countryId);
        if($country->count() === 0){            
            if(is_numeric($countryId)){
                $priceTable->deleteByField('price_country_id', $countryId);
                $success = 1;
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
     * Retrieves  form errors
     * @param object $form the form object
     * @param object $formConfig the app config of the form
     * @return errors[] | null
     */
    private function getFormErrors($form, $formConfig)
    {
        $errors = array();
        foreach ($form->getMessages() as $keyError => $valueError){
            foreach ($formConfig['elements'] as $keyForm => $valueForm){
                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])){
                    $key = $valueForm['spec']['options']['label'];
                    $errors[$key] = $valueError;
                }
            }
        }
        return $errors;
    }

    public function testAction()
    {
//         $amount = 9.5;
//         $sessionLocale = '';
//         $container = new Container('meliscore');
//         if (!empty($container['melis-lang-locale']))
//             $sessionLocale = $container['melis-lang-locale'];
        

//         echo $this->formatPrice(1234567.891234567890000)."\n";
//         $currentLocal = setlocale(LC_MONETARY, 'en');
//         echo 'Current Locale: ' . $currentLocal;
//         $locale = localeconv();
//         print '<pre>';
//         print_r($locale);
//         print '</pre>';
        $num = number_format('2,211.124', 2, '.', '');
        echo $num;
        die;
    }

    private function getTool()
    {
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        return $tool;
    }


    
}