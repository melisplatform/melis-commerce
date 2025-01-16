<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;

class AttributeTranslation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_attribute_trans';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'atrans_id';
}
