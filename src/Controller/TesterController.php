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
    
    public function testDocumentAction()
    {
        $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
        $tmp = $prodSvc->getProductById(1, null, null);
        echo '<pre>'; print_r($tmp); echo '</pre>'; die();
//         $varSvc = $this->getServiceLocator()->get('MelisComVariantService');
//         $tmp = $varSvc->getVariantById(1, 1, null, 'IMG', array('LARGE'));
//         echo '<pre>'; print_r($tmp); echo '</pre>'; die();
       
    }
    
    public function testAction()
    {
        $test = array(
            'MelisEcomCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTable',
            'MelisEcomCategoryTransTable' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTransTable',
            'MelisEcomCountryCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable',
            'MelisEcomLangTable' => 'MelisCommerce\Model\Tables\MelisEcomLangTable',
            'MelisEcomDocumentTable' => 'MelisCommerce\Model\Tables\MelisEcomDocumentTable',
            'MelisEcomDocTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomDocTypeTable',
            'MelisEcomProductTable' => 'MelisCommerce\Model\Tables\MelisEcomProductTable',
            'MelisEcomProductTextTable' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTable',
            'MelisEcomProductTextTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable',
            'MelisEcomVariantTable' => 'MelisCommerce\Model\Tables\MelisEcomVariantTable',
            'MelisEcomCountryTable' => 'MelisCommerce\Model\Tables\MelisEcomCountryTable',
            'MelisEcomPriceTable' => 'MelisCommerce\Model\Tables\MelisEcomPriceTable',
            'MelisEcomVariantStockTable' => 'MelisCommerce\Model\Tables\MelisEcomVariantStockTable',
            'MelisEcomProductCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable',
            'MelisEcomProductAttributeTable' => 'MelisCommerce\Model\Tables\MelisEcomProductAttributeTable',
            'MelisEcomProductCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable',
            'MelisEcomProductVariantAttributeValueTable' => 'MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable',
            'MelisEcomLang' => 'MelisCommerce\Model\Tables\MelisEcomLangTable',
            'MelisEcomDocRelationsTable' => 'MelisCommerce\Model\Tables\MelisEcomDocRelationsTable',
            'MelisEcomAttributeTrans' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable',
            'MelisEcomSeoTable' => 'MelisCommerce\Model\Tables\MelisEcomSeoTable',
            'MelisEcomAttributeTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable',
            'MelisEcomAttributeTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTable',
            'MelisEcomAttributeTransTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable',
            'MelisEcomAttributeValueTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTable',
            'MelisEcomAttributeValueTransTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable',
            'MelisEcomClientTable' => 'MelisCommerce\Model\Tables\MelisEcomClientTable',
            'MelisEcomClientPersonTable' => 'MelisCommerce\Model\Tables\MelisEcomClientPersonTable',
            'MelisEcomClientAddressTable' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTable',
            'MelisEcomClientCompanyTable' => 'MelisCommerce\Model\Tables\MelisEcomClientCompanyTable',
            'MelisEcomCivilityTransTable' => 'MelisCommerce\Model\Tables\MelisEcomCivilityTransTable',
            'MelisEcomClientAddressTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable',
            'MelisEcomClientAddressTypeTransTable' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable',
            'MelisEcomBasketPersistentTable' => 'MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable',
            'MelisEcomBasketAnonymousTable' => 'MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable',
            'MelisEcomOrderTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderTable',
            'MelisEcomOrderAddressTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderAddressTable',
            'MelisEcomCouponTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponTable',
            'MelisEcomCouponOrderTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponOrderTable',
            'MelisEcomCouponClientTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponClientTable',
            'MelisEcomOrderPaymentTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable',
            'MelisEcomOrderPaymentTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable',
            'MelisEcomOrderStatusTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTable',
            'MelisEcomOrderStatusTransTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable',
            'MelisEcomOrderMessageTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderMessageTable',
            'MelisEcomOrderShippingTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderShippingTable',
            'MelisEcomOrderBasketTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderBasketTable',
            'MelisEcomCurrencyTable' => 'MelisCommerce\Model\Tables\MelisEcomCurrencyTable',
            'MelisEcomAssocVariantTable'      => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTable',
            'MelisEcomAssocVariantTypeTable'  => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTypeTable',
        );
        
        ksort($test);
        foreach($test as $key => $value){
//             echo "<br> 'get$key' => array(\n
//                   <br> &nbsp&nbsp&nbsp 'model' => 'MelisCommerce\Model\\".substr($key, 0, -5)."',
//                   <br> &nbsp&nbsp&nbsp 'model_table' => '$value',
//                   <br> &nbsp&nbsp&nbsp 'db_table_name' => '',
//                   <br> ),";
//                 echo "\$this->assertNotEmpty(\$this->get$key()->fetchAll()->toArray());<br>";
                echo "\$this->get$key(),<br>";
        }
        die();

    }
    
    

    
}