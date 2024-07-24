<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;
use MelisCommerce\Model\Category;

class OrderStatusTranslation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_order_status_trans';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ostt_id';

    public static function getOrderTranslation($statusId, $langId)
    {
        $trans = static::query()->where('ostt_status_id', $statusId)
                        ->where('ostt_lang_id', $langId)
                        ->limit(1)
                        ->get();

        if (!$trans->count()) {
            $trans = static::query()->where('ostt_status_id', $statusId)
                        ->orderBy('ostt_lang_id')
                        ->limit(1)
                        ->get();
        }

        return ($trans->count()) ? $trans->first() : null;
    }
}
