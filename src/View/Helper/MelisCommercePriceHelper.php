<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\View\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * One money format for the commerce front plugins (Mantis #0011160): currency symbol stuck to the
 * amount with 2 decimals — "€10.00" — the format the cart/checkout totals already used, while unit
 * prices printed "€ 10" / "€10" and RelatedProducts the raw DB decimal. No amount → "N/A".
 *
 * Usage in a view: $this->MelisCommercePrice($amount, $currencySymbol)
 */
class MelisCommercePriceHelper extends AbstractHelper
{
    public function __invoke($amount, $currencySymbol = '', string $noPrice = 'N/A'): string
    {
        if ($amount === null || $amount === '' || !is_numeric($amount)) {
            return $this->getView()->escapeHtml($noPrice);
        }
        return $this->getView()->escapeHtml((string) $currencySymbol . number_format((float) $amount, 2));
    }
}
