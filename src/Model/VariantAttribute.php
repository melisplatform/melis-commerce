<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_variant_attribute_value';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'vatv_id';

    public function variant()
    {
        return $this->belongsTo('MelisCommerce\Model\Variant', 'vatv_variant_id');
    }

    public function attribute()
    {
        return $this->belongsTo('MelisCommerce\Model\Attribute', 'vatv_attribute_value_id');
    }
}
