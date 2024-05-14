<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;
use MelisCommerce\Model\CategoryTranslation;
use MelisCommerce\Model\ProductCategory;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_category';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cat_id';

    protected $casts = [
        'cat_id' => 'integer',
        'cat_father_cat_id' => 'integer',
        'cat_order' => 'integer',
        'cat_status' => 'integer',
        'cat_date_creation' => 'datetime',
        'cat_date_edit' => 'datetime',
    ];

    protected $appends = [
        'icon',
        'id',
        'text',
        'type',
        'textLang',
        'a_attr',
    ];

    protected static $textLang = '';

    public static function setLocale($locale = 'en_US')
    {
        self::$textLang = $locale;

        return new static;
    }

    public function scopeGetTree($query, $parent = -1, $languageId)
    {
        return $query->with([
            'children',
            'translations' => function ($query) use ($languageId) {
                return $query->where('catt_lang_id', $languageId);
            }
        ])
        ->where('cat_father_cat_id', $parent);
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function child()
    {
        return $this->hasMany(Category::class, 'cat_father_cat_id', 'cat_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'cat_father_cat_id', 'cat_id');
    }

    public function children()
    {
        return $this->child()->with(['children', 'translations']);
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class, 'catt_category_id', 'cat_id');
    }

    public function getIconAttribute()
    {
        return 'fa fa-circle ' . ($this->cat_status ? 'text-success' : 'text-danger');
    }

    public function getIdAttribute()
    {
        return "{$this->cat_id}_categoryId";
    }

    public function getTextAttribute()
    {
        return $this->cat_id . ' - ' . optional($this->translations->first())->catt_name;
    }

    public function getTypeAttribute()
    {
        return 'catalog';
    }

    public function getTextLangAttribute()
    {
        return self::$textLang;
    }

    public function products()
    {
        return $this->hasMany(ProductCategory::class, 'pcat_cat_id', 'cat_id');
    }

    public function getA_AttrAttribute()
    {
        return $this->attributes['a_attr'] = [
            "data-seopage" => "",
            // @INFO: This causes a second delay (without this it should execute below 1 second)
            "data-numprods" =>  0, //$this->products->count(),
            "data-textlang" => self::$textLang,
            "data-fathericon" => "<i class=\"fa fa-book\"></i>",
            "data-fathercateid" => $this->cat_father_cat_id,
        ];
    }
}
