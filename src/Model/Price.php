<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;
use MelisCommerce\Model\Product;
use MelisCommerce\Model\Variant;

class Price extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_price';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'price_id';

    public function product()
    {
        return $this->belongsTo(Product::class, 'price_prd_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'price_var_id');
    }

    public function scopeGetPricesByVariantId(
        $query,
        $variantId,
        $groupId = 1 
    ) {
        $query->where('price_var_id', '=', $variantId);

        if ($groupId >= 1) {
            $query->where('price_group_id', '=', $groupId);
        }

        return $query;
    }

    public function scopeGetPricesByProductId(
        $query,
        $productId,
        $countryId = null,
        $groupId = 1
    ) {
        $query->where('price_prd_id', '=', $productId);

        if ($groupId >= 1) {
            $query->where('price_group_id', '=', $groupId);
        }
    }


}
