<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomSeoTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_seo';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'eseo_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getSeoByTypeAndId($type, $id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        if ($type == 'categoryId')
            $select->where(array('eseo_category_id' => $id));
        else
            if ($type == 'productId')
            $select->where(array('eseo_product_id' => $id));
        else
                if ($type == 'variantId')
            $select->where(array('eseo_variant_id' => $id));
        else
            return null;

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getSeoUrlByType($type, $url)
    {
        $select = $this->tableGateway->getSql()->select();

        if (in_array($type, array('category', 'product', 'variant'))) {
            $select->where('eseo_' . $type . '_id IS NOT NULL');
        }

        $select->where(array('eseo_url' => $url));

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getCategorySeoById($categoryId = null, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!is_null($categoryId)) {
            $select->where->equalTo('eseo_category_id', (int)$categoryId);
        }

        if (!is_null($langId)) {
            $select->where->equalTo('eseo_lang_id', (int)$langId);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}
