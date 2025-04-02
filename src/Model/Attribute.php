<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Attribute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_attribute';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'attr_id';

    protected $appends = [
        'DT_RowId',
    ];

    public function translations()
    {
        return $this->hasMany('MelisCommerce\Model\AttributeTranslation', 'atrans_attribute_id');
    }

    public function variantAttributes()
    {
        return $this->hasMany('MelisCommerce\Model\VariantAttribute', 'vatv_attribute_value_id');
    }

    public function attributeValues()
    {
        return $this->hasMany('MelisCommerce\Model\AttributeValue', 'atval_attribute_id');
    }

    public function getDT_RowIdAttribute()
    {
        return $this->attr_id;
    }

    public function scopeGetAttributesList(
        \Illuminate\Database\Eloquent\Builder $query,
        $langId = null,
        $status = null,
        $visible = null,
        $searchable = null,
        $start = null,
        $limit = null,
        $order = 'ASC',
        $search = null,
        $forPagination = false,
        $orderBy = 'attr_id'
    ) {
        try {
            $query->select([
                'a.*',
                DB::raw('COALESCE(att.atrans_name, default_att.atrans_name) AS atrans_name'),
                'attt.*'
            ])
                ->from('melis_ecom_attribute as a') // Alias the main table as 'pa'
                ->leftJoin('melis_ecom_attribute_trans as att', function ($join) use ($langId) {
                    $join->on('a.attr_id', '=', 'att.atrans_attribute_id')
                        ->where('att.atrans_lang_id', '=', $langId);
                })
                ->leftJoin('melis_ecom_attribute_trans as default_att', function ($join) {
                    $join->on('a.attr_id', '=', 'default_att.atrans_attribute_id')
                        ->whereRaw('default_att.atrans_lang_id = (SELECT MIN(atrans_lang_id) FROM melis_ecom_attribute_trans WHERE atrans_attribute_id = a.attr_id)');
                })
                ->leftJoin('melis_ecom_attribute_type as attt', 'attt.atype_id', '=', 'a.attr_type_id');


            if (!empty($search)) {
                $search = '%' . $search . '%';
                $query->where('a.attr_id', 'LIKE', $search)
                    ->orWhere('a.attr_reference', 'LIKE', $search)
                    ->orWhere('att.atrans_name', 'LIKE', $search)
                    ->orWhere('attt.atype_name', 'LIKE', $search);
            }

            if (!is_null($status)) {
                $query->where('a.attr_status', $status);
            }

            if (!is_null($visible)) {
                $query->where('a.attr_visible', $visible);
            }

            if (!is_null($searchable)) {
                $query->where('a.attr_searchable', $searchable);
            }

            if ((!empty($limit) && $limit > 0) && !$forPagination) {
                $query->limit($limit);
            }

            if ((!empty($start) && $start > 0) && !$forPagination) {
                $query->offset($start);
            }

            $query->orderBy($orderBy, $order);

            return $query;
        } catch (\Exception $e) {
            return $e->getTraceAsString();
        }
    }

    public function getattr_statusAttribute()
    {
        $status = $this->attr_status ? 'text-success' : 'text-danger';
        return "<span class='$status'><i class='fa fa-fw fa-circle'></i></span>";
    }

    public function getattr_visibleAttribute()
    {
        $checked = '<span class="text-danger"><i class="fa fa-check"></i></span>';
        return $this->attr_visible ? $checked : '';
    }

    public function getattr_searchableAttribute()
    {
        $checked = '<span class="text-danger"><i class="fa fa-check"></i></span>';
        return $this->attr_searchable ? $checked : '';
    }
}
