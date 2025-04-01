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

class MelisCommerceGdprUserDeleteListener extends MelisGeneralListener
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            [
                'melis_core_gdpr_user_delete_event',
            ],
            function ($e) {
                $moduleName = $this->getModuleName($this);
                $parameters = $e->getParams();

                if (isset($parameters['selected']) && isset($parameters['selected'][$moduleName])) {
                    $ids = $parameters['selected'][$moduleName];
                    // $newsletterUsersTable = $e->getTarget()->getServiceManager()->get('MelisNewsletterSubscribersTable');

                    // try {
                    //     $countOfDeletedSubscriber = $newsletterUsersTable->deleteByField('nlu_id', $ids);
                    //     $success = 1;
                    //     $message = 'tr_melis_newsletter_delete_ok';
                    // } catch (\Exception $ex) {
                    $success = 0;
                    // TODO
                    $message = 'contact deletion not supported';
                    // }

                    // foreach ($ids as $id) {
                    //     $parameters['log'][$moduleName][$id] = [
                    //         'event' => 'melis_newsletter_flash_messenger',
                    //         'success' => $success,
                    //         'title' => 'tr_melis_newsletter_title',
                    //         'message' => $message,
                    //         'typeCode' => 'NEWSLETTER_DELETE',
                    //         'itemId' => $id
                    //     ];
                    // }

                    // $noErrors = ($countOfDeletedSubscriber == count($ids)) ? true : false;
                    $parameters['results'][$moduleName] = false;
                }
            }
        );
    }

    /**
     * This will get the module name of the class
     * @param Class
     * @return String = module name
     */
    public function getModuleName($class)
    {
        $controllerClass = get_class($this);
        $moduleName = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        return $moduleName;
    }
}
