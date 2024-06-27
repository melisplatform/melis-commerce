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
use Laminas\Mvc\MvcEvent;
use Laminas\Router\Http\Segment;

/**
 * This listener will activate when a page is deleted
 */
class MelisCommerceSEOReformatToRoutePageUrlListener 
    implements ListenerAggregateInterface
{

    public $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $callBackHandler = $events->attach(
        	MvcEvent::EVENT_ROUTE, 
        	function(MvcEvent $e){
        		$sm = $e->getApplication()->getServiceManager();
        		$router = $e->getRouter();

        		$routeMatch = $e->getRouteMatch();
        		
        		$uri = $_SERVER['REQUEST_URI'];
        		preg_match('/.*\.((?!php).)+(?:\?.*|)$/i', $uri, $matches, PREG_OFFSET_CAPTURE);
        		if (count($matches) > 1)
        		    return;
        		
        		if (substr($uri, 0, 1) == '/')
        		    $uri = substr($uri, 1, strlen($uri));
        		
        		$request = $e->getRequest();
        		$getString = $_SERVER['QUERY_STRING'];
        		if ($getString != '')
        		  $getString = '?' . $getString;
        		
        		$uri = str_replace($getString, '', $uri);

    		    // The SEO URLS will be effective only
    		    $domain = $_SERVER['SERVER_NAME'];
    		    $melisTableDomain = $sm->get('MelisEngineTableSiteDomain');
    		    $datasDomain = $melisTableDomain->getEntryByField('sdom_domain', $domain);
    		    if (empty($datasDomain) || empty($datasDomain->current()) || empty($uri)) {
    		        // We are not on a front site, then we don't use SEO URLS (also to
    		        // avoid collision with BO modules rules)
    		        return;
    		    }
    		     
    		    // Removing the optional parameters from url before checking
    		    $params = '';
    		    $parameters = explode('/', $uri);
    		    if (count($parameters) > 1) {
    		        for ($i = 1; $i < count($parameters); $i++)
    		            $params .= '/' . $parameters[$i];
    		            $uri = str_replace($params, '', $uri);
    		    }

    		    $melisEcomSeoTable = $sm->get('MelisEcomSeoTable');
    		    $datasComSeo = $melisEcomSeoTable->getEntryByField('eseo_url', $uri);
    		    if (!empty($datasComSeo)) {
    		        
    		        $datasComSeo = $datasComSeo->current(); 
    		            
    		        if (!empty($datasComSeo)) {
    		            $router = $e->getApplication()->getServiceManager()->get('router');
    		             
    		            $defaults = array(
    		                    'controller' => 'MelisFront\Controller\Index',
    		                    'action' => 'index',
    		                    'idpage' => $datasComSeo->eseo_page_id,
    		                    'renderType' => 'melis_zf2_mvc',
    		                    'renderMode' => 'front',
    		                    'preview' => false,
    		                    'urlparams' => $params,
    		            );

    		            if (!empty($datasComSeo->eseo_category_id)) {
    		                $defaults['categoryId'] = $datasComSeo->eseo_category_id;
    		                $typeLink = 'category';
    		                $id = $datasComSeo->eseo_category_id;
    		            }

    		            if (!empty($datasComSeo->eseo_product_id)) {
    		                $defaults['productId'] = $datasComSeo->eseo_product_id;
    		                $typeLink = 'product';
    		                $id = $datasComSeo->eseo_product_id;
    		            }

    		            if (!empty($datasComSeo->eseo_variant_id)) {
    		                $defaults['variantId'] = $datasComSeo->eseo_variant_id;
    		                $typeLink = 'variant';
    		                $id = $datasComSeo->eseo_variant_id;
    		            }

    		            if (empty($datasComSeo->eseo_page_id)) {
    		              $melisComLinksService = $sm->get('MelisComLinksService');
    		              $pageId = $melisComLinksService->getPageIdAssociated($typeLink, $id, $datasComSeo->eseo_lang_id);
    		              $defaults['idpage'] = $pageId;
    		              
    		            }
    		                
    		            $route = Segment::factory(array(
    		                'route' => '/' . $uri,
    		                'defaults' => $defaults
    		            ));
    		
    		            // add it to the router
    		            $router->addRoute('melis-front-commerce-seo', $route);
    		        }
    		    }
        	},
        70);
        
        $this->listeners[] = $callBackHandler;
    }
    
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}