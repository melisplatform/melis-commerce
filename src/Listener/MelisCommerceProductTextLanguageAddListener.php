<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

use MelisCore\Listener\MelisCoreGeneralListener;

class MelisCommerceProductTextLanguageAddListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_language_save_end'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
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
        		            $copiedData = array();
        		            foreach($prodTextData as $key => $value) {
        		                unset($value['ptxt_id']);
        		                $value['ptxt_lang_id'] = $langId;
        		                $value['ptxt_field_short'] = null;
        		                $value['ptxt_field_long'] = null;
        		                $copiedData[$key] = $value;
        		            }
        		            foreach($copiedData as $productText)
        		            {
        		                $prodTextId = isset($productText['ptxt_id']) ? $productText['ptxt_id'] : null;
        		                $productSvc->saveProductTexts($productText, $prodTextId);
        		            }
        		        }
        		    }
        		}
        		
        	},
        	
        -1000);
        
        $this->listeners[] = $callBackHandler;
    }
}