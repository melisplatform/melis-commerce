<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;

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

    public function translations()
    {
        return $this->hasMany('MelisCommerce\Model\AttributeTranslation', 'atrans_attribute_id');
    }
}
