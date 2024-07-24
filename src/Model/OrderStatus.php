<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;
use MelisCommerce\Model\Category;

class OrderStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_order_status';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'osta_id';
}
