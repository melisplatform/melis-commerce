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
use MelisCommerce\Listener\MelisCommercePostCheckoutCouponListener;
use MelisCommerce\Listener\MelisCommercePrdVarDuplicationListener;
use MelisCommerce\Listener\MelisCommerceVariantCheckLowStockListener;
use MelisCommerce\Listener\MelisCommerceVariantRestockListener;
use MelisCommerce\Listener\MelisCommerceSaveProductStockEmailAlertListener;
/**
 * Class Module
 * @package MelisCmsNews
 * @require melis-core
 */
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
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
            
            $this->createTranslations($e, $module);
             
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
            $eventManager->attach(new MelisCommerceSaveAttributeListener());
            $eventManager->attach(new MelisCommerceProductPriceCountryDeletedListener());
            $eventManager->attach(new MelisCommerceProductStockCountryDeletedListener());
            $eventManager->attach(new MelisCommerceCategoryCountryLinkCountryDeletedListener());
            $eventManager->attach(new MelisCommerceAttributeLanguageDeletedListener());
            $eventManager->attach(new MelisCommerceCategoryLanguageDeletedListener());
            $eventManager->attach(new MelisCommerceSEOLanguageDeletedListener());
            $eventManager->attach(new MelisCommerceProductTextLanguageAddListener());
            $eventManager->attach(new MelisCommerceProductTextLanguageDeleteListener());
            $eventManager->attach(new MelisCommerceDocumentCountryDeletedListener());
            $eventManager->attach(new MelisCommercePrdVarDuplicationListener());
            $eventManager->attach(new MelisCommerceSaveProductStockEmailAlertListener());
            
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
        
        $eventManager->attach(new MelisCommerceShipmentCostListener());
        $eventManager->attach(new MelisCommerceCheckoutCouponListener());
        $eventManager->attach(new MelisCommercePostCheckoutCouponListener());
        $eventManager->attach(new MelisCommercePostPaymentListener());
        $eventManager->attach(new MelisCommerceVariantCheckLowStockListener());
        $eventManager->attach(new MelisCommerceVariantRestockListener());
        
    }
    
    public function init(ModuleManager $manager)
    {
        $events = $manager->getEventManager();
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'updateRoutesFrontBack'));
    }
    
    public function updateRoutesFrontBack(ModuleEvent $e)
    {
        if(!empty($_SERVER['REQUEST_URI'])){
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
        
    }

    public function getConfig()
    {
    	$config = array();
    	$configFiles = array(
			include __DIR__ . '/../config/module.config.php',
			include __DIR__ . '/../config/app.emails.php',
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
            include __DIR__ . '/../config/interface/app.interface.duplications.php',
            include __DIR__ . '/../config/interface/app.interface.settings.php',
	    
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
	        include __DIR__ . '/../config/forms/app.forms.duplications.php',
	        include __DIR__ . '/../config/forms/app.forms.settings.php',
	    
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
            include __DIR__ . '/../config/tools/app.tools.assoc_var.php',
	    
    	    // categories plugin configs
    	    include __DIR__ . '/../config/plugins/categories/MelisCommerceCategorySliderListProductsPlugin.php',
    	    include __DIR__ . '/../config/plugins/categories/MelisCommerceCategoryListProductsPlugin.php',
    	    include __DIR__ . '/../config/plugins/categories/MelisCommerceFilterMenuCategoryListPlugin.php',
    	    include __DIR__ . '/../config/plugins/categories/MelisCommerceFilterMenuProductSearchBoxPlugin.php',
    	    include __DIR__ . '/../config/plugins/categories/MelisCommerceFilterMenuPriceValueBoxPlugin.php',
    	    include __DIR__ . '/../config/plugins/categories/MelisCommerceFilterMenuAttributeValueBoxPlugin.php',
    	    
    	    // clients plugin configs
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceLoginPlugin.php',
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceLostPasswordGetEmailPlugin.php',
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceLostPasswordResetPlugin.php',
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceRegisterPlugin.php',
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceAccountPlugin.php',
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceProfilePlugin.php',
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceDeliveryAddressPlugin.php',
    	    include __DIR__ . '/../config/plugins/clients/MelisCommerceBillingAddressPlugin.php',
    	    
    	    // order plugin configs
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCartAddPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCheckoutPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCheckoutCartPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCheckoutCouponAddPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCheckoutAddressesPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCheckoutSummaryPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCheckoutConfirmSummaryPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCheckoutConfirmPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceCartMenuPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceOrderListPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceOrderPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceOrderAddressPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceOrderShippingDetailsPlugin.php',
    	    include __DIR__ . '/../config/plugins/orders/MelisCommerceOrderMessagesPlugin.php',
    	    
    	    // products plugin configs
    	    include __DIR__ . '/../config/plugins/products/MelisCommerceProductShowPlugin.php',
    	    include __DIR__ . '/../config/plugins/products/MelisCommerceAttributesShowPlugin.php',
    	    include __DIR__ . '/../config/plugins/products/MelisCommerceProductsRelatedPlugin.php',
    	    
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
    
    public function createTranslations($e, $module)
    {
        $sm = $e->getApplication()->getServiceManager();
        $translator = $sm->get('translator');
        
        // Checking if the Request is from Melis-BackOffice or Front
        if ($module[0] == 'melis-backoffice')
        {
            $container = new Container('meliscore');
            $locale = $container['melis-lang-locale'];
        }
        else
        {
            $container = new Container('melisplugins');
            $locale = $container['melis-plugins-lang-locale'];
        }
        
        if(!empty($locale)) 
        {   
            // Commerce sub modules langauge config
            // used to identify the folder and file name: 
            // Translation path:            module/MelisModuleConfig/languages/MelisCommerce/[locale].interface.[module].php'
            // Default translation path :   __DIR__ . '/../language/[module]/en_EN.interface.[module].php';
            $commerceSubModules = array(
                'general',
                'categories',
                'products',
                'variants',
                'documents',
                'clients',
                'orders',
                'prices',
                'seo',
                'coupons',
                'checkout',
                'currency',
                'attributes',
                'language',
                'country',
                'assoc-var',
                'duplication',
                'settings',
            );
            
            $translationType = array(
                'interface',
                'forms',
            );
            
            $translationList = array();
            if(file_exists($_SERVER['DOCUMENT_ROOT'].'/../module/MelisModuleConfig/config/translation.list.php')){
                $translationList = include 'module/MelisModuleConfig/config/translation.list.php';
            }
            
            // Load translation files
            foreach($commerceSubModules as $subModule){
                
                foreach($translationType as $type){

                    $transPath = '';
                    $moduleTrans = __NAMESPACE__."/$locale.$type.$subModule.php";
                    
                    if(in_array($moduleTrans, $translationList)){
                        $transPath = "module/MelisModuleConfig/languages/".$moduleTrans;
                    }
                    
                    if(empty($transPath)){
                    
                        // if translation is not found, use melis default translations
                        $defaultLocale = (file_exists(__DIR__ . "/../language/$subModule/$locale.$type.$subModule.php"))? $locale : "en_EN";
                        $transPath = __DIR__ . "/../language/$subModule/$defaultLocale.$type.$subModule.php";
                    }
                    
                    $translator->addTranslationFile('phparray', $transPath);
                }
            }
        }
    }
}