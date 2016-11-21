<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use MelisCommerce\Model\MelisEcomCategory;

class MelisEcomCategoryTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cat_id';
    }
    
    /**
     * Get Category List By Category Id
     * @param int $categoryId If not specified, it will bring back the root categories.
	 * @param int $langId If specified, translations of category will be limited to that lang
	 * @param boolean $onlyValid if true, returns only active status and valid range of dates categories
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all categories of the list
     * @param int $fatherId If Zero (0), this will return the root of the category
     * @return int Array
     */
    public function getCategoryChildrenListById($categoryId, $langId, $onlyValid, $start, $limit, $fatherId = 0)
    {
        
        $select = $this->tableGateway->getSql()->select();
        
        if ($fatherId == 0){
            $select->where('cat_father_cat_id = '.$categoryId);
        }else{
            $select->where('cat_father_cat_id = '.$fatherId);
        }
        
        if (is_bool($onlyValid)&&$onlyValid){
            $select->where('cat_status = 1');
            $select->where('cat_date_valid_start <= "'. date('Y-m-d H:i:s').'"');
            $select->where('cat_date_valid_end >= "'. date('Y-m-d H:i:s').'"');
        }
        
        if($fatherId == 0){
            if (is_numeric($start)&&$start!=0){
                $select->offset($start);
            }
                
            if (is_numeric($limit)&&$limit!=0&&!is_null($limit)){
                $select->limit($limit);
            }
        }
        
        $select->order('cat_order ASC');
        
        $dataCategory = $this->tableGateway->selectWith($select);
        
        return $dataCategory;
    }
    
    /**
     * Get Category Deatils and Translation
     * @param int $categoryId
     * @param int $langId
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getCategoryTranslationBylangId($categoryId, $langId){
        
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('cat_id'));
        $select->join('melis_ecom_category_trans', 'melis_ecom_category_trans.catt_category_id = melis_ecom_category.'.$this->idField,
            array('*'), $select::JOIN_RIGHT);
        $select->join('melis_ecom_lang', 'melis_ecom_lang.elang_id = melis_ecom_category_trans.catt_lang_id',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('melis_ecom_category.cat_id = '.$categoryId);
        
        if (!is_null($langId)){
            $select->where->equalTo('catt_lang_id', $langId)->and->equalTo('melis_ecom_lang.elang_status', 1);
        }
        
        $dataCategory = $this->tableGateway->selectWith($select);
        return $dataCategory;
    }
    
    /**
     * Get Category Country/Countries by Category ID
     * @param int $categoryId
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getCategoryCountriesByCategoryId($categoryId){
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('cat_id'));
        $select->join('melis_ecom_country_category', 'melis_ecom_country_category.ccat_category_id = melis_ecom_category.'.$this->idField,
            array(), $select::JOIN_RIGHT);
        $select->join('melis_ecom_country', 'melis_ecom_country.ctry_id = melis_ecom_country_category.ccat_country_id',
            array('*'), $select::JOIN_LEFT);
        $select->group('ctry_id');
        
        $select->where->equalTo('cat_id', $categoryId)->and->equalTo('melis_ecom_country.ctry_status', 1);
        
        $dataCategory = $this->tableGateway->selectWith($select);
        return $dataCategory;
    }
    
    /**
     * Get Products that related to the Category
     * @param int $categoryId
     * @param int $langId
     * @param boolean $onlyValid
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getCategoryProductsTextById($categoryId, $langId, $onlyValid){
        
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array());
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_cat_id = melis_ecom_category.'.$this->idField,
            array(), $select::JOIN_RIGHT);
        $select->join('melis_ecom_product', 'melis_ecom_product.prd_id = melis_ecom_product_category.pcat_prd_id',
            array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_id = melis_ecom_product.prd_id',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('cat_id = '.$categoryId);
        
        if (is_bool($onlyValid)&&$onlyValid){
            $select->where('prd_status = 1');
        }
        
        if (!is_null($langId)&&is_numeric($langId)){
            $select->where('ptxt_lang_id = '.$langId);
        }
        
        $dataCategory = $this->tableGateway->selectWith($select);
        return $dataCategory;
    }
 
    /**
     *  Getting Categories under Category ID
     * @param int $categoryId
     * @param boolean $onlyValid
     * @param int $fatherId If Zero (0), this will return the root of the category
     * @return int Array()
     */
    public function getSubCategoryIdById($categoryId, $onlyValid, $fatherId = 0)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if (is_null($onlyValid)){
            return array();
        }
        
        if ($fatherId == 0){
            if(!in_array($categoryId, array('-1'))&&is_numeric($categoryId)){
                $categoryId = (Int) $categoryId;
                $select->where('cat_id = '.$categoryId);
            }else{
                $select->where('cat_father_cat_id = -1');
            }
        }else{
            $select->where('cat_father_cat_id = '.$fatherId);
        }
        
        if (is_bool($onlyValid)&&$onlyValid){
            $select->where('cat_status = 1');
            $select->where('cat_date_valid_start <= "'. date('Y-m-d H:i:s').'"');
            $select->where('cat_date_valid_end >= "'. date('Y-m-d H:i:s').'"');
        }
        
        $select->order('cat_order ASC');
        
        $dataCategory = $this->tableGateway->selectWith($select);
        $dataCategoryData = $dataCategory->toArray();
        
        return $dataCategoryData;
    }
    
    /**
     * Get Category Data By father Id
     * @param int $fatherId
     * @return MelisEcomCategory Object
     */
    public function getCategoryByFatherId($fatherId = 0){
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array(new \Zend\Db\Sql\Expression('cat_id As id'), 'cat_id', 'cat_status', 'cat_father_cat_id'));
        
        if ($fatherId == 0){
            $select->where('cat_father_cat_id = -1');
        }else{
            $select->where('cat_father_cat_id = '.$fatherId);
        }
        
        $select->order('cat_order ASC');
        
        $dataCategory = $this->tableGateway->selectWith($select);
        
        return $dataCategory;
    }
    
    /**
     * Get Category Transalations
     * @param int $catId
     * @param int $langId
     * @param booloean $anyLang, if not specified this will return 1 translation of the category in any language
     * @return MelisEcomCategory Object
     */
    public function getCategoryNameAsTextById($catId, $langId, $anyLang = false){
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array(new \Zend\Db\Sql\Expression('CONCAT(cat_id," - ",catt_name) As text')));
        $select->join('melis_ecom_category_trans', 'melis_ecom_category_trans.catt_category_id = melis_ecom_category.'.$this->idField,
            array('catt_lang_id'), $select::JOIN_RIGHT);
        
        if ($anyLang){
            $select->join('melis_ecom_lang', 'melis_ecom_lang.elang_id = melis_ecom_category_trans.catt_lang_id',
                array('elang_name'), $select::JOIN_LEFT);
            $select->limit(1);
        }else{
            $select->where('catt_lang_id = '.$langId);
        }
        
        $select->where('cat_id = '.$catId);
        
        
        $dataCategoryTrans = $this->tableGateway->selectWith($select);
        
        return $dataCategoryTrans;
    }
    
    /**
     * Get Category Children By Father Id
     * @param int $fatherId
     * @return MelisEcomCategory Object
     */
    public function getChildrenCategoriesOrderedByOrder($fatherId){
        
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('cat_id','cat_father_cat_id','cat_order'));
        $select->where('cat_father_cat_id ='.$fatherId);
        $select->order('cat_order ASC');
        
        $dataCategory = $this->tableGateway->selectWith($select);
        
        return $dataCategory;
    }
}