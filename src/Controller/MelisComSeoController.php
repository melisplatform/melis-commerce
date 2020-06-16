<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\ViewModel;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComSeoController extends MelisAbstractActionController
{
    /**
     * This method Render SEO plugin
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderSeoPluginAction()
    {
        $view = new ViewModel();
        $translator = $this->getServiceManager()->get('translator');
        
        // Getting Type of the request
        $zoneConfig = $this->params()->fromRoute('zoneconfig', array());
        
        // Category SEO Form
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_seo/meliscommerce_seo_form','meliscommerce_seo_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
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
                $propertyForm->get('eseo_page_id')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Page_id_seo tooltip'));
                $propertyForm->get('eseo_meta_title')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Meta_title_seo tooltip'));
                $propertyForm->get('eseo_meta_description')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Meta_description_seo tooltip'));
                $propertyForm->get('eseo_url')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Url_seo tooltip'));
                $propertyForm->get('eseo_url_redirect')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Url_redirect_seo tooltip'));
                $propertyForm->get('eseo_url_301')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Url_301_seo tooltip'));
                break;
            case 'product':
                $typeId = $this->params()->fromQuery('productId');
                break;
            case 'variant':
                $typeId = $this->params()->fromQuery('variantId');
                $propertyForm->get('eseo_page_id')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Page_id_var tooltip'));
                $propertyForm->get('eseo_meta_title')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Meta_title_var tooltip'));
                $propertyForm->get('eseo_meta_description')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Meta_description_var tooltip'));
                $propertyForm->get('eseo_url')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Url_var tooltip'));
                $propertyForm->get('eseo_url_redirect')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Url_redirect_var tooltip'));
                $propertyForm->get('eseo_url_301')->setOption('tooltip', $translator->translate('tr_meliscommerce_seo_Url_301_var tooltip'));
                break;
            default:
                break;
        }
        
        if (!is_null($type)&&!is_null($typeId))
        {
            // Getting Seo Data
            $melisComSeoService = $this->getServiceManager()->get('MelisComSeoService');
            $seoData = $melisComSeoService->getSeoByType($type, $typeId);
            
            if($seoData)
            {
                $view->seoData = $seoData;
            }
        }
        
        // Getting Commerce Languages
        $ecomLangtable = $this->getServiceManager()->get('MelisEcomLangTable');
        $ecomLang = $ecomLangtable->langOrderByName()->toArray();
        
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

    public function  getAllSeoKeywordsAction()
    {
        $view = new ViewModel();
        $translator = $this->getServiceManager()->get('translator');
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_seo/meliscommerce_seo_form','meliscommerce_seo_form');

        $view->setVariable('meliscommerce_seo_form', $propertyForm);
        $view->formDefaultValues = $formDefaultValues;
        return $view;

    }
}