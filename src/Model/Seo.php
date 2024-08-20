<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;
use MelisCommerce\Model\Category;

class Seo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_seo';

    public function category()
    {
        return $this->hasOne(Category::class, 'eseo_category_id', 'cat_id');
    }
}
