<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\Event;

use MelisFront\Listener\MelisFrontSEODispatchRouterAbstractListener;

/**
 * This listener will activate when a page is deleted
 * 
 */
class MelisCommerceSEODispatchRouterCommerceUrlListener 
    extends MelisFrontSEODispatchRouterAbstractListener
{
    public $serviceManager;

    public $commerceRouteNames = array(
        'melis-front/melis_front_commerce_category' => array(
            'type' => 'category',
            'routeInternalParamName' => 'categoryId',
            'routeParamName' => 'cid',
            '301CheckingMethod' => 'is301Category',
        ),
        'melis-front/melis_front_commerce_product' => array(
            'type' => 'product',
            'routeInternalParamName' => 'productId',
            'routeParamName' => 'pid',
            '301CheckingMethod' => 'is301Product',
        ),
        'melis-front/melis_front_commerce_variant' => array(
            'type' => 'variant',
            'routeInternalParamName' => 'variantId',
            'routeParamName' => 'vid',
            '301CheckingMethod' => 'is301Variant',
        ),
    );
    
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
        	'melisfront_site_dispatch_ready', 
        	function($e){

    	        $this->serviceManager = $e->getTarget()->serviceManager;
    	        $router = $this->serviceManager->get('router');
                
        	    $params = $e->getParams();
        	   
        	    // Page inactive, nothing to do
        	    if ($params['301_type'] == 'seo301')
        	        return;
        	       
    	        if (!empty($params['categoryId']))
    	           $routeName = 'melis-front/melis_front_commerce_category';
    	        else
    	           if (!empty($params['productId']))
    	               $routeName = 'melis-front/melis_front_commerce_product';
    	           else
    	               if (!empty($params['variantId']))
    	                   $routeName = 'melis-front/melis_front_commerce_variant';
    	               else
    	                   $routeName = $params['front_route'];
    	       
    	       if (!empty($this->commerceRouteNames[$routeName]))
    	           $routeItemConf = $this->commerceRouteNames[$routeName];
    	       else
    	           return;
    	           
    	       $melisEcomSeoTable = $this->serviceManager->get('MelisEcomSeoTable');
    	       $seoObject = $melisEcomSeoTable->getSeoByTypeAndId($routeItemConf['routeInternalParamName'], $params[$routeItemConf['routeInternalParamName']]);
    	       if (!empty($seoObject)) {
    	           $seoObject = $seoObject->toArray();
    	           if (!empty($seoObject))
    	               $seoObject = $seoObject[0];
    	       }
        	       
        	   if ($params['front_route'] == 'melis-front-commerce-seo' && $params['301_type'] != 'seo301') {
        	       $params['301_type'] = '';
        	       $params['301'] = null;
        	       
        	       $params = $this->check301Commerce($params, $routeItemConf, $seoObject);
        	       if (!empty($params['301_type']) && $params['301_type'] == 'seoCommerce301')
        	           return;
        	       if (!empty($seoObject) && !empty($seoObject['eseo_url_redirect']))
        	       {
        	           $params['301_type'] = 'seoURL';
        	           $params['301'] = $seoObject['eseo_url_redirect'];
        	           return;
        	       }
        	   } else  {
            	   // add check if url is ok and redirected because of cid
            	       
            	   if (array_key_exists($params['front_route'], $this->commerceRouteNames)) {
            	       $params = $this->check301Commerce($params, $routeItemConf, $seoObject);
            	       if (!empty($params['301_type']) && $params['301_type'] == 'seoCommerce301')
            	           return;
            	       if (!empty($seoObject) && !empty($seoObject['eseo_url_redirect'])) {
            	           $params['301_type'] = 'seoURL';
            	           $params['301'] = $seoObject['eseo_url_redirect'];
            	           return;
            	       }
            	       
            	       $queryCommerceEndParams = '/' . $routeItemConf['routeParamName'] . '/' . $params[$routeItemConf['routeInternalParamName']];
            	       
            	       $uri = $router->getRequestUri();
            	       $uriTab = explode('?', $uri);
            	       $uri = $uriTab[0];
            	       $paramsGet = '';
            	       if (!empty($uriTab[1]))
            	           $paramsGet = '?' . $uriTab[1];
            	           
            	       if (!empty($seoObject) && !empty($seoObject['eseo_url'])) {
        	               if (substr($seoObject['eseo_url'], 0, 4) != 'http')
        	                   $newuri = '/' . $seoObject['eseo_url'];
        	               else
        	                   $newuri = $seoObject['eseo_url'];
        	                   				
        	               $params['301'] = $newuri . $paramsGet;
        	               $params['301_type'] = 'seoURL';
            	       } else {
            	           if ($params['301_type'] == 'seoMelisURL') {
            	               $uri301 = explode('?', $params['301']);
            	               $uri301 = $uri301[0];
            	                   
            	               if ($uri301 . $queryCommerceEndParams == $uri) {
            	                   // The redirection is caused by parameter added at the end but the url is ok
            	                   // Therefore don't do anything
            	                   $params['301'] = null;
            	                   $params['301_type'] = '';
            	               } else {
            	                   $melisComLinksService = $this->serviceManager->get('MelisComLinksService');

            	                   $pageLink  = $melisComLinksService->getPageLink($routeItemConf['type'], 
            	                                   $params[$routeItemConf['routeInternalParamName']], 
            	                                   $params['datasPage']->getMelisPageTree()->plang_lang_id, 
            	                                   true);
            	                   
            	                   $melisTree = $this->serviceManager->get('MelisEngineTree');
            	                   $host = $melisTree->getDomainByPageId($params['idpage']);
            	                   $router = $this->serviceManager->get('router');
            	                   $uri = $router->getRequestUri();
            	                   
            	                   if ($host != '')
            	                       $fullUrl = $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
            	                   else
            	                       $fullUrl = $uri->getPath();
    	                           
    	                           if ($fullUrl != $pageLink) {
    	                               $request = $this->serviceManager->get('request');;
    	                               if (!$request->isPost())
    	                               {
        	                               // URL is not ok, working but not the good one
        	                               // only if not post or we'll loose data
                                           $params['301'] = $pageLink . $paramsGet;
        	                               $params['301_type'] = 'seoMelisURL';
    	                               }
    	                           } else {
    	                               // URL is ok
            	                       $params['301'] = null;
            	                       $params['301_type'] = '';
    	                           }
            	               }
            	           }
            	       }
            	   }
               }

        	   // Setting all router datas
        	   if ($params['301'] == null && $params['404'] == null) {
        	       $params['pageLangId'] = $params['datasPage']->getMelisPageTree()->plang_lang_id;
        	       $params['pageLangLocale'] = $params['datasPage']->getMelisPageTree()->lang_cms_locale;
        	   
        	       if (!empty($params['datasPage']->getMelisTemplate())) {

            	       if ($params['datasPage']->getMelisTemplate()->tpl_type == 'ZF2') {
            	           $params['module'] = $params['datasPage']->getMelisTemplate()->tpl_zf2_website_folder;
            	           $params['controller'] = $params['datasPage']->getMelisTemplate()->tpl_zf2_website_folder . '\Controller\\' . $params['datasPage']->getMelisTemplate()->tpl_zf2_controller;
            	           $params['action'] = $params['datasPage']->getMelisTemplate()->tpl_zf2_action;
            	       } else
            	           if ($params['datasPage']->getMelisTemplate()->tpl_type == 'PHP') {
            	               $params['action'] = 'phprenderer';
            	               $params['renderType'] = 'melis_php';
            	           }
        	       }
        	   }
            },
        100
        );
    }
    
    public function check301Commerce($params, $routeItemConf, $seoObject)
    {
        if (!empty($seoObject) && !empty($seoObject['eseo_url_301'])) {
            $functionToCheck = $routeItemConf['301CheckingMethod'];
            $is301redirect = $this->$functionToCheck($params);
            if ($is301redirect) {
                $params['301_type'] = 'seoCommerce301';
                $params['301'] = $seoObject['eseo_url_301'];
            }
        }
        
        return $params;
    }
    
    public function is301Category($params)
    {
        $melisComCategoryService = $this->serviceManager->get('MelisComCategoryService');
        $categoryDatas = $melisComCategoryService->getCategoryById($params['categoryId'], false, true);
        
        if (!empty($categoryDatas)) {
            $categoryMainDatas = $categoryDatas->getCategory();
            
            if (!empty($categoryMainDatas)) {
                // add check dates start and end
                $startDate = $categoryMainDatas->cat_date_valid_start;
                $endDate = $categoryMainDatas->cat_date_valid_end;
                
                if (empty($startDate)) {
                    $startDate = '1990-01-01 00:00:00';
                }
                
                if (empty($endDate)) {
                    $endDate = '2038-01-01 00:00:00';
                }
                
                $sDate = new \DateTime($startDate);
                $sDate = $sDate->getTimestamp();
                $eDate = new \DateTime($endDate);
                $eDate = $eDate->getTimestamp();
                $nDate = time();
                
                if ($nDate < $eDate && $nDate >= $sDate) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    public function is301Product($params)
    {
        $melisComProductService = $this->serviceManager->get('MelisComProductService');
        $productDatas = $melisComProductService->getProductById($params['productId']);
        if (!empty($productDatas)) {
            $productMainDatas = $productDatas->getProduct();
            if (!empty($productMainDatas) && $productMainDatas->prd_status)
                return false;
        }
        
        return true;
    }
    
    public function is301Variant($params)
    {
        $melisComVariantService = $this->serviceManager->get('MelisComVariantService');
        $variantDatas = $melisComVariantService->getVariantById($params['variantId']);
        if (!empty($variantDatas)) {
            $variantMainDatas = $variantDatas->getVariant();
            if (!empty($variantMainDatas) && $variantMainDatas->var_status)
                return false;
        }
        
        return true;
    }
}