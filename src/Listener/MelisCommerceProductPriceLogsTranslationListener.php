<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\Session\Container;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceProductPriceLogsTranslationListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_get_item_price_end',
            function($e){
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();

                if (empty($params['results']['price']))
                    return;

                $price = $params['results'];

                $translator = $sm->get('translator');

                $logs = $price['logs'];

                foreach($logs As $key => $log) {

                    if (is_array($log)) {

                        // If log data is array that contain values to be display/place on the translated text
                        // the log value should ONLY contain element with index of target translation and the value is array
                        foreach($log As $targetTrans => $values) {
                            if (strpos($targetTrans, 'tr_') !== false) {

                                $text = $translator->translate($targetTrans);
                                if (is_array($values)) {
                                    foreach($values As $vKey => $value) {
                                        $text = str_replace($vKey, $value, $text);
                                    }
                                } else {
                                    $text = sprintf($text, $value);
                                }

                                $logs[$key] = $text;

                                break;
                            }
                        }

                    } else {
                        $logWords = explode(' ', $log);
                        foreach($logWords As $lKey => $word) {
                            if (strpos($word, 'tr_') !== false)
                                $logWords[$lKey] = $translator->translate($word);
                        }

                        $logs[$key] = implode(' ', $logWords);
                    }

                    
                }

                $price['logs'] = $logs;

                $e->setParam('results', $price);
            },
            -9999
        );     
    }
}