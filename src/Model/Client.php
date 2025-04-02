<?php

namespace MelisCommerce\Model;

use MelisCommerce\Model\Model;
use MelisCommerce\Model\Product;
use MelisCommerce\Model\Variant;

class Client extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_client';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cli_id';

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'cper_client_id', 'cli_id');
    }
}
