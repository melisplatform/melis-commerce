<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;

class AttributeType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_attribute_type';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'atype_id';
}
