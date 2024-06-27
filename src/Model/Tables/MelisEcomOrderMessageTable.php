<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomOrderMessageTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_message';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'omsg_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getOrderMessageByOrderId($orderId, $msgType = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        if(!empty($msgType))
            $select->where->equalTo('omsg_type', $msgType);

        $select->where('omsg_order_id ='.$orderId);
        $select->order('omsg_date_creation');
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}