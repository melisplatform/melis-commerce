<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener\GDPR;

use MelisCommerce\Service\MelisCommerceGdprAutoDeleteService;
use MelisCore\Service\MelisCoreGdprAutoDeleteService as Gdpr;
use Laminas\EventManager\EventManagerInterface;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceGdprAutoDeleteGetEmailListener extends MelisGeneralListener
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'melis_core_gdpr_auto_delete_log_get_user_email',
            function($e){
                $params = $e->getParams();
                if ($params['module'] == MelisCommerceGdprAutoDeleteService::MODULE_NAME) {
                    // get service manager
                    $sm = $e->getTarget()->getServiceManager();
                    // melis prospects gdpr service
                    $userData = $sm->get('MelisCommerceGdprAutoDeleteService')->getUserById($params['id']);
                    $result['module'] = $params['module'];
                    if (! empty($userData)) {
                        if (!$userData->cper_anonymized) {
                            $result['email'] = $userData->cper_email;
                        }
                    }

                    return $result;
                }  
            },
            -1000
        );
    }
}
