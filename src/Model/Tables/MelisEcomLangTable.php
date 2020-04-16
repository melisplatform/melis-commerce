<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomLangTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_lang';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'elang_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    // get all lang ordered by name ASC
    public function langOrderByName()
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->where->equalTo('elang_status', 1);
        $order = 'elang_name ASC';
        $select->order($order);
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
    
}