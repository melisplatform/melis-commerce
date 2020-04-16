<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerceTest\Controller;

use MelisCore\ServiceManagerGrabber;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
class MelisCommerceControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = false;
    protected $sm;
    protected $method = 'save';

    public function setUp()
    {
        $this->sm  = new ServiceManagerGrabber();
    }

        /**
     * Get getMelisEcomAssocVariantTable table
     * @return mixed
     */
    private function getMelisEcomAssocVariantTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomAssocVariantTypeTable table
     * @return mixed
     */
    private function getMelisEcomAssocVariantTypeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomAttributeTable table
     * @return mixed
     */
    private function getMelisEcomAttributeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomAttributeTransTable table
     * @return mixed
     */
    private function getMelisEcomAttributeTransTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomAttributeTypeTable table
     * @return mixed
     */
    private function getMelisEcomAttributeTypeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomAttributeValueTable table
     * @return mixed
     */
    private function getMelisEcomAttributeValueTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomAttributeValueTransTable table
     * @return mixed
     */
    private function getMelisEcomAttributeValueTransTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomBasketAnonymousTable table
     * @return mixed
     */
    private function getMelisEcomBasketAnonymousTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomBasketPersistentTable table
     * @return mixed
     */
    private function getMelisEcomBasketPersistentTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCategoryTable table
     * @return mixed
     */
    private function getMelisEcomCategoryTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCategoryTransTable table
     * @return mixed
     */
    private function getMelisEcomCategoryTransTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCivilityTransTable table
     * @return mixed
     */
    private function getMelisEcomCivilityTransTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomClientTable table
     * @return mixed
     */
    private function getMelisEcomClientTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomClientAddressTable table
     * @return mixed
     */
    private function getMelisEcomClientAddressTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomClientAddressTypeTable table
     * @return mixed
     */
    private function getMelisEcomClientAddressTypeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomClientAddressTypeTransTable table
     * @return mixed
     */
    private function getMelisEcomClientAddressTypeTransTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomClientCompanyTable table
     * @return mixed
     */
    private function getMelisEcomClientCompanyTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomClientPersonTable table
     * @return mixed
     */
    private function getMelisEcomClientPersonTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCountryCategoryTable table
     * @return mixed
     */
    private function getMelisEcomCountryCategoryTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCountryTable table
     * @return mixed
     */
    private function getMelisEcomCountryTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCouponTable table
     * @return mixed
     */
    private function getMelisEcomCouponTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCouponClientTable table
     * @return mixed
     */
    private function getMelisEcomCouponClientTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCouponOrderTable table
     * @return mixed
     */
    private function getMelisEcomCouponOrderTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomCurrencyTable table
     * @return mixed
     */
    private function getMelisEcomCurrencyTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomDocumentTable table
     * @return mixed
     */
    private function getMelisEcomDocumentTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomDocRelationsTable table
     * @return mixed
     */
    private function getMelisEcomDocRelationsTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomDocTypeTable table
     * @return mixed
     */
    private function getMelisEcomDocTypeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomLangTable table
     * @return mixed
     */
    private function getMelisEcomLangTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderTable table
     * @return mixed
     */
    private function getMelisEcomOrderTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderAddressTable table
     * @return mixed
     */
    private function getMelisEcomOrderAddressTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderBasketTable table
     * @return mixed
     */
    private function getMelisEcomOrderBasketTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderMessageTable table
     * @return mixed
     */
    private function getMelisEcomOrderMessageTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderPaymentTable table
     * @return mixed
     */
    private function getMelisEcomOrderPaymentTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderPaymentTypeTable table
     * @return mixed
     */
    private function getMelisEcomOrderPaymentTypeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderShippingTable table
     * @return mixed
     */
    private function getMelisEcomOrderShippingTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderStatusTable table
     * @return mixed
     */
    private function getMelisEcomOrderStatusTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomOrderStatusTransTable table
     * @return mixed
     */
    private function getMelisEcomOrderStatusTransTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomPriceTable table
     * @return mixed
     */
    private function getMelisEcomPriceTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomProductTable table
     * @return mixed
     */
    private function getMelisEcomProductTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomProductAttributeTable table
     * @return mixed
     */
    private function getMelisEcomProductAttributeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomProductCategoryTable table
     * @return mixed
     */
    private function getMelisEcomProductCategoryTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomProductTextTable table
     * @return mixed
     */
    private function getMelisEcomProductTextTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomProductTextTypeTable table
     * @return mixed
     */
    private function getMelisEcomProductTextTypeTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomSeoTable table
     * @return mixed
     */
    private function getMelisEcomSeoTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomVariantTable table
     * @return mixed
     */
    private function getMelisEcomVariantTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomProductVariantAttributeValueTable table
     * @return mixed
     */
    private function getMelisEcomProductVariantAttributeValueTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisEcomVariantStockTable table
     * @return mixed
     */
    private function getMelisEcomVariantStockTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCommerce', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }


    public function getPayload($method)
    {
        return $this->sm->getPhpUnitTool()->getPayload('MelisCommerce', $method);
    }

    /**
     * START ADDING YOUR TESTS HERE
     */

    public function testComLanguage()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisEcomLangTable();
        
        foreach($payloads['create'] as $data){
            $id = $table->save($data);
            $this->assertNotEmpty($id, "Failed to insert language");
        }
        
        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
        
        foreach($payloads['delete'] as $data){
            $table->deleteByField($data['column'], $data['value']);
            $result = $table->getEntryByField($data['column'], $data['value']);
            $this->assertEmpty($result, "Failed to delete language");
        }
        
    }
    
    public function testComCurrency()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisEcomCurrencyTable();
        
        foreach($payloads['create'] as $data){
            $id = $table->save($data);
            $this->assertNotEmpty($id, "Failed to insert language");
        }
        
        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
        
        foreach($payloads['delete'] as $data){
            $table->deleteByField($data['column'], $data['value']);
            $result = $table->getEntryByField($data['column'], $data['value']);
            $this->assertEmpty($result, "Failed to delete currency");
        }
    }
    
    public function testComCountry()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisEcomCountryTable();
        
        foreach($payloads['create'] as $data){
            $id = $table->save($data);
            $this->assertNotEmpty($id, "Failed to insert country");
        }
    
        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
    
        foreach($payloads['delete'] as $data){
            $table->deleteByField($data['column'], $data['value']);
            $result = $table->getEntryByField($data['column'], $data['value']);
            $this->assertEmpty($result, "Failed to delete country");
        }
    }
    
    public function testComCategories()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisEcomCategoryTable();
        $table->disableCache();
        $transTable = $this->getMelisEcomCategoryTransTable();
        $countryCatTable = $this->getMelisEcomCountryCategoryTable();
        
        foreach($payloads['create'] as $category){
            
            $catID = $table->save($category['melis_ecom_category']);
            $this->assertNotEmpty($catID, "Failed to insert category");
            
            $catTransData = array(
                'catt_category_id' => $catID,
                'catt_lang_id' => '-1',
            );
            
            $catTransData = array_merge($category['melis_ecom_category_trans'], $catTransData);
            $catTransID = $transTable->save($catTransData);
            
            $this->assertNotEmpty($catID, "Failed to insert category translations");
            
            $countryCatData = array(
                'ccat_category_id' => $catID,
                'ccat_country_id' => '-1'
            );
            
            $catCountryID = $countryCatTable->save($countryCatData);
            $this->assertNotEmpty($catCountryID, "Failed to assign country to a category");
            
        }
    
        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
    
        foreach($payloads['delete'] as $data){
           $categoryID = $table->getEntryByField($data['column'], $data['value'])->current()->cat_id;
            
            $countryCatTable->deleteByField('ccat_category_id', $categoryID);
            $result = $countryCatTable->getEntryByField('ccat_category_id', $categoryID);
            $this->assertEmpty($result, "Failed to unassigned country from a category");
            
            unset($result);
            $transTable->deleteByField('catt_category_id', $categoryID);
            $result = $transTable->getEntryByField('catt_category_id', $categoryID);
            $this->assertEmpty($result, "Failed to delete category translations");
            
            unset($result);
            $table->deleteByID($categoryID);            
            $result = $table->getEntryById($categoryID);
            $this->assertEmpty($result, "Failed to delete category");
        }
    }
    
    public function testComProducts()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisEcomProductTable();
        $prodTextTypeTable = $this->getMelisEcomProductTextTypeTable();
        $prodTextTable = $this->getMelisEcomProductTextTable();
        $prodCatTable = $this->getMelisEcomProductCategoryTable();
        $priceTable = $this->getMelisEcomPriceTable();
        
        foreach($payloads['create'] as $product){
                
            $productID  = $table->save($product['melis_ecom_product']);
            $this->assertNotEmpty($productID, "Failed to insert product");
            
            $prodTextType = $prodTextTypeTable->save($product['melis_ecom_product_text_type']);
            $this->assertNotEmpty($prodTextType, "Failed to insert product text type");
            
            $prodText = array_merge($product['melis_ecom_product_text'],
                array(
                    'ptxt_prd_id' => $productID,
                    'ptxt_lang_id' => '-1',
                    'ptxt_type' => $prodTextType,
                )
                );
            
            $prodTextId = $prodTextTable->save($prodText);
            $this->assertNotEmpty($prodTextId, "Failed to insert product text");
            
            $prodCatId = $prodCatTable->save(array_merge($product['melis_ecom_product_category'], array('pcat_prd_id' => $productID, 'pcat_cat_id' => '-1')));
            $this->assertNotEmpty($prodCatId);
            
            $productPrice = array_merge($product['melis_ecom_price'],
                array(
                    'price_prd_id' => $productID,
                    'price_country_id' => '-1',
                    'price_currency' => '-1',
                )
                );
           
            $prodPriceId = $priceTable->save($productPrice);
            $this->assertNotEmpty($prodPriceId);
        }
        
        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
        
        foreach($payloads['delete'] as $product){
            
            $productID = $table->getEntryByField($product['column'], $product['value'])->current()->prd_id;
            
            $priceTable->deleteByField('price_prd_id', $productID);
            $result = $priceTable->getEntryByField('price_prd_id', $productID);
            $this->assertEmpty($result, "Failed to delete product prices");
            
            $prodCatTable->deleteByField('pcat_prd_id', $productID);
            $result = $prodCatTable->getEntryByField('pcat_prd_id', $productID);
            $this->assertEmpty($result, "Failed to remove category from product");
            
            $prodTxts = $prodTextTable->getEntryByField('ptxt_prd_id', $productID);
             
            $prodTextTable->deleteByField('ptxt_prd_id', $productID);
            $result = $prodTextTable->getEntryByField('ptxt_prd_id', $productID);
            $this->assertEmpty($result, "Failed to delete product text");
            
            $result = $prodTextTable->getEntryByField('ptxt_prd_id', $productID);
            $this->assertEmpty($result);
            foreach($prodTxts as $txt){
                $prodTextTypeTable->deleteById($txt->ptxt_type);
                $result = $prodTextTypeTable->getEntryById($txt->ptxt_type);
                $this->assertEmpty($result, "Failed to delete product text type");
            }
            $table->deleteById($productID);
            $result = $table->getEntryById($productID);
            $this->assertEmpty($result);
        }
    }
    
    public function testComVariants()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $varTable = $this->getMelisEcomVariantTable();
        $varStockTable = $this->getMelisEcomVariantStockTable();
        $priceTable = $this->getMelisEcomPriceTable();
        
        foreach($payloads['create'] as $variant){
        
            $variantID = $varTable->save(array_merge($variant['melis_ecom_variant'], array('var_prd_id' => '-1')));
            $this->assertNotEmpty($variantID);
        
            $stock = array_merge($variant['melis_ecom_variant_stock'],
                array(
                    'stock_var_id' => $variantID,
                    'stock_country_id' => '-1',
                ));
        
            $variantStockId = $varStockTable->save($stock);
            $this->assertNotEmpty($variantStockId);
        
            $variantPrice = array_merge($variant['melis_ecom_price'],
                array(
                    'price_var_id' => $variantID,
                    'price_country_id' => '-1',
                    'price_currency' => '-1',
                )
                );
            $varPriceId = $priceTable->save($variantPrice);
            $this->assertNotEmpty($varPriceId);
             
        }
        
        foreach($payloads['read'] as $data){
            $result = $varTable->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
        
        foreach($payloads['delete'] as $variant){
            $variantID = $varTable->getEntryByField($variant['column'], $variant['value'])->current()->var_id;
        
            $priceTable->deleteByField('price_var_id', $variantID);
            $result = $priceTable->getEntryByField('price_var_id', $variantID);
        
            $varStockTable->deleteByField('stock_var_id', $variantID);
            $result = $varStockTable->getEntryByField('stock_var_id', $variantID);
            $this->assertEmpty($result, "Faield to remove variant stocks");
        
            $varTable->deleteByID($variantID);
            $result = $varTable->getEntryById($variantID);
            $this->assertEmpty($result);
            unset($variantTable);
        }
    }
    
    public function testComAttributes()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $attributeTable = $this->getMelisEcomAttributeTable();
        $attrTransTable = $this->getMelisEcomAttributeTransTable();
        $prodAttrTable = $this->getMelisEcomProductAttributeTable();
        $attrValTable = $this->getMelisEcomAttributeValueTable();
        $varAttrValTable = $this->getMelisEcomProductVariantAttributeValueTable();
        $attrValTransTable = $this->getMelisEcomAttributeValueTransTable();
        
        foreach($payloads['create'] as $attribute){
            
            $attributeID = $attributeTable->save($attribute['melis_ecom_attribute']);
            $this->assertNotEmpty($attributeID, "Failed to insert attribute");
            $attributeTrans = array_merge($attribute['melis_ecom_attribute_trans'],
                array(
                    'atrans_attribute_id' => $attributeID,
                    'atrans_lang_id' => '-1',
                )
                );
        
            $attributeTransID = $attrTransTable->save($attributeTrans);
            $this->assertNotEmpty($attributeTransID, " Failed to insert attribute translations");
        
            $prodAttributeID = $prodAttrTable->save(array('patt_product_id' => '-1', 'patt_attribute_id' => $attributeID));
            $this->assertNotEmpty($prodAttributeID, "Failed to assign attribute to a product");
            $attributeVal = array_merge($attribute['melis_ecom_attribute_value'],
                array(
                    'atval_attribute_id' => $attributeID,
                )
                );
        
            $attributeValID = $attrValTable->save($attributeVal);
            $this->assertNotEmpty($attributeValID, "Failed to insert attribute value");
        
            $attrValTransId = $varAttrValTable->save(array('vatv_variant_id' => '-1', 'vatv_attribute_value_id' => $attributeValID));
            $this->assertNotEmpty($attrValTransId);
        
            $attributeValTrans = array_merge($attribute['melis_ecom_attribute_value_trans'],
                array(
                    'av_attribute_value_id' => $attributeValID,
                    'avt_lang_id' => '-1',
                )
                );
        
            $attrValTransId = $attrValTransTable->save($attributeValTrans);
            $this->assertNotEmpty($attrValTransId);
        
        }
        
        foreach($payloads['read'] as $data){
            $result = $attributeTable->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
        
        foreach($payloads['delete'] as $attribute){
            $attributeID  = $attributeTable->getEntryByField($attribute['column'], $attribute['value'])->current()->attr_id;
        
            $attrValID  = $attrValTable->getEntryByField('atval_attribute_id', $attributeID)->current()->atval_id;
        
            $attrValTransTable->deleteByField('av_attribute_value_id', $attrValID);
            $result = $attrValTransTable->getEntryByField('av_attribute_value_id', $attrValID);
            $this->assertEmpty($result, "Failed to delete attribute value translations");
        
            $varAttrValTable->deleteByField('vatv_attribute_value_id', $attrValID);
            $result = $varAttrValTable->getEntryByField('vatv_attribute_value_id', $attrValID);
            $this->assertEmpty($result, "Failed to remove attribute from a variant");
        
            $attrValTable->deleteByID($attrValID);
            $result = $attrValTable->getEntryById($attrValID);
            $this->assertEmpty($result, "Faield to remove attribute value");
        
            $prodAttrTable->deleteByField('patt_attribute_id', $attributeID);
            $result = $prodAttrTable->getEntryByField('patt_attribute_id', $attributeID);
            $this->assertEmpty($result, "Failed to remove attribute from a product");
        
            $attrTransTable->deleteByField('atrans_attribute_id', $attributeID);
            $result = $attrTransTable->getEntryByField('atrans_attribute_id', $attributeID);
            $this->assertEmpty($result, "Failed to delete attribute translations");
        
            $attributeTable->deleteById($attributeID);
            $result = $attributeTable->getEntryById($attributeID);
            $this->assertEmpty($result, "Failed to delete attribute");
        }
        
    }
    
    public function testComClients()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $clientTable = $this->getMelisEcomClientTable();
        $clientPerTable = $this->getMelisEcomClientPersonTable();
        $clientCompTable = $this->getMelisEcomClientCompanyTable();
        $clientAddrTable = $this->getMelisEcomClientAddressTable();
        
        foreach($payloads['create'] as $client){
            $clientID = $clientTable->save(array_merge($client['melis_ecom_client'], array('cli_country_id' => '-1')));
            $this->assertNotEmpty($clientID);
            $clientPerson = array_merge($client['melis_ecom_client_person'],
                array(
                    'cper_client_id' => $clientID,
                    'cper_lang_id' => '-1',
                )
                );
        
            $clientPersonID = $clientPerTable->save($clientPerson);
            $this->assertNotEmpty($clientPersonID);
            $clientCompanyID = $clientCompTable->save(array_merge($client['melis_ecom_client_company'], array('ccomp_client_id' => $clientID)));
            $this->assertNotEmpty($clientCompanyID);
            $clientAddress = array_merge($client['melis_ecom_client_address'],
                array(
                    'cadd_client_id' => $clientID,
                    'cadd_client_person' => $clientPersonID,
                ));
        
            $clientAddressId = $clientAddrTable->save($clientAddress);
            $this->assertNotEmpty($clientAddressId);
        
            $this->assertNotEmpty($clientID, "Failed to insert test client");
        }
        
        foreach($payloads['read'] as $data){
            $result = $clientPerTable->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
        
        foreach($payloads['delete'] as $client){
            $result = array();
            $clientID = $clientPerTable->getEntryByField($client['column'], $client['value'])->current()->cper_client_id;
            $clientAddrTable->deleteByField('cadd_client_id', $clientID);
            $result = $clientAddrTable->getEntryByField('cadd_client_id', $clientID);
            $this->assertEmpty($result, "Failed to delete client addresses");
        
            $clientCompTable->deleteByField('ccomp_client_id', $clientID);
            $result = $clientCompTable->getEntryByField('ccomp_client_id', $clientID);
            $this->assertEmpty($result, "Failed to remove company");
        
            $clientPerTable->deleteByField('cper_client_id', $clientID);
            $clientTable->deleteByField('cli_id', $clientID);
            $result = $clientTable->getEntryById($clientID)->toArray();
            $this->assertEmpty($result, "Failed to delete client");
        }
    }
    
    public function testComOrders()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $prodTextTable = $this->getMelisEcomProductTextTable();
        $catTransTable = $this->getMelisEcomCategoryTransTable();
        $varTable = $this->getMelisEcomVariantTable();
        $orderTable = $this->getMelisEcomOrderTable();
        $priceTable = $this->getMelisEcomPriceTable();
        $orderBasketTable = $this->getMelisEcomOrderBasketTable();
        $orderAddrTable = $this->getMelisEcomOrderAddressTable();
        $orderPayTable = $this->getMelisEcomOrderPaymentTable();
        $orderShippTable = $this->getMelisEcomOrderShippingTable();
        
        foreach($payloads['create'] as $order){
        
            $orderData = array_merge($order['melis_ecom_order'],
                array(
                    'ord_client_id' => '-1',
                    'ord_client_person_id' => '-1',
                    'ord_country_id' => '-1',
        
                )
                );
            //save order
            $orderID = $orderTable->save($orderData);
            $this->assertNotEmpty($orderID, "Failed to insert order");
        
            $variant = $varTable->getEntryByField($order['order_variant']['column'], $order['order_variant']['value'])->current();
            $varId = !empty($variant)? $variant->var_prd_id: '-1';
            $varSku = !empty($variant)? $variant->var_sku: 'PHPUNITTEST123';
            
            $productText = $prodTextTable->getEntryByField($order['order_product_text']['column'], $order['order_product_text']['value'])->current();
            $productTextString = !empty($productText)? $productText->ptxt_field_short : 'Product Text Test PHP';
            
            $category = $catTransTable->getEntryByField($order['order_category']['column'], $order['order_category']['value'])->current();
            $catId = !empty($category)? $category->catt_category_id: '-1';
            $categoryName = !empty($category)? $category->catt_name: 'PHP Category Test Name';
            
            $price = $priceTable->getEntryByField('price_var_id', $varId)->current(); 
            $priceNet = !empty($price)? $price->price_net: 100;
            $priceGross = !empty($price)? $price->price_gross: 80;
            $priceVat = !empty($price)? $price->price_vat_price: 11;
        
            $basketData = array_merge($order['melis_ecom_order_basket'],
                array(
                    'obas_order_id' => $orderID,
                    'obas_variant_id' => $varId,
                    'obas_product_name' => $productTextString,
                    'obas_sku' => $varSku,
                    'obas_category_id' => $catId,
                    'obas_category_name' => $categoryName,
                    'obas_currency' => '-1',
                    'obas_price_net' => $priceNet,
                    'obas_price_gross' => $priceGross,
                    'obas_price_vat' =>  $priceVat
                )
                );
        
            $orderBasketID = $orderBasketTable->save($basketData);
            $this->assertNotEmpty($orderBasketID, "Failed to insert order basket");
        
            $billAddress = array_merge($order['melis_ecom_order_address'],
                array(
                    'oadd_order_id' => $orderID,
                    'oadd_type' => 1,
                )
                );
            //save billing address
            $billAddressID = $orderAddrTable->save($billAddress);
            $this->assertNotEmpty($billAddressID, "Failed to insert order billing address");
        
            $shipAddress = array_merge($order['melis_ecom_order_address'],
                array(
                    'oadd_order_id' => $orderID,
                    'oadd_type' => 2,
                )
                );
            // save shipping/delivery address
            $shipAddressID = $orderAddrTable->save($shipAddress);
            $this->assertNotEmpty($shipAddressID, "Failed to insert shipping address");
        
            $paymentData = array_merge($order['melis_ecom_order_payment'],
                array(
                    'opay_order_id' => $orderID,
                    'opay_price_total' => $priceNet + 100,
                    'opay_price_order' => $priceNet,
                    'opay_price_shipping' => 100,
                    'opay_currency_id' => '-1',
                )
                );
        
            // save payment details
            $paymentID = $orderPayTable->save($paymentData);
            $this->assertNotEmpty($paymentID, "Failed to insert order payment");
        
            $shippingData = array_merge($order['melis_ecom_order_shipping'],
                array(
                    'oship_order_id' => $orderID,
                )
                );
            //save shipping details
            $shippingID = $orderShippTable->save($shippingData);
            $this->assertNotEmpty($shippingID, "Failed to insert order shipping details");
        
            $orderUpdate = array(
                'ord_billing_address' => $billAddressID,
                'ord_delivery_address' => $shipAddressID,
            );
        
            $orderTable->save($orderUpdate, $orderID);
        }
        
        foreach($payloads['read'] as $data){
            $result = $orderTable->getEntryByField($data['column'], $data['value'])->current();
            $this->assertNotEmpty($result);
        }
        
        foreach($payloads['delete'] as $order){
            $orderID = $orderTable->getEntryByField($order['column'], $order['value'])->current()->ord_id;
            $orderPayTable->deleteByField('opay_order_id', $orderID);
            $result = $orderPayTable->getEntryByField('opay_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order payment");
        
            $orderAddrTable->deleteByField('oadd_order_id', $orderID);
            $result = $orderAddrTable->getEntryByField('oadd_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order addresses");
        
            $orderShippTable->deleteByField('oship_order_id', $orderID);
            $result = $orderShippTable->getEntryByField('oship_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order shipping details");
        
            $orderBasketTable->deleteByField('obas_order_id', $orderID);
            $result = $orderBasketTable->getEntryByField('obas_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order basket");
        
            $orderTable->deleteById($orderID);
            $result = $orderTable->getEntryById($orderID);
            $this->assertEmpty($result, "Failed to delete order");
        }
        
    }

}

