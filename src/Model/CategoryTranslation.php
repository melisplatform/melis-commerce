<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_category_trans';

    public function language()
    {
        return $this->belongsTo(Language::class, 'catt_lang_id', 'elang_id');
    }

    public function getLocaleAttribute()
    {
        return $this->language->lang_locale;
    }
}
