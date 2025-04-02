<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;

class AttributeValue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_attribute_value';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'atval_id';

    public function attribute()
    {
        return $this->belongsTo('MelisCommerce\Model\Attribute', 'atval_attribute_id');
    }

    public function types()
    {
        return $this->hasMany('MelisCommerce\Model\AttributeType', 'atval_type_id');
    }

    public function translations()
    {
        return $this->hasMany('MelisCommerce\Model\AttributeValueTranslation', 'av_attribute_value_id');
    }
}
