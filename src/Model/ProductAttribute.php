<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use Illuminate\Database\Capsule\Manager as DB;

class ProductAttribute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_product_attribute';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'patt_id';

    public function product()
    {
        return $this->belongsTo('MelisCommerce\Model\Product', 'patt_product_id');
    }

    public function products()
    {
        return $this->belongsToMany('MelisCommerce\Model\Product', 'patt_product_id');
    }

    public function attribute()
    {
        return $this->belongsTo('MelisCommerce\Model\Attribute', 'patt_attribute_id');
    }

    public function scopeGetProductAttributes(
        $query,
        $productId
    ) {
        $query->with([
            'attribute.translations',
            'attribute.attributeValues' => function ($query) {
                $query->select([
                    'melis_ecom_attribute_value.*',
                    'melis_ecom_attribute_type.*'
                ]);

                $query->leftJoin(
                    'melis_ecom_attribute_type',
                    'melis_ecom_attribute_type.atype_id',
                    '=',
                    'melis_ecom_attribute_value.atval_type_id'
                );
            },
            'attribute.attributeValues.translations'
        ]);

        $query->where('patt_product_id', '=', $productId);

        return $query;
    }


    public function scopeGetProductAttributesThroughRawQuery($query, $productId, $langId)
    {
        try {
            return $query->select([
                'a.attr_id',
                'a.attr_reference',
                DB::raw('COALESCE(att.atrans_name, default_att.atrans_name) AS atrans_name'),
                'av.atval_id',
                'attt.atype_column_value',
                'avt.avt_v_int',
                'avt.avt_v_varchar',
                'avt.avt_v_text',
                'avt.avt_v_datetime'
            ])
                ->from('melis_ecom_product_attribute as pa')
                ->join('melis_ecom_attribute as a', 'pa.patt_attribute_id', '=', 'a.attr_id')
                ->leftJoin('melis_ecom_attribute_trans as att', function ($join) use ($langId) {
                    $join->on('a.attr_id', '=', 'att.atrans_attribute_id')
                        ->where('att.atrans_lang_id', '=', $langId);
                })
                ->leftJoin('melis_ecom_attribute_trans as default_att', function ($join) {
                    $join->on('a.attr_id', '=', 'default_att.atrans_attribute_id')
                        ->whereRaw('default_att.atrans_lang_id = (SELECT MIN(atrans_lang_id) FROM melis_ecom_attribute_trans WHERE atrans_attribute_id = a.attr_id)');
                })
                ->leftJoin('melis_ecom_attribute_value as av', 'a.attr_id', '=', 'av.atval_attribute_id')
                ->leftJoin('melis_ecom_attribute_type as attt', 'av.atval_type_id', '=', 'attt.atype_id')
                ->leftJoin('melis_ecom_attribute_value_trans as avt', 'av.atval_id', '=', 'avt.av_attribute_value_id')
                ->where('pa.patt_product_id', $productId)
                ->distinct();
        } catch (\Exception $e) {
            return $e->getTraceAsString();
        }
    }

    public function scopeGetProductAttributesSimplifiedThroughRawQuery(
        $query,
        $productId,
        $langId
    ) {
        try {
            $query->select([
                'a.*',
                DB::raw('COALESCE(att.atrans_name, default_att.atrans_name) AS atrans_name'),
                'pa.patt_attribute_id',
                'pa.patt_id',
            ])
                ->from('melis_ecom_product_attribute as pa')
                ->join('melis_ecom_attribute as a', 'pa.patt_attribute_id', '=', 'a.attr_id')
                ->leftJoin('melis_ecom_attribute_trans as att', function ($join) use ($langId) {
                    $join->on('a.attr_id', '=', 'att.atrans_attribute_id')
                        ->where('att.atrans_lang_id', '=', $langId);
                })
                ->leftJoin('melis_ecom_attribute_trans as default_att', function ($join) {
                    $join->on('a.attr_id', '=', 'default_att.atrans_attribute_id')
                        ->whereRaw('default_att.atrans_lang_id = (SELECT MIN(atrans_lang_id) FROM melis_ecom_attribute_trans WHERE atrans_attribute_id = a.attr_id)');
                })
                ->where('pa.patt_product_id', $productId);
            return $query;
        } catch (\Exception $e) {
            return $e->getTraceAsString();
        }
    }
}