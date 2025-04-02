<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use MelisCommerce\Model\Product;
use MelisCommerce\Model\Variant;

class Contact extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_client_person';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cper_id';
}
