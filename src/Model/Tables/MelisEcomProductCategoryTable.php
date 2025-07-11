<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Expression;

class MelisEcomProductCategoryTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_product_category';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'pcat_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getCategoryProductsByCategoryId($categoryId, $onlyValid = false)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_product',
            'melis_ecom_product.prd_id = melis_ecom_product_category.pcat_prd_id',
            array('*'),
            $select::JOIN_LEFT
        );

        if (is_bool($onlyValid) && $onlyValid) {
            $select->where('prd_status = 1');
        }

        $select->where->equalTo('pcat_cat_id', (int)$categoryId);
        $select->order(array('pcat_order' => 'ASC'));

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    public function getCategoryProductCount($categoryId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('count' => new Expression('COUNT(DISTINCT pcat_prd_id)')));
        $select->where->equalTo('pcat_cat_id', (int)$categoryId);
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getProductCategories($productId, $status = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_category',
            'melis_ecom_category.cat_id = melis_ecom_product_category.pcat_cat_id',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('pcat_prd_id', (int)$productId);

        if (!is_null($status))
            $select->where->equalTo('cat_status', (int)$status);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}
