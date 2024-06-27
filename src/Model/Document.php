<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_document';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'doc_id';
}
