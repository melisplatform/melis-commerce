<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_product_attribute';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'patt_id';

    public function product()
    {
        return $this->belongsTo('MelisCommerce\Model\Product', 'patt_product_id');
    }

    public function attribute()
    {
        return $this->belongsTo('MelisCommerce\Model\Attribute', 'patt_attribute_id');
    }
}
