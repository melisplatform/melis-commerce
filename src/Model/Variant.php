<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use MelisCommerce\Model\Product;
use MelisCommerce\Model\Price;
use MelisCommerce\Model\VariantAttribute;

class Variant extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_variant';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'var_id';

    protected static $dataTable = false;
    protected static $documenService = null;
    protected static $languageId = null;
    protected static $tooltipTable = null;
    protected static $tooltipColumns = [];
    protected static $type = 'variant';

    protected $appends = [
        'DT_RowId',
        'DT_RowData',
        'var_image',
        'var_attributes',
    ];

    protected $fillable = [
        'var_id',
        'var_prd_id',
        'var_sku',
        'var_status',
        'var_main_variant'
    ];

    public $timestamps = false;

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
        self::$type = $type ?: 'variant';

        return new static;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'var_prd_id', 'prd_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'price_var_id');
    }

    public function scopeGetProductVariants(
        $query,
        $productId,
        $langId = null,
        $onlyValid = null,
        $start = 0,
        $limit = null,
        $orderBy = 'var_main_variant',
        $order = 'ASC',
        $search = '',
    ) {
        self::setLanguageId($langId);
        $query = $query->select([
            'melis_ecom_variant.*',
        ])->where('var_prd_id', $productId);
        $query->with([
            'variantAttributes' => function ($query) use ($langId) {
                $query->select([
                    'melis_ecom_variant_attribute_value.*',
                    'melis_ecom_attribute_value.*',
                    'melis_ecom_attribute_trans.*',
                    'melis_ecom_attribute_value_trans.avt_lang_id',
                    'melis_ecom_attribute_value_trans.avt_v_int',
                    'melis_ecom_attribute_value_trans.avt_v_float',
                    'melis_ecom_attribute_value_trans.avt_v_bool',
                    'melis_ecom_attribute_value_trans.avt_v_varchar',
                    'melis_ecom_attribute_value_trans.avt_v_text',
                    'melis_ecom_attribute_value_trans.avt_v_datetime',
                    'melis_ecom_attribute_value_trans.avt_v_binary',
                    'melis_ecom_attribute_type.*'
                ])
                    ->leftJoin('melis_ecom_attribute_value', 'atval_id', '=', 'vatv_attribute_value_id')
                    ->leftJoin('melis_ecom_attribute_trans', 'atrans_attribute_id', '=', 'atval_attribute_id')
                    ->leftJoin('melis_ecom_attribute_type', 'atype_id', '=', 'atval_type_id')
                    ->leftJoin('melis_ecom_attribute_value_trans', function ($join) use ($langId) {
                        $join->on('av_attribute_value_id', '=', 'vatv_attribute_value_id')
                            ->where('melis_ecom_attribute_value_trans.avt_lang_id', '=', $langId);
                    });
            }
        ]);

        if (!is_null($onlyValid)) {
            $query->where('var_status', $onlyValid);
        }

        if (!empty($search)) {
            $query->where('var_sku', 'like', "%$search%")
                ->orWhere('var_id', 'like', "%$search%");
        }

        if ((!empty($limit) && $limit > 0)) {
            $query->limit($limit);
        }

        if ((!empty($start) && $start > 0)) {
            $query->offset($start);
        }

        $query->orderBy($orderBy, $order);

        return $query;
    }

    public function getDT_RowIdAttribute()
    {
        return $this->var_id;
    }

    public function getvar_main_variantAttribute()
    {
        $icon = '<span class="text-success"><i class="fa fa-fw fa-star"></i></span>';

        return $this->var_main_variant ? $icon : '';
    }

    public function variantAttributes()
    {
        return $this->hasMany(VariantAttribute::class, 'vatv_variant_id', 'var_id');
    }

    public function getvar_attributesAttribute()
    {
        return $this->variantAttributes
            ->map(function ($attribute) {
                $value = $attribute['avt_v_' . $attribute['atype_column_value']] ?? null;
                if ($value === '' || $value === null) {
                    return null;
                }

                return sprintf(
                    '<span class="btn btn-default cell-val-table" title="%1$s: %2$s" style="border-radius: 4px;color: #7D7B7B;">%1$s: %2$s</span>',
                    $attribute['atrans_name'],
                    $value
                );
            })
            ->filter()
            ->values();
    }

    public function getvar_statusAttribute()
    {
        $status = $this->var_status ? 'text-success' : 'text-danger';
        return "<span class='$status var-status-indicator' id='variant-{$this->var_id}-status' data-status='$this->var_status'><i class='fa fa-fw fa-circle'></i></span>";
    }

    public function getvar_skuAttribute()
    {
        $format = '<a id="row-%1$s" class="toolTipVarHoverEvent tooltipTableVar" data-variantId="%2$s" data-variantname="%3$s" data-hasqtip="1" aria-describedby="qtip-%4$s">%5$s</a>';

        if ($this->isMobile() || is_null(self::$tooltipTable)) {
            return sprintf(
                $format,
                $this->var_id,
                $this->var_id,
                $this->var_sku,
                $this->var_id,
                $this->var_sku,
            );
        }

        // add tooltip code here
        self::$tooltipTable->setTable(self::$type . 'Table' . $this->var_id, 'table-row-' . $this->var_id . ' ' . self::$type . 'Table' . $this->var_id, 'border:1px solid;');
        self::$tooltipTable->setColumns(self::$tooltipColumns);

        return sprintf(
            $format,
            $this->var_id,
            $this->var_id,
            $this->var_sku,
            $this->var_id,
            $this->var_sku,
        );
    }

    public function getvar_imageAttribute()
    {
        if (is_null($this->documentService())) {
            return '';
        }

        $varImage = '<img src="%s" width="60" height="60" class="rounded-circle img-fluid"/>';
        return sprintf($varImage, $this->documentService()->getDocDefaultImageFilePath('variant', $this->var_id));
    }

    public function getDT_RowDataAttribute()
    {
        return ['var_status', $this->var_status];
    }

    private function isMobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
    }

    private function documentService()
    {
        return self::$documenService;
    }
}
