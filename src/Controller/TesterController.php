<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
class TesterController extends AbstractActionController
{

    public function displayTestAction()
    {
        $request = $this->getRequest();
        $searchProductByTextFieldsRes = array();
        $searchProductByAttributeValuesAndPriceRangeRes = array();
        $searchProductFullRes = array();
        
        if($request->isPost()) {
            $prodSearchSvc = $this->getServiceLocator()->get('MelisComProductSearchService');
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
    
    
    public function testAction()
    {
        $langId = 6;
        $attrService = $this->getServiceLocator()->get('MelisComAttributeService');
        $attrData = $attrService->getAttributeText(27, 2);
        echo $attrData;
        

        die;
    }

    
}