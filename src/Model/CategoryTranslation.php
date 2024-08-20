<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;

class CategoryTranslation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_category_trans';

    protected $appends = ['locale'];

    public function language()
    {
        return $this->belongsTo(Language::class, 'catt_lang_id', 'elang_id');
    }

    public function getLocaleAttribute()
    {
        return $this->language->lang_locale;
    }
}
