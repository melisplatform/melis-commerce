<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

/**
 * Tells the React page editor (melis-cms, /melis/react-api/cms-page/edition/...) apart from the legacy one,
 * so the adaptations it needs stay out of the legacy page edition.
 */
final class MelisCommercePageEditor
{
    const REACT_EDITOR_PATH = '/melis/react-api/cms-page/edition/';

    /** React editor endpoint listing the plugins that can be added to a page (its "+" palette) */
    const REACT_EDITOR_PALETTE_PATH = '/melis/react-api/cms-page/edition/plugins';

    public static function isReactRequest()
    {
        return strpos(self::requestPath(), self::REACT_EDITOR_PATH) === 0;
    }

    public static function isReactPaletteRequest()
    {
        return rtrim(self::requestPath(), '/') === self::REACT_EDITOR_PALETTE_PATH;
    }

    private static function requestPath()
    {
        return (string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    }
}
