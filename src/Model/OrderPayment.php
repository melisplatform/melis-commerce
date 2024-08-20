<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;
use MelisCommerce\Model\Category;

class OrderPayment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_order_payment';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'opay_id';
}
