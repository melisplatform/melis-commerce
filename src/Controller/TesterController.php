<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Metadata\Metadata;
use MelisCore\Controller\MelisAbstractActionController;

class TesterController extends MelisAbstractActionController
{
    public function displayTestAction()
    {
        $request = $this->getRequest();
        $searchProductByTextFieldsRes = array();
        $searchProductByAttributeValuesAndPriceRangeRes = array();
        $searchProductFullRes = array();
        
        if($request->isPost()) {
            $prodSearchSvc = $this->getServiceManager()->get('MelisComProductSearchService');
            $postData = $request->getPost()->getArrayCopy();

            $formType = $postData['actionType'];
            $searchProductByTextFieldsRes = array();
            $searchProductByAttributeValuesAndPriceRangeRes = array();
            $searchProductFullRes = array();
            

            
            $catIds = $postData['categoryId'] ? str_replace(' ', '', trim($postData['categoryId'])) : null;
            if($catIds) {
                $catIds = explode(',', $catIds);
            }
            
            switch($formType) {
                case 'searchProductByTextFields':
                    $types = str_replace(' ', '', trim($postData['fieldTypeCodes']));
                    $types = explode(',', $types);
                    $searchProductByTextFieldsRes = $prodSearchSvc->searchProductByTextFields($postData['productName'], $types, $postData['langId'], $catIds, null, $postData['productStatus']);
                break;
                case 'searchProductByAttributeValuesAndPriceRange':
                    $attr = str_replace(' ', '', trim($postData['attributeIds']));
                    $attr = explode(',', $attr);
                    $searchProductByAttributeValuesAndPriceRangeRes = $prodSearchSvc->searchProductByAttributeValuesAndPriceRange($attr, $postData['minPrice'], $postData['maxPrice'],
                        $postData['langId'],
                        $catIds, null, $postData['productStatus']);
                break;
                case 'searchProductFull':
                    $types = str_replace(' ', '', trim($postData['fieldTypeCodes']));
                    $types = explode(',', $types);
                    
                    $attr = str_replace(' ', '', trim($postData['attributeIds']));
                    $attr = explode(',', $attr);
                    $searchProductFullRes = $prodSearchSvc->searchProductFull($postData['productName'], $types, $attr, $postData['minPrice'], $postData['maxPrice'],
                        $postData['langId'], $catIds, null, $postData['productStatus']
                    );
                break;
               
            }
            
        }
        
        
        
        $view = new ViewModel();
        $view->searchProductByTextFieldsRes = $searchProductByTextFieldsRes;
        $view->searchProductByAttributeValuesAndPriceRangeRes = $searchProductByAttributeValuesAndPriceRangeRes;
        $view->searchProductFullRes = $searchProductFullRes;
        
        return $view;
    }
    
    public function testDocumentAction()
    {
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        $tmp = $prodSvc->getProductById(1, null, null);
        echo '<pre>'; print_r($tmp); echo '</pre>'; die();
//         $varSvc = $this->$this->getServiceManager()->get('MelisComVariantService');
//         $tmp = $varSvc->getVariantById(1, 1, null, 'IMG', array('LARGE'));
//         echo '<pre>'; print_r($tmp); echo '</pre>'; die();
       
    }
    
    public function testSeoCategoryAction()
    {
        
        $categorySvc = $this->getServiceManager()->get('MelisComCategoryService');
        
        $data = $categorySvc->getCategorySeoById(3,2);
        echo '<pre>'; print_r($data); echo '</pre>'; die();
    }
    
    public function testAction()
    {
        $stockAlertTable = $this->getServiceManager()->get('MelisEcomStockEmailAlertTable');
        $data = $stockAlertTable->fetchAll();
        
        var_dump($data->toArray());
       
        die();

    }
    
    public function testingAction()
    {
//         $melisCoreConfig = $this->$this->getServiceManager()->get('MelisCoreConfig');
//         $confOrder = $melisCoreConfig->getItem('meliscommerce/emails');
//         $confOrder = $melisCoreConfig->getItem('meliscommerce/');
        
//         echo '<pre>'; print_r($confOrder); echo '</pre>';
//         die();
           $stockEmailSvc = $this->getServiceManager()->get('MelisComStockEmailAlertService');
           $stockEmailSvc->checkStockLevelByOrderId((int) $this->params()->fromQuery('orderId', ''));
           echo 'sending....';
           die();
    }
    
    public function checkMailConfigAction()
    {
                $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
                $confOrder = $melisCoreConfig->getItem('meliscommerce/emails');
//                 $confOrder = $melisCoreConfig->getItem('meliscommerce/');
    
                echo '<pre>'; print_r($confOrder); echo '</pre>';
                die();
    }
    
    /**
     * Returns the translation text
     * @param String $key
     * @param array $args
     * @return string
     */
    private function getTranslation($key, $args = [])
    {
        $translator = $this->getServiceManager()->get('translator');
        $text = vsprintf($translator->translate($key), $args);
    
        return $text;
    }
    
    public function testMailAction()
    {
        $sendMailSvc = $this->getServiceManager()->get('MelisEngineSendMail');
    
       
            $email_template_path = 'MelisDemoCommerce/emailLayout';
            $email_from_name = 'Gwapo gwapo';
            $email_from =  'gwapo@gmail.com';
            $email_to_name = 'Alvin Lanceta';
            $email_reply_to = 'noReply@gmail.com';
            $email_to = 'alanceta@melistechnology.com';
            $email_content = 'Testing ni email ni';
            $email_subject =  'Testing';
            $email_content_tag_replace = array();
    
        $sendMailSvc->sendEmail($email_template_path, $email_from, $email_from_name, 
	                           $email_to, $email_to_name, $email_subject, 
	                           $email_content, $email_content_tag_replace, $email_reply_to);
        echo 'ni send kahaa?';
        die();
    }
    
    public function cacheTestAction()
    {
        $cacheKey = 'getPageLinkCategory_' . 12 . '_' . 1 . '_' . false;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        echo 'cache<pre>'; var_dump($results); echo '</pre> cache_end';
        
        $comLinkSrv = $this->getServiceManager()->get('MelisComLinksService');
        $test = $comLinkSrv->getPageLink('category', 12, 1, false);
        echo '<pre>'; print_r($test); die();
    }

    
}