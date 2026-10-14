<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

/**
 * Account-area plugins (Account, Profile, addresses, order history / detail) for anonymous visitors
 * (Mantis #0011157): they rendered EMPTY EDITABLE forms ("Save", password fields…) to visitors who
 * aren't logged in. Now, on the front: redirect to the plugin's login page (m_login_page_link) when one
 * is set — coming back to this page after login — otherwise show the "please login" message instead.
 * Never in the BO page edition (renderMode "melis"), where the form must stay editable.
 */
trait MelisCommercePluginLoginGuardTrait
{
    /**
     * @return array|null null when the plugin may render normally; otherwise the view variables
     *                    ['notLoggedIn' => true, 'loginMessage' => …] to return as is.
     */
    protected function anonymousGuard(): ?array
    {
        if ($this->renderMode === 'melis') {
            return null;
        }
        $auth = $this->getServiceManager()->get('MelisComAuthenticationService');
        if ($auth->hasIdentity()) {
            return null;
        }

        $request = $this->getController()->getRequest();
        $loginUrl = $this->loginPageUrl((string) ($this->pluginFrontConfig['m_login_page_link'] ?? ''));
        if ($loginUrl !== '' && !$request->isXmlHttpRequest()) {
            $back = (string) $request->getUriString();
            $sep = strpos($loginUrl, '?') === false ? '?' : '&';
            $this->getController()->redirect()->toUrl($loginUrl . $sep . http_build_query(['m_redirection_link_ok' => $back]));
        }

        return [
            'notLoggedIn'  => true,
            'loginMessage' => $this->getServiceManager()->get('translator')->translate('tr_meliscommerce_client_Checkout_no_identity'),
        ];
    }

    /** A page id (React/legacy page picker) or an internal/external URL → URL ('' when unset/unknown). */
    protected function loginPageUrl(string $link): string
    {
        $link = trim($link);
        if ($link === '') {
            return '';
        }
        if (ctype_digit($link)) {
            try {
                return (string) $this->getServiceManager()->get('MelisEngineTree')->getPageLink((int) $link, true);
            } catch (\Throwable $e) {
                return '';
            }
        }
        return $link;
    }

    /** m_login_page_link from this plugin's XML node (loadDbXmlToPluginConfig). */
    protected function loginPageLinkFromXml($xml): array
    {
        return ($xml && isset($xml->m_login_page_link)) ? ['m_login_page_link' => (string) $xml->m_login_page_link] : [];
    }

    /** m_login_page_link as XML for savePluginConfigToXml ('' when unset). */
    protected function loginPageLinkToXml(array $parameters): string
    {
        return !empty($parameters['m_login_page_link'])
            ? "\t\t" . '<m_login_page_link><![CDATA[' . $parameters['m_login_page_link'] . ']]></m_login_page_link>'
            : '';
    }
}
