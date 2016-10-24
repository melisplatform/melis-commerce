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
class TesterController extends AbstractActionController
{

    
    public function runAction()
    {
        echo 'Tester Controller' . PHP_EOL;
        
        $docService = $this->getServiceLocator()->get('MelisComDocumentService');
        print_r(
            $docService->getDocumentsByRelation('product', 1)
        );

        return new JsonModel(array());
    }
    
    public function saveDocTypeAction()
    {
        echo 'Tester Controller' . PHP_EOL;
        
        $docService = $this->getServiceLocator()->get('MelisComDocumentService');
        print_r(
            $docService->saveDocumentType('IMAGE', 'imageShirt')
            );
        print_r(
            $docService->saveDocumentType('IMAGES', 'imageShirt')
            );
        
        return new JsonModel(array());
    }
    
    function typesAction()
    {
        echo 'Tester Controller' . PHP_EOL;
        
        $docService = $this->getServiceLocator()->get('MelisComDocumentService');
        print_r(
            $docService->getDocumentTypes()
            );
        
        return new JsonModel(array());
    }
    
    public function attributesAction()
    {
        echo 'Tester Controller' . PHP_EOL;
        
        $prodService = $this->getServiceLocator()->get('MelisComProductService');
        print_r(
            $prodService->getProductList()
            );
        
        return new JsonModel(array());
    }
    
    public function currencyAction()
    {
        $coreConfig = $this->getServiceLocator()->get('MelisCoreConfig');
        $data = $coreConfig->getItem('meliscommerce/interface/meliscommerce_coupon_list/interface/meliscommerce_coupon_list_leftmenu');
        print '<pre>';
        print_r($data);
        print '</pre>';
        die;
    }
    
}