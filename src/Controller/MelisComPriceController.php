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
class MelisComPriceController extends AbstractActionController
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
        $ctyGeneral =   '<li class="">
                    		<a class="clearfix" data-toggle="tab" href="#'.$hrefGeneral.'" data-country="General" aria-expanded="true"><span>General</span>
                    			<i class="fa fa-globe"></i>
                    		</a>
                    	</li>';
        $ctyFormat =    '<li class="">
                    		<a class="clearfix" data-toggle="tab" href="#%s" data-country="%s" aria-expanded="true"><span>%s</span>
            
                    		</a>
                    	</li>';
    
        $countryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        $countries = $countryTable->fetchAll();
        $ctyData[] = $ctyGeneral;
        foreach ($countries as $country){
            $ctyData[] = sprintf($ctyFormat, $hrefCountry.str_replace(' ', '', $country->ctry_name), $country->ctry_name, $country->ctry_name);
        }
        $view = new ViewModel();
        $view->countries = $ctyData;
        $view->melisKey = $melisKey;
        $view->prefixId = $prefixId;
        return $view;
    }
    
    /**
     * renders the tab prices country form for prices
     * @return \Zend\View\Model\ViewModel
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
        
        $variantSvc = $this->getServiceLocator()->get('MelisComVariantService');
        $productSvc = $this->getServiceLocator()->get('MelisComProductService');
        $countryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');        
        
        if($productId || $page = 'newprod'){
            $prefixId = $productId.'_product';
            $formId = $productId.'_meliscommerce_product_prices_form_';
            $tabIdGen = $productId.'_product_price_General';
            $tabId = $productId.'_product_price_';
            $priceList = $productSvc->getProductPricesById($productId);
        }
        if(!empty($variantId) || $page == 'newvar'){
            $prefixId = $variantId.'_variant';
            $formId = $variantId.'_meliscommerce_variant_prices_form_';
            $tabIdGen = $variantId.'_variant_price_General';
            $tabId = $variantId.'_variant_price_';
            $priceList = $variantSvc->getVariantPricesById($variantId);
        }
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_prices/meliscommerce_prices_form','meliscommerce_prices_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $pricesForm = $factory->createForm($appConfigForm);
        $countries = $countryTable->fetchAll();
        $formatOnly = array('price_net', 'price_gross', 'price_vat_percent', 'price_vat_price', 'price_other_tax_price');
        $data = array();
        $c = 1;
        //set general price
        foreach($priceList as $price){
            if($price->price_country_id == 0){
                $data[0] = (array)$price;
            }
        }
        $data[0]['name'] = 'General';
        $data[0]['price_country_id'] = 'a0';
        $data[0]['price_currency'] = 'a0';
        $data[0]['tab_id'] = $tabIdGen;
    
        foreach($countries as $country){
            foreach((array)$priceList as $price){
                if($price->price_country_id == $country->ctry_id){
                    $data[$c] = (array)$price;
                }
            }
            $data[$c]['name'] = $country->ctry_name;
            $data[$c]['price_country_id'] = $country->ctry_id;
            $data[$c]['price_currency'] = $country->ctry_currency_id;
            $data[$c]['tab_id'] = $tabId.str_replace(' ', '', $country->ctry_name);
            
            $c++;
        }
        $c = 0;

        foreach ($data as $formValue){
            if(isset($formValue['price_net']))
                $formValue['price_net'] = $this->formatPrice((float) $formValue['price_net']);
            
            if(isset($formValue['price_gross'])) 
                $formValue['price_gross'] = $this->formatPrice((float) $formValue['price_gross']);
            
            if(isset($formValue['price_vat_percent']))
                $formValue['price_vat_percent'] = $this->formatPrice((float) $formValue['price_vat_percent']);
            
            if(isset($formValue['price_vat_price']))
                $formValue['price_vat_price'] = $this->formatPrice((float) $formValue['price_vat_price']);
            
            if(isset($formValue['price_other_tax_price']))
            $formValue['price_other_tax_price'] = $this->formatPrice((float) $formValue['price_other_tax_price']);
            
            $form = clone($pricesForm);
            $form->setAttribute('id',$formId.$formValue['name']);
            $form->setData($formValue);
            $forms[] = $form;
            $c++;
        }
        
        

        // echo '<pre>'; print_r($data); echo '</pre>'; die();
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
                
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigPriceForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_prices/meliscommerce_prices_form','meliscommerce_prices_form');
        $priceForm = $factory->createForm($appConfigPriceForm);

        $postValues = get_object_vars($this->getRequest()->getPost());
        if(!empty($postValues['priceForm'])){
            foreach($postValues['priceForm'] as $price){
                $countryId = (int) $price['price_country_id'];
                $currency = (int) $price['price_currency'];
                $price = array_map(function($item) { return $item ?: NULL; }, $price);
                unset($price['price_country_id']); // unset autofill datas
                unset( $price['price_currency']);
        
                if (array_filter($price)){
        
                    $price['price_country_id'] = $countryId;
                    $price['price_currency'] =  $currency;
        
                    $priceForm->setData($price);
        
                    if(!$priceForm->isValid()){
                        $success = false;
                        $errors[] = $this->getFormErrors($priceForm, $appConfigPriceForm);
                    }
                    $data['prices'][] = $priceForm->getData();
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
    
    private function formatPrice($price)
    {
        /**
         * if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    echo 'This is a server using Windows!';
} else {
    echo 'This is a server not using Windows!';
}
         * @var string $sessionLocale
         * http://php.net/manual/en/function.php-uname.php
         */
        $sessionLocale = 'en_EN';
        $container = new Container('meliscore');
        if (!empty($container['melis-lang-locale']))
            $sessionLocale = $container['melis-lang-locale'];
            
