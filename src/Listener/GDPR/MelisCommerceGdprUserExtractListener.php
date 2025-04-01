<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener\GDPR;

use Laminas\EventManager\EventManagerInterface;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceGdprUserExtractListener extends MelisGeneralListener
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'melis_core_gdpr_user_extract_event',
            function ($e) {
                $parameters = $e->getParams();
                $moduleName = 'MelisCommerce';

                if (isset($parameters['selected'][$moduleName])) {
                    $melisCoreConfig = $e->getTarget()->getServiceManager()->get('config');
                    $personTable = $e->getTarget()->getServiceManager()->get('MelisEcomClientPersonTable');

                    $columns = $melisCoreConfig['plugins'][$moduleName]['gdpr']['extract']['columns'];

                    $xmlDoc = new \DOMDocument();
                    $xmlDoc->formatOutput = true;

                    $root = $xmlDoc->appendChild($xmlDoc->createElement('xml'));
                    $module = $root->appendChild($xmlDoc->createElement($moduleName));

                    $tblGw = $personTable->getTableGateway();
                    $select = $tblGw->getSql()->select();
                    $select->where->in('cper_id', $parameters['selected'][$moduleName]);
                    $result = $tblGw->selectWith($select);

                    $contacts = $result->toArray();

                    foreach ($contacts as $contact) {
                        $moduleId = $module->appendChild($xmlDoc->createElement("contact_" . $contact['cper_id']));
                        foreach ($contact as $contactColumn => $prospectValue) {
                            if (isset($columns[$contactColumn])) {
                                $newKey = $columns[$contactColumn]['text'];

                                $moduleId->appendChild($xmlDoc->createElement(str_replace(' ', '_', $newKey), (string)$prospectValue));
                            }
                        }
                    }

                    $parameters['results'][$moduleName] = $xmlDoc->saveXML();
                }
            }
        );
    }
}
