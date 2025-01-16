<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;

class OrderBasket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_order_basket';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'obas_id';
}
