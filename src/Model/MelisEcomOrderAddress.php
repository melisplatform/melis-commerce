<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model;

class MelisEcomOrderAddress
{
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}