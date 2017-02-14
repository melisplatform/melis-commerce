<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerceTest\Controller;

use MelisCore\ServiceManagerGrabber;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
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

    
    /*
     * Test for inserting commerce data
     */
    public function testInsertData()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        
        //language
        $langID = $this->insertLanguage($payloads['languages']);  
        
        //currency
        $currenciesID = $this->insertCurrency($payloads['currencies']);
        
        //country
        $countriesID = $this->insertCountry($payloads['countries'], $currenciesID[0]);
        
        //categories
        $categoriesID = $this->insertCategory($payloads['categories'], $langID[0], $countriesID[0]);
        
        //products
        $productsID = $this->insertProduct($payloads['products'], $payloads['prices'], $categoriesID[0], $langID[0], $countriesID[0], $currenciesID[0]);
        
        //variants
        $variantsID = $this->insertVariant($payloads['variants'], $payloads['prices'], $productsID[0], $countriesID[0], $currenciesID[0]);
        
        //attributes
        $attributesID = $this->insertAttribute($payloads['attributes'], $productsID[0], $variantsID[0], $langID[0]);
        
        //clients
        $clientsID = $this->insertClient($payloads['clients'], $categoriesID[0], $langID[0]);
        
        //orders
        $orders = $this->insertOrder($payloads['orders'], $clientsID[0], $variantsID[0], $currenciesID[0], $countriesID[0]);

    }
    
    /*
     * Test for accessing inserted tests data
     */
    public function testTableAccessWithPayloadFromConfig()
    {
        $payloads = $this->getPayload(__METHOD__);
        $tables   = $this->getMelisCommerceTables();
        
        foreach($tables as $value => $table) {
            if(!empty($payloads[$value])){
                foreach($payloads[$value] as $entry){
                    $result = $table->getEntryByField($entry['column'], $entry['value'])->current();
                    if($result) {
                        $this->assertNotEmpty($result);
                    }
                }
            }
        }
        
    }
    
    /**
     * Test for removing commerce data, first inserted last deleted
     */
    public function testRemoveData()
    {   
        $payloads = $this->getPayload(__METHOD__);
        
        $this->removeOrder($payloads['melis_ecom_order']);
        $this->removeClient($payloads['melis_ecom_client_person']);
        $this->removeAttribute($payloads['melis_ecom_attribute']);
        $this->removeVariant($payloads['melis_ecom_variant']);
        $this->removeProduct($payloads['melis_ecom_product']);
        $this->removeCategory($payloads['melis_ecom_category']);
        $this->removeCountry($payloads['melis_ecom_country']);
        $this->removeCurrency($payloads['melis_ecom_currency']);
        $this->removeLanguage($payloads['melis_ecom_lang']);    

    }
    
    /**
     * Inserts test currency
     * @param [] $payloads
     * @return currency ID
     */
    private function insertCurrency($currencies)
    {    
        $currenciesID = array();
        foreach($currencies as $currency){
            $currencyID = $this->getMelisEcomCurrencyTable()->save($currency['melis_ecom_currency']);
            $this->assertNotEmpty($currencyID, "Failed to insert currency");
            $currenciesID[] = $currencyID;
        }            
        return $currenciesID;
    }
    
    /*
     * Removes test currency data
     * @param [] $payloads
     */
    private function removeCurrency($currencies)
    {
        foreach($currencies as $currency){
            $currenciesTable = $this->getMelisEcomCurrencyTable();
            $currenciesTable->deleteByField($currency['column'], $currency['value']);
            $result = $currenciesTable->getEntryByField($currency['column'], $currency['value']);
            $this->assertEmpty($result, "Failed to delete currency");
        }        
    }
    
    /**
     * Inserts test country
     * @param [] $payloads
     * @return country ID
     */
    private function insertCountry($countries, $currencyID)
    {
        $countriesID = array();
        foreach($countries as $country){
            $countryID = $this->getMelisEcomCountryTable()->save(array_merge($country['melis_ecom_country'], array('ctry_currency_id' => $currencyID)));
            $this->assertNotEmpty($countryID, "Failed to insert country");
            $countriesID[] = $countryID;
        }
        return $countriesID;
    }
    
    /*
     * Removes test country data
     * @param [] $payloads
     */
    private function removeCountry($countries)
    {
        foreach($countries as $country){
            $countryTable = $this->getMelisEcomCountryTable();
            $countryTable->deleteByField($country['column'], $country['value']);
            $result = $countryTable->getEntryByField($country['column'], $country['value']);
            $this->assertEmpty($result, "Failed to delete country");
        }        
    }
    
    /**
     * Inserts test langauge
     * @param [] $payloads
     * @return language ID
     */
    private function insertLanguage($languages)
    {
        $languagesID = array();
        foreach($languages as $language){ 
           $languageID = $this->getMelisEcomLangTable()->save($language['melis_ecom_lang']);
           $this->assertNotEmpty($languageID, "Failed to insert language");
           $languagesID[] = $languageID;         
           
        }
        return $languagesID;
    }
    
    /**
     * Removes test language data
     * @param [] $payloads
     */
    private function removeLanguage($languages)
    {
        foreach($languages as $language){     
            $languageTable = $this->getMelisEcomLangTable();
            $languageTable->deleteByField($language['column'], $language['value']);
            $result = $languageTable->getEntryByField($language['column'], $language['value']);
            $this->assertEmpty($result, "Failed to delete language");
        }        
    }
    
    /**
     * Inserts test category datas
     * @param [] $payloads
     * @return category ID
     */
    private function insertCategory($categories, $langID , $countryID)
    {
        $categoriesID = array();
        
        foreach($categories as $category){
            // catalogs and categories
            $catID = $this->getMelisEcomCategoryTable()->save($category['melis_ecom_category']);
            $this->assertNotEmpty($catID, "Failed to insert category");
            $catTransData = array(
                'catt_category_id' => $catID,
                'catt_lang_id' => $langID,
            );
            
            $catTransData = array_merge($category['melis_ecom_category_trans'], $catTransData);
            $catTransID = $this->getMelisEcomCategoryTransTable()->save($catTransData);
            $this->assertNotEmpty($catID, "Failed to insert category translations");
            
            // uncommenct the code below to link country and category
            $countryCatData = array(
                'ccat_category_id' => $catID,
                'ccat_country_id' => $countryID
            );
            $catCountryID = $this->getMelisEcomCountryCategoryTable()->save($countryCatData);
            $this->assertNotEmpty($catCountryID, "Failed to assign country to a category");
            $categoriesID[] = $catID;
                     
        }
        
        return $categoriesID;
    }
    
    /**
     * Removes test category data
     * @param [] $payloads
     */
    private function removeCategory($categories)
    {        
        foreach($categories as $category){
            $categoryID = $this->getMelisEcomCategoryTable()->getEntryByField($category['column'], $category['value'])->current()->cat_id;
            
            $countryCatTable = $this->getMelisEcomCountryCategoryTable();
            $countryCatTable->deleteByField('ccat_category_id', $categoryID);
            $result = $countryCatTable->getEntryByField('ccat_category_id', $categoryID);
            $this->assertEmpty($result, "Failed to unassigned country from a category");
            
            unset($result);
            $catTransTable = $this->getMelisEcomCategoryTransTable();
            $catTransTable->deleteByField('catt_category_id', $categoryID);
            $result = $catTransTable->getEntryByField('catt_category_id', $categoryID);
            $this->assertEmpty($result, "Failed to delete category translations");
            
            unset($result);
            $this->getMelisEcomCategoryTable()->deleteByID($categoryID);            
            $result = $this->getMelisEcomCategoryTable()->getEntryById($categoryID);
            $this->assertEmpty($result, "Failed to delete category");
            
        }
    }
    
    /**
     * Inserts test product datas
     * @param [] $payloads
     * @param int $categoryID
     * @param int $langID
     * @param int $countryID
     * @param int $currencyID
     */
    private function insertProduct($products, $prices, $categoryID, $langID, $countryID, $currencyID)
    {
        $productsID = array();
        foreach($products as $product){
            
            $productID  = $this->getMelisEcomProductTable()->save($product['melis_ecom_product']);
            $this->assertNotEmpty($productID, "Failed to insert product");
            
            $prodTextType = $this->getMelisEcomProductTextTypeTable()->save($product['melis_ecom_product_text_type']);
            $this->assertNotEmpty($prodTextType, "Failed to insert product text type");
            
            $prodText = array_merge($product['melis_ecom_product_text'],
                array(
                    'ptxt_prd_id' => $productID,
                    'ptxt_lang_id' => $langID,
                    'ptxt_type' => $prodTextType,
                )
                );
            
            $prodTextId = $this->getMelisEcomProductTextTable()->save($prodText);
            $this->assertNotEmpty($prodTextId, "Failed to insert product text");
            
            $prodCatId = $this->getMelisEcomProductCategoryTable()->save(array_merge($product['melis_ecom_product_category'], array('pcat_prd_id' => $productID, 'pcat_cat_id' => $categoryID)));
            $this->assertNotEmpty($prodCatId);
            
            foreach($prices as $price){
                $productPrice = array_merge($price['melis_ecom_price'],
                    array(
                        'price_prd_id' => $productID,
                        'price_country_id' => $countryID,
                        'price_currency' => $currencyID,
                    )
                    );
                $prodPriceId = $this->getMelisEcomPriceTable()->save($productPrice);
                $this->assertNotEmpty($prodPriceId);
            }
            
            $productsID[] = $productID;
                       
        }
        
        return $productsID;
    }
    
    /**
     * Removes test product datas
     * @param [] $payloads
     */
    private function removeProduct($products)
    {
        foreach($products as $product){
            
            $productTable = $this->getMelisEcomProductTable();
            $productID = $productTable->getEntryByField($product['column'], $product['value'])->current()->prd_id;
            
            $this->getMelisEcomPriceTable()->deleteByField('price_prd_id', $productID);
            $result = $this->getMelisEcomPriceTable()->getEntryByField('price_prd_id', $productID);
            $this->assertEmpty($result, "Failed to delete product prices");            
            
            $this->getMelisEcomProductCategoryTable()->deleteByField('pcat_prd_id', $productID);
            $result = $this->getMelisEcomProductCategoryTable()->getEntryByField('pcat_prd_id', $productID);
            $this->assertEmpty($result, "Failed to remove category from product");
            
            $prodTextTable = $this->getMelisEcomProductTextTable();
            $prodTxts = $prodTextTable->getEntryByField('ptxt_prd_id', $productID);          
             
            $prodTextTable->deleteByField('ptxt_prd_id', $productID);
            $result = $prodTextTable->getEntryByField('ptxt_prd_id', $productID);
            $this->assertEmpty($result, "Failed to delete product text");
            
            $result = $prodTextTable->getEntryByField('ptxt_prd_id', $productID);
            $this->assertEmpty($result);
            foreach($prodTxts as $txt){
                $prodTextTypeTable =  $this->getMelisEcomProductTextTypeTable();
                $prodTextTypeTable->deleteById($txt->ptxt_type);
                $result = $prodTextTypeTable->getEntryById($txt->ptxt_type);
                $this->assertEmpty($result, "Failed to delete product text type");
            }
            $productTable->deleteById($productID);
            $result = $productTable->getEntryById($productID);
            $this->assertEmpty($result);
                      
        }
                
    }
    
    /**
     * Inserts test variant data
     * @param [] $payloads
     * @param int $productID
     * @param int $countryID
     * @param int $currencyID
     */
    private function insertVariant($variants, $prices, $productID, $countryID, $currencyID)
    {
        $variantsID = array();
        foreach($variants as $variant){
            
            $variantID = $this->getMelisEcomVariantTable()->save(array_merge($variant['melis_ecom_variant'], array('var_prd_id' => $productID)));
            $this->assertNotEmpty($variantID);
            
            $stock = array_merge($variant['melis_ecom_variant_stock'],
                array(
                    'stock_var_id' => $variantID,
                    'stock_country_id' => $countryID,
                ));
            
            $variantStockId = $this->getMelisEcomVariantStockTable()->save($stock);
            $this->assertNotEmpty($variantStockId);
            
            foreach($prices as $price){
               $variantPrice = array_merge($price['melis_ecom_price'],
                   array(
                       'price_var_id' => $variantID,
                       'price_country_id' => $countryID,
                       'price_currency' => $currencyID,
                   )
               );               
               $varPriceId = $this->getMelisEcomPriceTable()->save($variantPrice);
               $this->assertNotEmpty($varPriceId);
           }
           
           $variantsID[] = $variantID;
        }
        return $variantsID;
    }
    
    /**
     * Removes test variant data
     * @param [] $payloads
     */
    private function removeVariant($variants)
    {   
        foreach($variants as $variant){
            $variantTable = $this->getMelisEcomVariantTable();
            $variantID = $variantTable->getEntryByField($variant['column'], $variant['value'])->current()->var_id;
                        
            $this->getMelisEcomPriceTable()->deleteByField('price_var_id', $variantID);
            $result = $this->getMelisEcomPriceTable()->getEntryByField('price_var_id', $variantID);
            
            $variantStockTable = $this->getMelisEcomVariantStockTable();
            $variantStockTable->deleteByField('stock_var_id', $variantID);
            $result = $variantStockTable->getEntryByField('stock_var_id', $variantID);
            $this->assertEmpty($result, "Faield to remove variant stocks");
            
            $variantTable->deleteByID($variantID);
            $result = $variantTable->getEntryById($variantID);
            $this->assertEmpty($result);
            unset($variantTable);                       
        }
    }
    
    /**
     * Inserts test attributes data
     * @param int $payloads
     * @param int $productID
     * @param int $variantID
     * @param int $langID
     */
    private function insertAttribute($attributes, $productID, $variantID, $langID)
    {
        $attributesID = array();
        foreach($attributes as $attribute){
            $attributeTable = $this->getMelisEcomAttributeTable();
            $attributeID = $attributeTable->save($attribute['melis_ecom_attribute']);
            $this->assertNotEmpty($attributeID, "Failed to insert attribute");
            $attributeTrans = array_merge($attribute['melis_ecom_attribute_trans'],
                array(
                    'atrans_attribute_id' => $attributeID,
                    'atrans_lang_id' => $langID,
                )
                );
            
            $attributeTransID = $this->getMelisEcomAttributeTransTable()->save($attributeTrans);
            $this->assertNotEmpty($attributeTransID, " Failed to insert attribute translations");            
            
            $prodAttributeID = $this->getMelisEcomProductAttributeTable()->save(array('patt_product_id' => $productID, 'patt_attribute_id' => $attributeID));
            $this->assertNotEmpty($prodAttributeID, "Failed to assign attribute to a product");
            $attributeVal = array_merge($attribute['melis_ecom_attribute_value'],
                array(
                    'atval_attribute_id' => $attributeID,
                )
                );
            
            $attributeValID = $this->getMelisEcomAttributeValueTable()->save($attributeVal);
            $this->assertNotEmpty($attributeValID, "Failed to insert attribute value");            
            
            $attrValTransId = $this->getMelisEcomProductVariantAttributeValueTable()->save(array('vatv_variant_id' => $variantID, 'vatv_attribute_value_id' => $attributeValID));
            $this->assertNotEmpty($attrValTransId);
            
            $attributeValTrans = array_merge($attribute['melis_ecom_attribute_value_trans'],
                array(
                    'av_attribute_value_id' => $attributeValID,
                    'avt_lang_id' => $langID,
                )
                );
            
            $attrValTransId = $this->getMelisEcomAttributeValueTransTable()->save($attributeValTrans);
            $this->assertNotEmpty($attrValTransId);
            
            $attributesID[] = $attributeID;
                       
        }
        
        return $attributesID;
    }
    
    /**
     * This removes test attribute data
     * @param unknown $payloads
     */
    private function removeAttribute($attributes)
    {
        foreach($attributes as $attribute){
            $attributeTable = $this->getMelisEcomAttributeTable();
            $attributeID  = $attributeTable->getEntryByField($attribute['column'], $attribute['value'])->current()->attr_id;            
            
            $attributeValTable = $this->getMelisEcomAttributeValueTable();
            $attrValID  = $attributeValTable->getEntryByField('atval_attribute_id', $attributeID)->current()->atval_id;            
            
            $attrValTransTable = $this->getMelisEcomAttributeValueTransTable();
            $attrValTransTable->deleteByField('av_attribute_value_id', $attrValID);
            $result = $attrValTransTable->getEntryByField('av_attribute_value_id', $attrValID);
            $this->assertEmpty($result, "Failed to delete attribute value translations");
            
            $prodVarAttrValTable = $this->getMelisEcomProductVariantAttributeValueTable();
            $prodVarAttrValTable->deleteByField('vatv_attribute_value_id', $attrValID);
            $result = $prodVarAttrValTable->getEntryByField('vatv_attribute_value_id', $attrValID);                
            $this->assertEmpty($result, "Failed to remove attribute from a variant");
            
            $attributeValTable->deleteByID($attrValID);
            $result = $attributeValTable->getEntryById($attrValID);
            $this->assertEmpty($result, "Faield to remove attribute value");
            
            $prodAttrTable = $this->getMelisEcomProductAttributeTable();
            $prodAttrTable->deleteByField('patt_attribute_id', $attributeID);
            $result = $prodAttrTable->getEntryByField('patt_attribute_id', $attributeID);
            $this->assertEmpty($result, "Failed to remove attribute from a product");
            
            $attrTransTable = $this->getMelisEcomAttributeTransTable();
            $attrTransTable->deleteByField('atrans_attribute_id', $attributeID);
            $result = $attrTransTable->getEntryByField('atrans_attribute_id', $attributeID);
            $this->assertEmpty($result, "Failed to delete attribute translations");
            
            $attributeTable->deleteById($attributeID);
            $result = $attributeTable->getEntryById($attributeID);
            $this->assertEmpty($result, "Failed to delete attribute");              
        }
    }
    
    /**
     * Inserts test client data
     * @param int $payloads
     * @param int $countryID
     * @param int $langID
     */
    private function insertClient($clients, $countryID, $langID)
    {
        $clientsID = array();
        $c = 1;
        foreach($clients as $client){            
            $clientID = $this->getMelisEcomClientTable()->save(array_merge($client['melis_ecom_client'], array('cli_country_id' => $countryID)));
            $this->assertNotEmpty($clientID);
            $clientPerson = array_merge($client['melis_ecom_client_person'],
                array(
                    'cper_client_id' => $clientID,
                    'cper_lang_id' => $langID,
                )
                );
            
            $clientPersonID = $this->getMelisEcomClientPersonTable()->save($clientPerson);
            $this->assertNotEmpty($clientPersonID);
            $clientCompanyID = $this->getMelisEcomClientCompanyTable()->save(array_merge($client['melis_ecom_client_company'], array('ccomp_client_id' => $clientID)));
            $this->assertNotEmpty($clientCompanyID);
            $clientAddress = array_merge($client['melis_ecom_client_address'],
                array(
                    'cadd_client_id' => $clientID,
                    'cadd_client_person' => $clientPersonID,
                ));
            
            $clientAddressId = $this->getMelisEcomClientAddressTable()->save($clientAddress);
            $this->assertNotEmpty($clientAddressId);
            
            $this->assertNotEmpty($clientID, "Failed to insert test client $c");
            $clientsID[] = $clientID;
                     
            $c++;
        }
        return $clientsID;
    }
    
    /**
     * Removes test client data
     * @param int $payloads
     */
    private function removeClient($clients)
    {
        $c = 1;
        foreach($clients as $client){
            $result = array();
            $clientID = $this->getMelisEcomClientPersonTable()->getEntryByField($client['column'], $client['value'])->current()->cper_client_id;
            $this->getMelisEcomClientAddressTable()->deleteByField('cadd_client_id', $clientID);
            $result = $this->getMelisEcomClientAddressTable()->getEntryByField('cadd_client_id', $clientID);
            $this->assertEmpty($result, "Failed to delete client addresses");
            
            $this->getMelisEcomClientCompanyTable()->deleteByField('ccomp_client_id', $clientID);
            $result = $this->getMelisEcomClientCompanyTable()->getEntryByField('ccomp_client_id', $clientID);
            $this->assertEmpty($result, "Failed to remove company");
            
            $this->getMelisEcomClientPersonTable()->deleteByField('cper_client_id', $clientID);
            $this->getMelisEcomClientTable()->deleteByField('cli_id', $clientID);
            $result = $this->getMelisEcomClientTable()->getEntryById($clientID)->toArray();
            $this->assertEmpty($result, "Failed to delete client");        
            $c++;
        }
    }
    
    private function insertOrder($orders, $clientID, $variantID, $currencyID, $countryID)
    {
        $ordersID = array();
        foreach($orders as $order){
            $clientPerID = $this->getMelisEcomClientPersonTable()->getEntryByField('cper_client_id', $clientID)->current()->cper_id;
            
            $orderData = array_merge($order['melis_ecom_order'], 
                array(
                    'ord_client_id' => $clientID,
                    'ord_client_person_id' => $clientPerID,
                    'ord_country_id' => $countryID,
                    
                )
            );
            //save order
            $orderID = $this->getMelisEcomOrderTable()->save($orderData);
            $this->assertNotEmpty($orderID, "Failed to insert order");
            
            $variant = $this->getMelisEcomVariantTable()->getEntryById($variantID)->current();            
            $productText = $this->getMelisEcomProductTextTable()->getEntryByField('ptxt_prd_id', $variant->var_prd_id)->current();
            $productCat = $this->getMelisEcomProductCategoryTable()->getEntryByField('pcat_prd_id', $variant->var_prd_id)->current();
            $categoryText = $this->getMelisEcomCategoryTransTable()->getEntryByField('catt_category_id', $productCat->pcat_cat_id)->current();
            $variantPrice = $this->getMelisEcomPriceTable()->getEntryByField('price_var_id', $variantID)->current();
            
            $basketData = array_merge($order['melis_ecom_order_basket'], 
                array(
                    'obas_order_id' => $orderID,
                    'obas_variant_id' => $variantID,
                    'obas_product_name' => $productText->ptxt_field_short,
                    'obas_sku' => $variant->var_sku,
                    'obas_category_id' => $productCat->pcat_cat_id,
                    'obas_category_name' => $categoryText->catt_name,
                    'obas_currency' => $currencyID,
                    'obas_price_net' => $variantPrice->price_net,
                    'obas_price_gross' => $variantPrice->price_gross,
                    'obas_price_vat' =>  $variantPrice->price_vat_price
                )
            );
            
            $orderBasketID = $this->getMelisEcomOrderBasketTable()->save($basketData);
            $this->assertNotEmpty($orderBasketID, "Failed to insert order basket");
            
            $billAddress = array_merge($order['melis_ecom_order_address'],
                array(
                    'oadd_order_id' => $orderID,
                    'oadd_type' => 1,
                )
            );
            //save billing address
            $billAddressID = $this->getMelisEcomOrderAddressTable()->save($billAddress);
            $this->assertNotEmpty($billAddressID, "Failed to insert order billing address");
            
            $shipAddress = array_merge($order['melis_ecom_order_address'],
                array(
                    'oadd_order_id' => $orderID,
                    'oadd_type' => 2,
                )
            );
            // save shipping/delivery address
            $shipAddressID = $this->getMelisEcomOrderAddressTable()->save($shipAddress);
            $this->assertNotEmpty($shipAddressID, "Failed to insert shipping address");
            
            $paymentData = array_merge($order['melis_ecom_order_payment'], 
                array(
                    'opay_order_id' => $orderID,
                    'opay_price_total' => $variantPrice->price_net + 100,
                    'opay_price_order' => $variantPrice->price_net,
                    'opay_price_shipping' => 100,
                    'opay_currency_id' => $currencyID,                    
                )
            );
            
            // save payment details
            $paymentID = $this->getMelisEcomOrderPaymentTable()->save($paymentData);
            $this->assertNotEmpty($paymentID, "Failed to insert order payment");
            
            $shippingData = array_merge($order['melis_ecom_order_shipping'],
                array(
                    'oship_order_id' => $orderID,      
                )
            );
            //save shipping details
            $shippingID = $this->getMelisEcomOrderShippingTable()->save($shippingData);
            $this->assertNotEmpty($shippingID, "Failed to insert order shipping details");
            
            $orderUpdate = array(
                'ord_billing_address' => $billAddressID,
                'ord_delivery_address' => $shipAddressID,
            );
            
            $this->getMelisEcomOrderTable()->save($orderUpdate, $orderID);
        }
    }
    
    private function removeOrder($orders)
    {
        foreach($orders as $order){
            $orderID = $this->getMelisEcomOrderTable()->getEntryByField($order['column'], $order['value'])->current()->ord_id;
            $this->getMelisEcomOrderPaymentTable()->deleteByField('opay_order_id', $orderID);
            $result = $this->getMelisEcomOrderPaymentTable()->getEntryByField('opay_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order payment");
            
            $this->getMelisEcomOrderAddressTable()->deleteByField('oadd_order_id', $orderID);
            $result = $this->getMelisEcomOrderAddressTable()->getEntryByField('oadd_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order addresses");
            
            $this->getMelisEcomOrderShippingTable()->deleteByField('oship_order_id', $orderID);
            $result = $this->getMelisEcomOrderShippingTable()->getEntryByField('oship_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order shipping details");
            
            $this->getMelisEcomOrderBasketTable()->deleteByField('obas_order_id', $orderID);
            $result = $this->getMelisEcomOrderBasketTable()->getEntryByField('obas_order_id', $orderID);
            $this->assertEmpty($result, "Failed to delete order basket");
            
            $this->getMelisEcomOrderTable()->deleteById($orderID);
            $result = $this->getMelisEcomOrderTable()->getEntryById($orderID);
            $this->assertEmpty($result, "Failed to delete order");
        }
    }
    
    /**
     * List of MelisCommerce db tables
     * @return array
     */
    private function getMelisCommerceTables()
    {
        return array(
          'melis_ecom_assoc_variant'        =>  $this->getMelisEcomAssocVariantTable(),
          'melis_ecom_assoc_variants_type'  =>  $this->getMelisEcomAssocVariantTypeTable(),
          'melis_ecom_attribute'            =>  $this->getMelisEcomAttributeTable(),
          'melis_ecom_attribute_trans'      =>  $this->getMelisEcomAttributeTransTable(),
          'melis_ecom_attribute_type'       =>  $this->getMelisEcomAttributeTypeTable(),
          'melis_ecom_attribute_value'      =>  $this->getMelisEcomAttributeValueTable(),
          'melis_ecom_attribute_value_trans'=>  $this->getMelisEcomAttributeValueTransTable(),
          'melis_ecom_basket_anonymous'     =>  $this->getMelisEcomBasketAnonymousTable(),
          'melis_ecom_basket_persistent'    =>  $this->getMelisEcomBasketPersistentTable(),
          'melis_ecom_category'             =>  $this->getMelisEcomCategoryTable(),
          'melis_ecom_category_trans'       =>  $this->getMelisEcomCategoryTransTable(),
          'melis_ecom_civility_trans'       =>  $this->getMelisEcomCivilityTransTable(),
          'melis_ecom_client_address'       =>  $this->getMelisEcomClientAddressTable(),
          'melis_ecom_client_address_type'  =>  $this->getMelisEcomClientAddressTypeTable(),
          'melis_ecom_client_address_type'  =>  $this->getMelisEcomClientAddressTypeTransTable(),
          'melis_ecom_client_company'       =>  $this->getMelisEcomClientCompanyTable(),
          'melis_ecom_client_person'        =>  $this->getMelisEcomClientPersonTable(),
          'melis_ecom_client'               =>  $this->getMelisEcomClientTable(),
          'melis_ecom_country_category'     =>  $this->getMelisEcomCountryCategoryTable(),
          'melis_ecom_country'              =>  $this->getMelisEcomCountryTable(),
          'melis_ecom_coupon_client'        =>  $this->getMelisEcomCouponClientTable(),
          'melis_ecom_coupon_order'         =>  $this->getMelisEcomCouponOrderTable(),
          'melis_ecom_coupon'               =>  $this->getMelisEcomCouponTable(),
          'melis_ecom_currency'             =>  $this->getMelisEcomCurrencyTable(),
          'melis_ecom_doc_relations'        =>  $this->getMelisEcomDocRelationsTable(),
          'melis_ecom_doc_type'             =>  $this->getMelisEcomDocTypeTable(),
          'melis_ecom_document'             =>  $this->getMelisEcomDocumentTable(),
          'melis_ecom_lang'                 =>  $this->getMelisEcomLangTable(),
          'melis_ecom_order_address'        =>  $this->getMelisEcomOrderAddressTable(),
          'melis_ecom_order_basket'         =>  $this->getMelisEcomOrderBasketTable(),
          'melis_ecom_order_message'        =>  $this->getMelisEcomOrderMessageTable(),
          'melis_ecom_order_payment'        =>  $this->getMelisEcomOrderPaymentTable(),
          'melis_ecom_order_payment_type'   =>  $this->getMelisEcomOrderPaymentTypeTable(),
          'melis_ecom_order_shipping'       =>  $this->getMelisEcomOrderShippingTable(),
          'melis_ecom_order_status'         =>  $this->getMelisEcomOrderStatusTable(),
          'melis_ecom_order_status_trans'   =>  $this->getMelisEcomOrderStatusTransTable(),
          'melis_ecom_order'                =>  $this->getMelisEcomOrderTable(),
          'melis_ecom_price'                =>  $this->getMelisEcomPriceTable(),
          'melis_ecom_product_attribute'    =>  $this->getMelisEcomProductAttributeTable(),
          'melis_ecom_product_category'     =>  $this->getMelisEcomProductCategoryTable(),
          'melis_ecom_product'              =>  $this->getMelisEcomProductTable(),
          'melis_ecom_product_text'         =>  $this->getMelisEcomProductTextTable(),
          'melis_ecom_product_text_type'    =>  $this->getMelisEcomProductTextTypeTable(),
          'melis_ecom_variant_attribute_value'  =>  $this->getMelisEcomProductVariantAttributeValueTable(),
          'melis_ecom_seo'                  =>  $this->getMelisEcomSeoTable(),
          'melis_ecom_variant_stock'        =>  $this->getMelisEcomVariantStockTable(),
          'melis_ecom_variant'              =>  $this->getMelisEcomVariantTable(),
        );
    }


}

