<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;

class Language extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_lang';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'elang_id';
}
