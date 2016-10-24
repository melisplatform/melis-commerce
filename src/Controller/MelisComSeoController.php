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

class MelisComSeoController extends AbstractActionController
{
    /**
     * This method Render SEO plugin
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSeoPluginAction()
    {
        $view = new ViewModel();
        $translator = $this->serviceLocator->get('translator');
        
        // Getting Type of the request
        $zoneConfig = $this->params()->fromRoute('zoneconfig', array());
        
        $type = null;
        $typeId = null;
        // Getting Type of Request from Config
        if (isset($zoneConfig['conf']['formType']))
        {
            $type = strtolower($zoneConfig['conf']['formType']);
        }
        
        // Checking type og the request
        switch ($type) {
            case 'category':
                $typeId = $this->params()->fromQuery('catId');
                break;
            case 'product':
                $typeId = $this->params()->fromQuery('productId');
                break;
            case 'variant':
                $typeId = $this->params()->fromQuery('variantId');
                break;
            default:
                break;
        }
        
        if (!is_null($type)&&!is_null($typeId))
        {
            // Getting Seo Data
            $melisComSeoService = $this->getServiceLocator()->get('MelisComSeoService');
            $seoData = $melisComSeoService->getSeoByType($type, $typeId);
            
            if($seoData)
            {
                $view->seoData = $seoData;
            }
        }
        
        // Getting Commerce Languages
        $ecomLangtable = $this->serviceLocator->get('MelisEcomLangTable');
        $ecomLang = $ecomLangtable->fetchAll()->toArray();
        
        // Category SEO Form
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_seo/meliscommerce_seo_form','meliscommerce_seo_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        // Getting Form input and push to array with empty/null value as Default Value for multiple Form
        $appConfigFormElements = $appConfigForm['elements'];
        $formDefaultValues = array();
        foreach ($appConfigFormElements As $key => $val)
        {
            $formDefaultValues[$val['spec']['name']] = null;
        }
        
        $view->melisKey = $this->params()->fromRoute('melisKey');
        $view->ecomLang = $ecomLang;
        $view->typeId = $typeId;
        $view->setVariable('meliscommerce_seo_form', $propertyForm);
        $view->formDefaultValues = $formDefaultValues;
        return $view;
    }
}