//         if($sessionLocale == 'en_EN') {
//             $sessionLocale = 'en_US';
//         }
        
        if($this->isWindows()) {
            //$sessionLocale = str_replace('_', '-', $sessionLocale);
            $sessionLocale = substr($sessionLocale, 0, 2);
        }

        setlocale(LC_MONETARY, $sessionLocale);
        $locale = localeconv();

        $curSymbol = trim($locale['currency_symbol']);
        $curSymbolInt = trim($locale['int_curr_symbol']);
        $decimalPoint = trim($locale['mon_decimal_point']);
        $thousandSeparator = trim($locale['mon_thousands_sep']);
        $fmt = new \NumberFormatter($sessionLocale, \NumberFormatter::CURRENCY );
        //$value =  money_format('%.2n', $price); // not windows compatible
        $value = $fmt->formatCurrency($price, $curSymbolInt);

        if(count($value) === 1) {
            $value = $fmt->formatCurrency($price, $curSymbolInt);
        }

        $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value); // replace special characters
        $value = preg_replace('/[a-zA-Z$ ]/', '', $value); // replace $, [space] and alphabets in price
        
        $newVal = $price;

        if($decimalPoint) {
            $value = explode($decimalPoint, $value);
            if(is_array($value)) {
                $tmpVal = str_replace($thousandSeparator, '', $value[0]);
                $newVal = $tmpVal . $decimalPoint . $value[1];
            }
        }
        


        return $newVal;
    }
    
    public function isWindows()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
           return true;
        } else {
            return false;
        }
    }
    
    
    public function testAction()
    {
//         $amount = 9.5;
//         $sessionLocale = '';
//         $container = new Container('meliscore');
//         if (!empty($container['melis-lang-locale']))
//             $sessionLocale = $container['melis-lang-locale'];
        

//         echo $this->formatPrice(1234567.891234567890000)."\n";
        $currentLocal = setlocale(LC_MONETARY, 'en');
        echo 'Current Locale: ' . $currentLocal;
        $locale = localeconv();
        print '<pre>';
        print_r($locale);
        print '</pre>';
        
        die;
    }

    
}