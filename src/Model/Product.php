<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use MelisCommerce\Model\ProductCategory;
use MelisCommerce\Model\ProductText;
use MelisCommerce\Model\Variant;
use MelisCommerce\Model\Price;
use MelisCommerce\Model\ProductAttribute;

/**
 * @class Product
 * @property int $prd_id
 * @property string $prd_reference
 *
 * @property-read ProductCategory[] $category
 */
class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_product';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'prd_id';

    protected $appends = [
        'label',
        'DT_RowId',
        'DT_RowData',
        'product_image',
        'product_categories',
        'product_table_checkbox',
        'product_name',
    ];

    protected static $dataTable = false;
    protected static $documenService = null;
    protected static $languageId = null;
    protected static $tooltipTable = null;
    protected static $tooltipColumns = [];
    protected static $type = 'product';

    public static function setDataTable($dataTable = false)
    {
        self::$dataTable = $dataTable;

        return new static;
    }

    public static function setDocumentService($documentService)
    {
        self::$documenService = $documentService;

        return new static;
    }

    public static function setLanguageId($languageId)
    {
        self::$languageId = $languageId;

        return new static;
    }

    public static function setTooltipService($tooltipService)
    {
        self::$tooltipTable = $tooltipService;

        return new static;
    }

    public static function setTooltipColumns($columns)
    {
        self::$tooltipColumns = $columns;

        return new static;
    }

    public static function setType($type)
    {
        self::$type = $type ?: 'product';

        return new static;
    }

    public function category()
    {
        return $this->hasMany(ProductCategory::class, 'pcat_prd_id', 'prd_id');
    }

    public function categories()
    {
        return $this->hasMany(ProductCategory::class, 'pcat_prd_id', 'prd_id');
    }

    public function productTexts()
    {
        return $this->hasMany(ProductText::class, 'ptxt_prd_id', 'prd_id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'var_prd_id', 'prd_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'price_id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'patt_product_id', 'prd_id');
    }

    public function scopeGetProducts(
        $query,
        $langId = null,
        $categoryIds = [],
        $countryId = null,
        $onlyValid = null,
        $start = 0,
        $limit = null,
        $orderBy = 'prd_id',
        $order = 'ASC',
        $search = '',
        $forPagination = false
    ) {
        self::setLanguageId($langId);
        $query = $query->select('melis_ecom_product.*')
            ->with([
                'categories' => function ($query) use ($categoryIds) {
                    $query->select(['melis_ecom_product_category.*', 'melis_ecom_category_trans.*'])
                        ->leftJoin('melis_ecom_category_trans', 'catt_category_id', '=', 'pcat_cat_id');

                    if (!empty($categoryIds)) {
                        $query->whereIn('pcat_cat_id', $categoryIds);
                    }
                },
                'productTexts' => function ($query) use ($langId, $search) {
                    $query->select('melis_ecom_product_text.*');
                },
                'variants' => function ($query) use ($countryId) {
                    $query->select('melis_ecom_variant.*')
                        ->with([
                            'prices' => function ($query) use ($countryId) {
                                $query->select('melis_ecom_price.*')
                                    ->where('price_country_id', $countryId);
                            }
                        ]);
                },
                'prices' => function ($query) use ($countryId) {
                    $query->select('melis_ecom_price.*')
                        ->where('price_country_id', $countryId);
                },
            ])
            ->distinct();

        if (!is_null($onlyValid)) {
            $query->where('prd_status', $onlyValid);
        }

        if (!empty($search)) {
            $query->where('prd_reference', 'like', "%$search%")
                ->orWhere('prd_id', 'like', "%$search%");

            $query->orWhere(function ($query) use ($search) {
                $query->whereHas('productTexts', function ($query) use ($search) {
                    $query->where('ptxt_field_short', 'like', "%$search%");
                });
            });
        }

        if (!empty($categoryIds)) {
            $query->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('pcat_cat_id', $categoryIds);
            });
        }

        if ((!empty($limit) && $limit > 0) && !$forPagination) {
            $query->limit($limit);
        }

        if ((!empty($start) && $start > 0) && !$forPagination) {
            $query->offset($start);
        }

        $query->orderBy($orderBy, $order);

        return $query;
    }

    private function isForDataTable()
    {
        return self::$dataTable;
    }

    private function documentService()
    {
        return self::$documenService;
    }

    public function getLabelAttribute()
    {
        $label = array_filter($this->productTexts->pluck('ptxt_field_short')->toArray(), function ($text) {
            return !empty($text) || !is_null($text);
        });

        return current($label) ?: $this->prd_reference;
    }


    public function getDT_RowDataAttribute()
    {
        return ['productname', $this->label];
    }

    public function getDT_RowIdAttribute()
    {
        return $this->prd_id;
    }

    public function getprd_idAttribute()
    {
        $format = '<span data-productname="%s">%s</span>';
        return $this->isForDataTable() ? sprintf($format, $this->label, $this->prd_id) : $this->prd_id;
    }

    public function getprd_statusAttribute()
    {
        $status = $this->prd_status ? 'text-success' : 'text-danger';
        return "<span class='$status'><i class='fa fa-fw fa-circle'></i></span>";
    }

    public function getproduct_imageAttribute()
    {
        if (is_null($this->documentService())) {
            return '';
        }

        $prodImage = '<img src="%s" width="60" height="60" class="rounded-circle img-fluid"/>';
        return sprintf($prodImage, $this->documentService()->getDocDefaultImageFilePath('product', $this->prd_id));
    }

    public function getproduct_categoriesAttribute()
    {
        $category = array_filter($this->categories->pluck('catt_name')->toArray(), function ($category) {
            return $category !== '';
        });

        return array_map(function ($cat) {
            $template = '<span class="cell-val-table" style="margin:0 2px 4px 0;display:inline-block;padding: 3px 10px;background: #ECEBEB;border-radius: 4px;color: #7D7B7B;">%s</span>';
            return sprintf($template, $cat);
        }, $category);
    }

    public function getproduct_table_checkboxAttribute()
    {
        $format = '<div class="checkbox checkbox-single margin-none" data-product-id="%s">
                        <label class="checkbox-custom">
                            <i class="fa fa-fw fa-square-o"></i>
                            <input type="checkbox" class="check-product">
                        </label>
                    </div>';

        return sprintf($format, $this->prd_id);
    }

    public function getproduct_nameAttribute()
    {
        $format = '<a id="row-%s" class="toolTipHoverEvent tooltipTable" data-productId="%s" data-hasqtip="1" aria-describedby="qtip-%s">%s</a>';

        if ($this->isMobile()) {
            return sprintf($format, $this->prd_id, $this->prd_id, $this->prd_id, $this->label);
        }

        if (is_null(self::$tooltipTable)) {
            return sprintf($format, $this->prd_id, $this->prd_id, $this->prd_id, $this->label);
        }

        // add tooltip code here
        self::$tooltipTable->setTable(self::$type . 'Table' . $this->prd_id, 'table-row-' . $this->prd_id . ' ' . self::$type . 'Table' . $this->prd_id, '');
        self::$tooltipTable->setColumns(self::$tooltipColumns);
        return sprintf($format, $this->prd_id, $this->prd_id, $this->prd_id, $this->label) . self::$tooltipTable->render();
    }

    private function isMobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
    }
}