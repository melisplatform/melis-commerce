<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\ArrayUtils;
use Zend\Session\Container;
use Zend\ModuleManager\ModuleEvent;

use MelisCommerce\Listener\MelisCommerceCategoryListener;
use MelisCommerce\Listener\MelisCommerceFlashMessengerListener;
use MelisCommerce\Listener\MelisCommerceSaveProductListener;
use MelisCommerce\Listener\MelisCommerceValidateVariantListener;
use MelisCommerce\Listener\MelisCommerceSEODispatchRouterCommerceUrlListener;
use MelisCommerce\Listener\MelisCommerceSEOReformatToRoutePageUrlListener;
use MelisCommerce\Listener\MelisCommerceSEOMetaPageListener;
use MelisCommerce\Listener\MelisCommerceSaveOrderListener;
use MelisCommerce\Listener\MelisCommerceSaveClientListener;
use MelisCommerce\Listener\MelisCommerceShipmentCostListener;
use MelisCommerce\Listener\MelisCommerceSaveAttributeListener;
use MelisCommerce\Listener\MelisCommercePostPaymentListener;
use MelisCommerce\Listener\MelisCommerceProductPriceCountryDeletedListener;
use MelisCommerce\Listener\MelisCommerceProductStockCountryDeletedListener;
use MelisCommerce\Listener\MelisCommerceCategoryCountryLinkCountryDeletedListener;
use MelisCommerce\Listener\MelisCommerceAttributeLanguageDeletedListener;
use MelisCommerce\Listener\MelisCommerceCategoryLanguageDeletedListener;
use MelisCommerce\Listener\MelisCommerceSEOLanguageDeletedListener;
use MelisCommerce\Listener\MelisCommerceProductTextLanguageAddListener;
use MelisCommerce\Listener\MelisCommerceProductTextLanguageDeleteListener;
use MelisCommerce\Listener\MelisCommerceDocumentCountryDeletedListener;
use MelisCommerce\Listener\MelisCommerceCheckoutCouponListener;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $this->createTranslations($e);
        $container = new Container('meliscommerce');
        $container['documents'] = array('docRelationType' => '', 'docRelationId' => 0);
        // Determines if Melis is loaded or Front is loaded
        $melisRoute = false;
        $sm = $e->getApplication()->getServiceManager();
        $routeMatch = $sm->get('router')->match($sm->get('request'));
        
       
        
        
        if (!empty($routeMatch))
        {
            $routeName = $routeMatch->getMatchedRouteName();
            
            $module = explode('/', $routeName);
             
            if (!empty($module[0]))
            {
                if ($module[0] == 'melis-backoffice')
                    $melisRoute = true;
            }
        }
        
        if ($melisRoute)
        {
            // attach listeners for Melis
            $eventManager->attach(new MelisCommerceCategoryListener());
            $eventManager->attach(new MelisCommerceFlashMessengerListener());
            $eventManager->attach(new MelisCommerceSaveProductListener());
            $eventManager->attach(new MelisCommerceValidateVariantListener());
            $eventManager->attach(new MelisCommerceSaveOrderListener());
            $eventManager->attach(new MelisCommerceSaveClientListener());
            $eventManager->attach(new MelisCommerceShipmentCostListener());
            $eventManager->attach(new MelisCommerceSaveAttributeListener());
            $eventManager->attach(new MelisCommercePostPaymentListener());
            $eventManager->attach(new MelisCommerceProductPriceCountryDeletedListener());
            $eventManager->attach(new MelisCommerceProductStockCountryDeletedListener());
            $eventManager->attach(new MelisCommerceCategoryCountryLinkCountryDeletedListener());
            $eventManager->attach(new MelisCommerceAttributeLanguageDeletedListener());
            $eventManager->attach(new MelisCommerceCategoryLanguageDeletedListener());
            $eventManager->attach(new MelisCommerceSEOLanguageDeletedListener());
            $eventManager->attach(new MelisCommerceProductTextLanguageAddListener());
            $eventManager->attach(new MelisCommerceProductTextLanguageDeleteListener());
            $eventManager->attach(new MelisCommerceDocumentCountryDeletedListener());
            $eventManager->attach(new MelisCommerceCheckoutCouponListener());
            
        }
        else
        {
            // attach listeners for Front
            $eventManager->attach($sm->get('MelisCommerce\Listener\MelisCommerceSEOReformatToRoutePageUrlListener'));
            $eventManager->attach($sm->get('MelisCommerce\Listener\MelisCommerceSEODispatchRouterCommerceUrlListener'));
        
            // Adding Commerce SEO meta datas to page
            $eventManager->attach(new MelisCommerceSEOMetaPageListener());
            
            // Init session for services that uses session (checkout)
            $container = new Container('meliscommerce');
        }
        
    }
    
    public function init(ModuleManager $manager)
    {
        $events = $manager->getEventManager();
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'updateRoutesFrontBack'));
    }
    
    public function updateRoutesFrontBack(ModuleEvent $e)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri1 = '';
        $tabUri = explode('/', $uri);
        if (!empty($tabUri[1]))
            $uri1 = $tabUri[1];
        
        if ($uri1 != 'melis')
        {
            // No need of BO routes if we're in front
            $configListener = $e->getConfigListener();
            $config         = $configListener->getMergedConfig(false);
            
            unset($config['router']['routes']['melis-backoffice']);
            
            // Pass the changed configuration back to the listener:
            $configListener->setMergedConfig($config);
        }
        
    }

    public function getConfig()
    {
    	$config = array();
    	$configFiles = array(
    			include __DIR__ . '/../config/module.config.php',
                include __DIR__ . '/../config/diagnostic.config.php',

    			include __DIR__ . '/../config/interface/app.interface.general.php',
    	        include __DIR__ . '/../config/interface/app.interface.documents.php',
    			include __DIR__ . '/../config/interface/app.interface.categories.php',
    			include __DIR__ . '/../config/interface/app.interface.products.php',
    			include __DIR__ . '/../config/interface/app.interface.variants.php',
    			include __DIR__ . '/../config/interface/app.interface.clients.php',
    	        include __DIR__ . '/../config/interface/app.interface.orders.php',
    	        include __DIR__ . '/../config/interface/app.interface.prices.php',
    	        include __DIR__ . '/../config/interface/app.interface.seo.php',
	            include __DIR__ . '/../config/interface/app.interface.coupons.php',
	            include __DIR__ . '/../config/interface/app.interface.checkout.php',
    	        include __DIR__ . '/../config/interface/app.interface.currency.php',
    	        include __DIR__ . '/../config/interface/app.interface.attributes.php',
    	        include __DIR__ . '/../config/interface/app.interface.language.php',
    	        include __DIR__ . '/../config/interface/app.interface.country.php',
                include __DIR__ . '/../config/interface/app.interface.assoc-var.php',
    	    
    			include __DIR__ . '/../config/forms/app.forms.general.php',
    	        include __DIR__ . '/../config/forms/app.forms.documents.php',
    			include __DIR__ . '/../config/forms/app.forms.categories.php',
    			include __DIR__ . '/../config/forms/app.forms.products.php',
    			include __DIR__ . '/../config/forms/app.forms.variants.php',
    			include __DIR__ . '/../config/forms/app.forms.clients.php',
    	        include __DIR__ . '/../config/forms/app.forms.orders.php',
    	        include __DIR__ . '/../config/forms/app.forms.prices.php',
    	        include __DIR__ . '/../config/forms/app.forms.seo.php',
    	        include __DIR__ . '/../config/forms/app.forms.coupons.php',
    	        include __DIR__ . '/../config/forms/app.forms.checkout.php',
    	        include __DIR__ . '/../config/forms/app.forms.currency.php',
    	        include __DIR__ . '/../config/forms/app.forms.attributes.php',
    	    
    			include __DIR__ . '/../config/tools/app.tools.general.php',
    	        include __DIR__ . '/../config/tools/app.tools.documents.php',
    			include __DIR__ . '/../config/tools/app.tools.categories.php',
    			include __DIR__ . '/../config/tools/app.tools.products.php',
    			include __DIR__ . '/../config/tools/app.tools.variants.php',
    			include __DIR__ . '/../config/tools/app.tools.clients.php',
    	        include __DIR__ . '/../config/tools/app.tools.orders.php',
    	        include __DIR__ . '/../config/tools/app.tools.prices.php',
    	        include __DIR__ . '/../config/tools/app.tools.coupons.php',
    	        include __DIR__ . '/../config/tools/app.tools.checkout.php',

    	        include __DIR__ . '/../config/tools/app.tools.currency.php',
    	        include __DIR__ . '/../config/tools/app.tools.attributes.php',
    	        include __DIR__ . '/../config/tools/app.tools.language.php',
    	        include __DIR__ . '/../config/tools/app.tools.country.php',
    	);
    	
    	foreach ($configFiles as $file) {
    		$config = ArrayUtils::merge($config, $file);
    	} 
    	
    	return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function createTranslations($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $translator = $sm->get('translator');

        // Get the locale used from meliscore session
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        if(!empty($locale)) 
        {
            // Load files
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/general/' . $locale . '.interface.general.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/categories/' . $locale . '.interface.categories.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/products/' . $locale . '.interface.products.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/variants/' . $locale . '.interface.variants.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/documents/' . $locale . '.interface.documents.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/clients/' . $locale . '.interface.clients.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/orders/' . $locale . '.interface.orders.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/prices/' . $locale . '.interface.prices.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/seo/' . $locale . '.interface.seo.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/coupons/' . $locale . '.interface.coupons.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/checkout/' . $locale . '.interface.checkout.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/currency/' . $locale . '.interface.currency.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/attributes/' . $locale . '.interface.attributes.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/language/' . $locale . '.interface.language.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/country/' . $locale . '.interface.country.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/assoc-var/' . $locale . '.interface.assoc-var.php');
    
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/general/' . $locale . '.forms.general.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/categories/' . $locale . '.forms.categories.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/products/' . $locale . '.forms.products.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/variants/' . $locale . '.forms.variants.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/documents/' . $locale . '.forms.documents.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/clients/' . $locale . '.forms.clients.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/orders/' . $locale . '.forms.orders.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/prices/' . $locale . '.forms.prices.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/seo/' . $locale . '.forms.seo.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/coupons/' . $locale . '.forms.coupons.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/checkout/' . $locale . '.forms.checkout.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/currency/' . $locale . '.forms.currency.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/attributes/' . $locale . '.forms.attributes.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/language/' . $locale . '.forms.language.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/country/' . $locale . '.forms.country.php');
            $translator->addTranslationFile('phparray', __DIR__ . '/../language/assoc-var/' . $locale . '.interface.assoc-var.php');
        }
    }

}
