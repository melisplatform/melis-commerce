<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

use Laminas\Session\Container;

/**
 * Front logout: MelisComAuthenticationService::logout() existed but no plugin called it, so a customer could not end
 * the session — a problem on a shared computer (Mantis #0011208). A "?m_logout=1" link on the Login and Account
 * plugins logs the customer out, clears the checkout data of the session and reloads the page without the parameter.
 */
trait MelisCommercePluginLogoutTrait
{
    /** Handles "?m_logout=1" on the front (never in the BO page edition). */
    protected function handleFrontLogout(): void
    {
        if ($this->renderMode === 'melis') {
            return;
        }
        $request = $this->getServiceManager()->get('request');
        if (!method_exists($request, 'getQuery') || !$request->getQuery('m_logout')) {
            return;
        }
        $auth = $this->getServiceManager()->get('MelisComAuthenticationService');
        if ($auth->hasIdentity()) {
            $auth->logout();
        }
        $container = new Container('meliscommerce');
        unset($container['checkout']); // addresses, coupons, pending order of the previous customer

        $query = $request->getQuery()->toArray();
        unset($query['m_logout']);
        $url = $request->getUri()->getPath() . (!empty($query) ? '?' . http_build_query($query) : '');
        $this->getController()->redirect()->toUrl($url);
    }

    /** The logout link of the current page. */
    protected function logoutLink(): string
    {
        return '?' . http_build_query(['m_logout' => 1]);
    }

    /** Name shown for the logged-in customer ("First Last", else the e-mail), or ''. */
    protected function loggedInName(): string
    {
        $auth = $this->getServiceManager()->get('MelisComAuthenticationService');
        if (!$auth->hasIdentity()) {
            return '';
        }
        $identity = $auth->getIdentity();
        $name = trim(($identity->cper_firstname ?? '') . ' ' . ($identity->cper_name ?? ''));
        return $name !== '' ? $name : (string) ($identity->cper_email ?? '');
    }
}
