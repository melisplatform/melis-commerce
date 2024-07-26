<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;

class AttributeValueTranslation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_attribute_value_trans';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'avt_id';

    public function attributeValue()
    {
        return $this->belongsTo('MelisCommerce\Model\AttributeValue', 'av_attribute_value_id');
    }
}
