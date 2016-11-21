<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Zend\View\Model\JsonModel;

/**
 * MelisCommerce SEO Service
 *
 */

class MelisComSeoService extends MelisComGeneralService
{
    /**
     * This method will validate the Seo Data from Request/Post
     * and return as Validated Seo Data
     * @param String $type
     * @param Array $seoPostValues
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function validateSEOData($type, $seoPostValues)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_validate_seo_start', $arrayParameters);
        $result = array();
        // Service implementation start
        $type = strtolower($arrayParameters['type']);
        $seoPostValues = $arrayParameters['seoPostValues'];
        
        $translator = $this->getServiceLocator()->get('translator');
        $success = 0;
        $errors = array();
        $seoData = array();
        
        // Getting Commerce Languages
        $ecomLangtable = $this->serviceLocator->get('MelisEcomLangTable');
        $ecomLang = $ecomLangtable->fetchAll()->toArray();
        
        $hasManyLang = (count($ecomLang)>1) ? true : false;
        
        $ecomSeotable = $this->serviceLocator->get('MelisEcomSeoTable');
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_seo/meliscommerce_seo_form','meliscommerce_seo_form');
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        
        $appConfigFormElements = $appConfigForm['elements'];
        
        $ecomLangtable = $this->serviceLocator->get('MelisEcomLangTable');
        
        $seoErrors = array();
        foreach ($seoPostValues As $key => $val)
        {
            $propertyForm = $factory->createForm($appConfigForm);
            $propertyForm->setData($val);
            
            $langName = '';
            if ($hasManyLang)
            {
                $elangName = $ecomLangtable->getEntryById($key)->current();
                if (!empty($elangName))
                {
                    $langName = ' ('.ucfirst($elangName->elang_name).') ';
                }
            }
            
            
            // Checking if Seo Fields/input has data then checking if Page Id has Data
            $colOptionalField = array('eseo_meta_title', 'eseo_meta_description', 'eseo_url', 'eseo_url_redirect', 'eseo_url_301');
            foreach ($colOptionalField As $oVal)
            {
                if (!empty(($val[$oVal])))
                {
                    if (empty($val['eseo_page_id']))
                    {
                        $errors['eseo_page_id'] = array(
                            'label' => $translator->translate('tr_meliscommerce_seo_Page_id'),
                            $key.'_notEmpty' => $langName.$translator->translate('tr_meliscommerce_seo_Seo_input_empty')
                        );
                        
                        $includeThisSeoDataFalse = false;
                    }
                }
            }
            
            // Checking if Seo Page Id is Numeric
            if (!empty($val['eseo_page_id']))
            {
                if (!is_numeric($val['eseo_page_id']))
                {
                    $errors['eseo_page_id'] = array(
                        'label' => $translator->translate('tr_meliscommerce_seo_Page_id'),
                        $key.'_notNumeric' => $langName.$translator->translate('tr_meliscommerce_seo_Page_id_invalid')
                    );
                }
            }
            
            // Checking of the SEO url uniqueness on Database
            if (!empty($val['eseo_url'])){
            
                if (in_array($type, array('category', 'product', 'variant')))
                {
                    $seoUrl = $ecomSeotable->getEntryByField('eseo_url', $val['eseo_url'])->toArray();
                    
                    if (!empty($seoUrl))
                    {
                        $hasUrlError = false;
                        
                        if (empty($val['eseo_'.$type.'_id']))
                        {
                            $hasUrlError = true;
                        }
                        else
                        {
                            if ($val['eseo_'.$type.'_id'] != $seoUrl[0]['eseo_'.$type.'_id'])
                            {
                                $hasUrlError = true;
                            }
                        }
                        
                        if ($hasUrlError)
                        {
                            if (isset($errors['eseo_url']))
                            {
                                $errors['eseo_url'][$key.'_Exist'] = $langName.$translator->translate('tr_meliscommerce_seo_url_exist');
                            }
                            else
                            {
                                $errors['eseo_url'] = array(
                                    'label' => $translator->translate('tr_meliscommerce_seo_Url'),
                                    $key.'_Exist' => $langName.$translator->translate('tr_meliscommerce_seo_url_exist')
                                );
                            }
                        }
                    }
                }
            }
            
            if($propertyForm->isValid())
            {
                array_push($seoData, $propertyForm->getData());
            }
            else
            {
                $seoErr = $propertyForm->getMessages();
                 
                foreach ($seoErr as $keyError => $valueError)
                {
                    foreach ($appConfigFormElements as $keyForm => $valueForm)
                    {
                        if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                        {
                            foreach ($valueError As $keyValErr => $valValerr)
                            {
                                if (isset($errors[$keyError]))
                                {
                                    $errors[$keyError][$key.'_'.$keyValErr] = $langName.$valValerr;
                                }
                                else
                                {
                                    $errors[$keyError] = array(
                                        'label' => $valueForm['spec']['options']['label'],
                                        $key.'_'.$keyValErr => $langName.$valValerr
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if (empty($errors))
        {
            $success = 1;
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('seo_errors' => $errors),
            'datas' =>  array('seo_data' => $seoData),
        );
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_validate_deo_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    public function saveSeoDataAction($type, $typeId, $seoData)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_saving_seo_start', $arrayParameters);
        $successFlag = true;
        
        // Service implementation start
        $ecomSeotable = $this->serviceLocator->get('MelisEcomSeoTable');
        
        foreach ($arrayParameters['seoData'] As $val)
        {
            $eseoId = (!empty($val['eseo_id'])) ? $val['eseo_id'] : null;
            unset($val['eseo_id']);
            $val['eseo_'.strtolower($type).'_id'] = $typeId;
            
            // Checking if input fields has values
            $saveDataFlag = false;
            $colOptionalField = array('eseo_page_id','eseo_meta_title', 'eseo_meta_description', 'eseo_url', 'eseo_url_redirect', 'eseo_url_301');
            foreach ($colOptionalField As $oVal)
            {
                if (!empty(($val[$oVal])))
                {
                    $saveDataFlag = true;
                }
            }
            
            if ($saveDataFlag)
            {
                $result = $this->saveSeo($val, $eseoId);
                if ($result!=true)
                {
                    return false;
                }
            }
            else 
            {
                if (!is_null($eseoId))
                {
                    // Delete row if the field of the form is empty
                    $ecomSeotable->deleteById($eseoId);
                }
            }
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_saving_seo_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method will save SEO
     *
     * @param array $seo reflects to melis_ecom_seo table
     * @param int $seoId
     *
     * @return boolean True/false if the SEO were successfuly save to the category
     */
    public function saveSeo($seo, $seoId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_seo_start', $arrayParameters);
        $successFlag = false;
        // Service implementation start
        $ecomSeotable = $this->serviceLocator->get('MelisEcomSeoTable');
        $enginePage = $this->getServiceLocator()->get('MelisEngineTree');
        
        $seo = $arrayParameters['seo'];
        $seoId = $arrayParameters['seoId'];
        
        try
        {
            $seo['eseo_url'] = $enginePage->cleanString(mb_strtolower($seo['eseo_url']));
            
            if (preg_match('/\s/', $seo['eseo_url']))
            {
                $seo['eseo_url'] = str_replace(" ", "", $seo['eseo_url']);
            }
            
            $seo['eseo_url_redirect'] = mb_strtolower($seo['eseo_url_redirect']);
            $seo['eseo_url_301'] = mb_strtolower($seo['eseo_url_301']);
            
            $ecomSeotable->save($seo, $seoId);
            $successFlag = true;
        }
        catch(\Exception $e)
        {
            $successFlag = false;
        }
        // Service implementation end
    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_seo_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method return SEO data By Type
     * 
     * @param String $type, is the Type of the request "category", "product", or "variant"
     * @param int $typeId id of the Type
     * 
     * @return int Array
     */
    public function getSeoByType($type, $typeId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_seo_start', $arrayParameters);
        $result = array();
        // Service implementation start
        
        if (!is_null($type)&&!is_null($typeId))
        {
            $ecomSeotable = $this->serviceLocator->get('MelisEcomSeoTable');
            $seo = $ecomSeotable->getEntryByField('eseo_'.$type.'_id', $typeId);
            
            foreach ($seo As $val)
            {
                array_push($result, $val);
            }
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_seo_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
}