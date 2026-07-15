<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use MelisCommerce\Model\Product;

class ProductText extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_product_text';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ptxt_id';

    public function product()
    {
        return $this->belongsTo(Product::class, 'ptxt_prd_id', 'prd_id');
    }
}
