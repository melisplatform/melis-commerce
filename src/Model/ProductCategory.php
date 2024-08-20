<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use MelisCommerce\Model\Category;
use MelisCommerce\Model\Product;

class ProductCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_product_category';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pcat_id';

    public function product()
    {
        return $this->belongsTo(Product::class, 'pcat_prd_id', 'prd_id');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'melis_ecom_category', 'cat_id', 'pcat_cat_id');
    }
}
