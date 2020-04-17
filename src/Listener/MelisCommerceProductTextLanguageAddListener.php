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

use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceProductTextLanguageAddListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_language_save_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
        		$params = $e->getParams();

        		$langId = (int) $params['langId'];
        		$prodTextTable = $sm->get('MelisEcomProductTextTable');
        		$prodTable = $sm->get('MelisEcomProductTable');
        		$productSvc = $sm->get('MelisComProductService');
        		$prodData = $prodTable->fetchAll()->toArray();
        		foreach($prodData as $index => $product) {

        		    $prodTextLang = $prodTextTable->getProductTextLangId((int) $product['prd_id'])->current();

        		    if($prodTextLang && $prodTextLang->ptxt_lang_id) {

        		        $prodTextData = $prodTextTable->getProductTextsByProductId((int) $product['prd_id'], (int) $prodTextLang->ptxt_lang_id)->toArray();

        		        if($prodTextData) {

        		            $copiedData = [];
        		            foreach($prodTextData as $key => $value) {

        		                unset($value['ptxt_id']);
        		                $value['ptxt_lang_id'] = $langId;
        		                $value['ptxt_field_short'] = null;
        		                $value['ptxt_field_long'] = null;
        		                $copiedData[$key] = $value;
        		            }

        		            foreach($copiedData as $productText) {
        		                $prodTextId = isset($productText['ptxt_id']) ? $productText['ptxt_id'] : null;
        		                $productSvc->saveProductTexts($productText, $prodTextId);
        		            }
        		        }
        		    }
        		}
        	},
        -1000
        );
    }
}