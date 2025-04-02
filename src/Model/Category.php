<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
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

    protected $fillable = [
        'cat_id',
        'cat_father_cat_id',
        'cat_order',
        'cat_status',
        'cat_reference',
        'cat_date_valid_start',
        'cat_date_valid_end',
        'cat_date_creation',
        'cat_user_id_creation',
        'cat_date_edit',
        'cat_user_id_edit'
    ];
    protected $appends = [
        'icon',
        'id',
        'text',
        'type',
        'textLang',
        'a_attr',
        'state'
    ];

    protected static $languageModel = null;
    protected static $hasTranslation = true;
    protected static $languageLocale = 'en_EN';

    public static function setLanguageModel($languageModel)
    {
        self::$languageModel = $languageModel;

        return new static;
    }

    private function getLanguageModel()
    {
        return self::$languageModel;
    }

    public static function setHasTranslation($hasTranslation)
    {
        self::$hasTranslation = $hasTranslation;

        return new static;
    }

    public static function setLanguageLocale($languageLocale)
    {
        self::$languageLocale = $languageLocale;

        return new static;
    }

    public static function getLanguageLocale()
    {
        return self::$languageLocale;
    }


    private function hasTranslation()
    {
        return self::$hasTranslation;
    }

    public function scopeGetTree($query, $parent = -1, $languageId = null)
    {
        $query =  $query->with([
            'children',
            'translations'
        ]);

        if (!is_null($languageId)) {
            $query->whereHas('translations', function ($query) use ($languageId) {
                $query->where('catt_lang_id', $languageId);
            });
        }
        return $query->where('cat_father_cat_id', $parent);
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
        $languageId = $this->getLanguageModel()->elang_id ?? 1;

        $label = array_filter($this->translations->where('catt_lang_id', $languageId)->toArray(), function ($text) {
            return strlen($text['catt_name']);
        });

        if (!current($label)) {
            $this->setHasTranslation(false);
            $label = array_filter($this->translations->toArray(), function ($text) {
                return strlen($text['catt_name']);
            });
        }

        $label = current($label);
        $this->setLanguageLocale($label['language']['elang_locale'] ?? 'en_EN');

        $text = $label['catt_name'] ?? $this->cat_reference;

        return strlen($text) ? $this->cat_id . ' - ' . $text : $this->cat_id;
    }

    public function getTypeAttribute()
    {
        return $this->cat_father_cat_id === -1 ? 'catalog' : 'category';
    }

    public function getTextLangAttribute()
    {
        return $this->getLanguageModel()->elang_locale ?? 'en_EN';
    }

    public function products()
    {
        return $this->hasMany(ProductCategory::class, 'pcat_cat_id', 'cat_id');
    }

    public function getA_AttrAttribute()
    {
        $languageModel = $this->getLanguageModel();
        $locale = $languageModel?->elang_locale === $this->getLanguageLocale() ? null : $this->getLanguageLocale();

        if (! is_null($locale)) {
            $locale = explode('_', $this->getLanguageLocale());
            $locale = '<strong>' . strtoupper($locale[1]) . '</strong>';
        }

        return $this->attributes['a_attr'] = [
            "data-seopage" => "",
            // @INFO: This causes a second delay (without this it should execute below 1 second)
            "data-numprods" =>  $this->products->count(),
            "data-textlang" => $locale,
            "data-fathericon" => $this->isCatalog() ? "<i class=\"fa fa-book\"></i>" : '',
            "data-fathercateid" => $this->cat_father_cat_id,
        ];
    }

    public function getStateAttribute()
    {
        return [
            'opened' => false,
            'selected' => false,
            'checked' => false,
        ];
    }

    /**
    * Get Category Children By Parent Id
    * @param int $fatherId
    * @return Collection
    */
    public function scopeGetChildrenCategoriesOrderedByOrder($query, $fatherId)
    {
        return $query->where('cat_father_cat_id', $fatherId)
            ->select('cat_id', 'cat_father_cat_id', 'cat_order')
            ->orderBy('cat_order', 'asc')
            ->get()->makeHidden($this->getArrayableAppends());
    }

    private function isCatalog(): bool
    {
        return $this->type === 'catalog';

    }
}
