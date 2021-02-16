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

class MelisCommerceOrderBasketProductAmountListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_get_item_price_end',
            function($e) {
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();

                // Price data from Event
                $price = $params['results'];

                // Extra data from params
                $data = $params['data'];

                if (empty($params['data']) 
                    || empty($price['price']))
                    return;

                if (!isset($data['basket']))
                    return;

                if (!empty($params['data']['basket'])) {

                    // Total amount
                    // Discount included
                    $basket = $params['data']['basket'];
                    $total = $price['price'] * $basket->getQuantity();
                    
                    // Set new price to result
                    $price['total_discount'] = 0;
                    // $price['sub_total_amount'] = $total;
                    $price['total_amount'] = $total;

                    // Set param from updated price
                    $e->setParam('results', $price);
                }
            },
            +999
        );

        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_get_item_price_end',
            function($e) {
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();

                // Price data from Event
                $price = $params['results'];

                // Extra data from params
                $data = $params['data'];

                if (empty($params['data']) 
                    || empty($price['price']))
                    return;

                if (!isset($data['basket']))
                    return;

                if (!empty($params['data']['basket'])) {

                    $basket = $params['data']['basket'];
                    $subTotal = $price['price_default'] * $basket->getQuantity();

                    // Discount/difference from sub total and total
                    $discount = $subTotal - $price['total_amount'];

                    if ($discount == 0)
                        $discount = 0;

                    // Set new price to result
                    $price['total_discount'] = $discount;

                    // Set param from updated price
                    $e->setParam('results', $price);
                }
            },
            -9999
        );
    }
}