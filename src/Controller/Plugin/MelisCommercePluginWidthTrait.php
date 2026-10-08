<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

use Laminas\View\Model\ViewModel;

/**
 * Applies the plugin width chosen in the page editor (width_desktop / width_tablet / width_mobile of the
 * plugin's XML) on the FRONT, like melis-front's tag plugin does: a plugin dropped in a drag & drop zone is
 * wrapped in a div carrying its plugin-width-* classes, which the page's plugin width CSS sizes.
 *
 * Done here once for every commerce plugin instead of in each template, so site templates overriding the
 * commerce ones (demo sites…) get it too. The back-office render already has this wrapper (plugin container),
 * and hardcoded template plugins have no editable width, so only the front render of zone plugins is wrapped.
 */
trait MelisCommercePluginWidthTrait
{
    public function render($updatesPluginConfig = array(), $generatePluginId = false, $forceRenderModeToFront = false)
    {
        $view = parent::render($updatesPluginConfig, $generatePluginId, $forceRenderModeToFront);

        $isFront = $this->renderMode == 'front' || $this->previewMode;
        if (!$isFront || !$this->fromDragDropZone || !($view instanceof ViewModel)) {
            return $view;
        }

        $wrapper = new ViewModel([
            'pluginHtml'   => $this->getServiceManager()->get('ViewRenderer')->render($view),
            'pluginId'     => $this->pluginFrontConfig['id'] ?? '',
            'widthClasses' => trim($view->widthDesktop . ' ' . $view->widthTablet . ' ' . $view->widthMobile),
        ]);
        $wrapper->setTemplate('MelisCommerce/plugin-width-container');

        return $wrapper;
    }
}
