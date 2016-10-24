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

use MelisCommerce\Model\MelisEcomLang;
use MelisCommerce\Model\Tables\MelisEcomLangTable;
use MelisCommerce\Model\MelisEcomCategory;
use MelisCommerce\Model\Tables\MelisEcomCategoryTable;
use MelisCommerce\Model\MelisEcomCategoryTrans;
use MelisCommerce\Model\Tables\MelisEcomCategoryTransTable;
use MelisCommerce\Model\MelisEcomDocument;
use MelisCommerce\Model\Tables\MelisEcomDocumentTable;
use MelisCommerce\Model\MelisEcomDocType;
use MelisCommerce\Model\Tables\MelisEcomDocTypeTable;
use MelisCommerce\Model\MelisEcomDocRelations;
use MelisCommerce\Model\Tables\MelisEcomDocRelationsTable;
use MelisCommerce\Model\MelisEcomProduct;
use MelisCommerce\Model\Tables\MelisEcomProductTable;
use MelisCommerce\Model\MelisEcomProductText;
use MelisCommerce\Model\Tables\MelisEcomProductTextTable;
use MelisCommerce\Model\MelisEcomProductTextType;
use MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable;
use MelisCommerce\Model\MelisEcomProductCategory;
use MelisCommerce\Model\Tables\MelisEcomProductCategoryTable;
use MelisCommerce\Model\MelisEcomVariant;
use MelisCommerce\Model\Tables\MelisEcomVariantTable;
use MelisCommerce\Model\MelisEcomCountry;
use MelisCommerce\Model\Tables\MelisEcomCountryTable;
use MelisCommerce\Model\MelisEcomCountryCategory;
use MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable;
use MelisCommerce\Model\MelisEcomPrice;
use MelisCommerce\Model\Tables\MelisEcomPriceTable;
use MelisCommerce\Model\MelisEcomVariantStock;
use MelisCommerce\Model\Tables\MelisEcomVariantStockTable;
use MelisCommerce\Model\MelisEcomAttribute;
use MelisCommerce\Model\Tables\MelisEcomAttributeTable;
use MelisCommerce\Model\MelisEcomAttributeTrans;
use MelisCommerce\Model\Tables\MelisEcomAttributeTransTable;
use MelisCommerce\Model\MelisEcomAttributeValue;
use MelisCommerce\Model\Tables\MelisEcomAttributeValueTable;
use MelisCommerce\Model\MelisEcomAttributeValueTrans;
use MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable;
use MelisCommerce\Model\MelisEcomProductAttribute;
use MelisCommerce\Model\Tables\MelisEcomProductAttributeTable;
use MelisCommerce\Model\MelisEcomVariantAttributeValue;
use MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable;
use MelisCommerce\Model\MelisEcomAttributeType;
use MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable;
use MelisCommerce\Model\MelisEcomSeo;
use MelisCommerce\Model\Tables\MelisEcomSeoTable;
use MelisCommerce\Model\MelisEcomCurrency;
use MelisCommerce\Model\Tables\MelisEcomCurrencyTable;
use MelisCommerce\Model\MelisEcomClient;
use MelisCommerce\Model\Tables\MelisEcomClientTable;
use MelisCommerce\Model\MelisEcomClientPerson;
use MelisCommerce\Model\Tables\MelisEcomClientPersonTable;
use MelisCommerce\Model\MelisEcomClientAddress;
use MelisCommerce\Model\Tables\MelisEcomClientAddressTable;
use MelisCommerce\Model\MelisEcomClientCompany;
use MelisCommerce\Model\Tables\MelisEcomClientCompanyTable;
use MelisCommerce\Model\MelisEcomCivilityTrans;
use MelisCommerce\Model\Tables\MelisEcomCivilityTransTable;
use MelisCommerce\Model\MelisEcomClientAddressTypeTrans;
use MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable;
use MelisCommerce\Model\MelisEcomClientAddressType;
use MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable;
use MelisCommerce\Model\MelisEcomBasketPersistent;
use MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable;
use MelisCommerce\Model\MelisEcomBasketAnonymous;
use MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable;
use MelisCommerce\Model\MelisEcomOrder;
use MelisCommerce\Model\Tables\MelisEcomOrderTable;
use MelisCommerce\Model\MelisEcomOrderAddress;
use MelisCommerce\Model\Tables\MelisEcomOrderAddressTable;
use MelisCommerce\Model\MelisEcomCoupon;
use MelisCommerce\Model\Tables\MelisEcomCouponTable;
use MelisCommerce\Model\MelisEcomCouponOrder;
use MelisCommerce\Model\Tables\MelisEcomCouponOrderTable;
use MelisCommerce\Model\MelisEcomCouponClient;
use MelisCommerce\Model\Tables\MelisEcomCouponClientTable;
use MelisCommerce\Model\MelisEcomOrderPayment;
use MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable;
use MelisCommerce\Model\MelisEcomOrderPaymentType;
use MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable;
use MelisCommerce\Model\MelisEcomOrderStatus;
use MelisCommerce\Model\Tables\MelisEcomOrderStatusTable;
use MelisCommerce\Model\MelisEcomOrderStatusTrans;
use MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable;
use MelisCommerce\Model\MelisEcomOrderMessage;
use MelisCommerce\Model\Tables\MelisEcomOrderMessageTable;
use MelisCommerce\Model\MelisEcomOrderShipping;
use MelisCommerce\Model\Tables\MelisEcomOrderShippingTable;
use MelisCommerce\Model\MelisEcomOrderBasket;
use MelisCommerce\Model\Tables\MelisEcomOrderBasketTable;

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
        }
    }
    
    public function getServiceConfig()
    {
        return array(
			'factories' => array(
			    
			    // Business Services
				'MelisCommerce\Service\MelisComCategoryService' =>  function($sm) {
					$melisComCategoryService = new \MelisCommerce\Service\MelisComCategoryService();
					$melisComCategoryService->setServiceLocator($sm);
					$melisComCategoryService->setEventManager($sm->get('EventManager'));
					return $melisComCategoryService;
				},
				'MelisCommerce\Service\MelisComDocumentService' =>  function($sm) {
					$melisComDocumentService = new \MelisCommerce\Service\MelisComDocumentService();
					$melisComDocumentService->setServiceLocator($sm);
					$melisComDocumentService->setEventManager($sm->get('EventManager'));
					return $melisComDocumentService;
				},
				'MelisCommerce\Service\MelisComProductService' =>  function($sm) {
					$melisComProductService = new \MelisCommerce\Service\MelisComProductService();
					$melisComProductService->setServiceLocator($sm);
					$melisComProductService->setEventManager($sm->get('EventManager'));
					return $melisComProductService;
				},
				'MelisCommerce\Service\MelisComVariantService' =>  function($sm) {
					$melisComVariantService = new \MelisCommerce\Service\MelisComVariantService();
					$melisComVariantService->setServiceLocator($sm);
					$melisComVariantService->setEventManager($sm->get('EventManager'));
					return $melisComVariantService;
				},
				'MelisCommerce\Service\MelisComAttributeService' =>  function($sm) {
    				$melisComAttributeService = new \MelisCommerce\Service\MelisComAttributeService();
    				$melisComAttributeService->setServiceLocator($sm);
    				$melisComAttributeService->setEventManager($sm->get('EventManager'));
    				return $melisComAttributeService;
				},
				'MelisCommerce\Service\MelisComClientService' =>  function($sm) {
    				$melisComClientService = new \MelisCommerce\Service\MelisComClientService();
    				$melisComClientService->setServiceLocator($sm);
    				$melisComClientService->setEventManager($sm->get('EventManager'));
    				return $melisComClientService;
				},
				'MelisCommerce\Service\MelisComOrderService' =>  function($sm) {
    				$melisComOrderService = new \MelisCommerce\Service\MelisComOrderService();
    				$melisComOrderService->setServiceLocator($sm);
    				$melisComOrderService->setEventManager($sm->get('EventManager'));
    				return $melisComOrderService;
				},
				'MelisCommerce\Service\MelisComSeoService' =>  function($sm) {
    				$melisComSeoService = new \MelisCommerce\Service\MelisComSeoService();
    				$melisComSeoService->setServiceLocator($sm);
    				$melisComSeoService->setEventManager($sm->get('EventManager'));
    				return $melisComSeoService;
				},
				'MelisCommerce\Service\MelisComCouponService' =>  function($sm) {
    				$melisComCouponService = new \MelisCommerce\Service\MelisComCouponService();
    				$melisComCouponService->setServiceLocator($sm);
    				$melisComCouponService->setEventManager($sm->get('EventManager'));
    				return $melisComCouponService;
				},
				'MelisCommerce\Service\MelisComHeadService' =>  function($sm) {
					$melisComHeadService = new \MelisCommerce\Service\MelisComHeadService();
					$melisComHeadService->setServiceLocator($sm);
					return $melisComHeadService;
				},
				'MelisCommerce\Service\MelisComBasketService' =>  function($sm) {
					$melisComBasketService = new \MelisCommerce\Service\MelisComBasketService();
					$melisComBasketService->setServiceLocator($sm);
					return $melisComBasketService;
				},
				'MelisCommerce\Service\MelisComOrderCheckoutService' =>  function($sm) {
					$melisOrderCheckoutService = new \MelisCommerce\Service\MelisComOrderCheckoutService();
					$melisOrderCheckoutService->setServiceLocator($sm);
					return $melisOrderCheckoutService;
				},
				'MelisCommerce\Service\MelisComShipmentCostService' =>  function($sm) {
					$melisShipmentCostService = new \MelisCommerce\Service\MelisComShipmentCostService();
					$melisShipmentCostService->setServiceLocator($sm);
					return $melisShipmentCostService;
				},
    					
				
				// Listeners
				'MelisCommerce\Listener\MelisCommerceSEODispatchRouterCommerceUrlListener' =>  function($sm) {
    				$melisCommerceSEODispatchRouterCommerceUrlListener = new MelisCommerceSEODispatchRouterCommerceUrlListener();
    				$melisCommerceSEODispatchRouterCommerceUrlListener->setServiceLocator($sm);
    				return $melisCommerceSEODispatchRouterCommerceUrlListener;
				},
				'MelisCommerce\Listener\MelisCommerceSEOReformatToRoutePageUrlListener' =>  function($sm) {
    				$melisCommerceSEOReformatToRoutePageUrlListener = new MelisCommerceSEOReformatToRoutePageUrlListener();
    				$melisCommerceSEOReformatToRoutePageUrlListener->setServiceLocator($sm);
    				return $melisCommerceSEOReformatToRoutePageUrlListener;
				},
				
                // Table services
				'MelisCommerce\Model\Tables\MelisEcomLangTable' =>  function($sm) {
				    return new MelisEcomLangTable($sm->get('MelisEcomLangTableGateway'));
				},
				'MelisEcomLangTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomLang());
				    return new TableGateway('melis_ecom_lang', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCategoryTable' =>  function($sm) {
				    return new MelisEcomCategoryTable($sm->get('MelisEcomCategoryTableGateway'));
				},
				'MelisEcomCategoryTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCategory());
				    return new TableGateway('melis_ecom_category', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCategoryTransTable' =>  function($sm) {
				    return new MelisEcomCategoryTransTable($sm->get('MelisEcomCategoryTransTableGateway'));
				},
				'MelisEcomCategoryTransTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCategoryTrans());
				    return new TableGateway('melis_ecom_category_trans', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomDocumentTable' =>  function($sm) {
				    return new MelisEcomDocumentTable($sm->get('MelisEcomDocumentTableGateway'));
				},
				'MelisEcomDocumentTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomDocument());
				    return new TableGateway('melis_ecom_document', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomDocTypeTable' =>  function($sm) {
				    return new MelisEcomDocTypeTable($sm->get('MelisEcomDocTypeTableGateway'));
				},
				'MelisEcomDocTypeTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomDocType());
				    return new TableGateway('melis_ecom_doc_type', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomDocRelationsTable' =>  function($sm) {
				    return new MelisEcomDocRelationsTable($sm->get('MelisEcomDocRelationsTableGateway'));
				},
				'MelisEcomDocRelationsTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomDocRelations());
				    return new TableGateway('melis_ecom_doc_relations', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomProductTable' =>  function($sm) {
				    return new MelisEcomProductTable($sm->get('MelisEcomProductTableGateway'));
				},
				'MelisEcomProductTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomProduct());
				    return new TableGateway('melis_ecom_product', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomProductTextTable' =>  function($sm) {
				    return new MelisEcomProductTextTable($sm->get('MelisEcomProductTextTableGateway'));
				},
				'MelisEcomProductTextTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomProductText());
				    return new TableGateway('melis_ecom_product_text', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable' =>  function($sm) {
				    return new MelisEcomProductTextTypeTable($sm->get('MelisEcomProductTextTypeTableGateway'));
				},
				'MelisEcomProductTextTypeTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomProductTextType());
				    return new TableGateway('melis_ecom_product_text_type', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable' =>  function($sm) {
				    return new MelisEcomProductCategoryTable($sm->get('MelisEcomProductCategoryTableGateway'));
				},
				'MelisEcomProductCategoryTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomProductCategory());
				    return new TableGateway('melis_ecom_product_category', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomVariantTable' =>  function($sm) {
				    return new MelisEcomVariantTable($sm->get('MelisEcomVariantTableGateway'));
				},
				'MelisEcomVariantTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomVariant());
				    return new TableGateway('melis_ecom_variant', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCountryTable' =>  function($sm) {
				    return new MelisEcomCountryTable($sm->get('MelisEcomCountryTableGateway'));
				},
				'MelisEcomCountryTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCountry());
				    return new TableGateway('melis_ecom_country', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable' =>  function($sm) {
				    return new MelisEcomCountryCategoryTable($sm->get('MelisEcomCountryCategoryTableGateway'));
				},
				'MelisEcomCountryCategoryTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCountryCategory());
				    return new TableGateway('melis_ecom_country_category', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomPriceTable' =>  function($sm) {
				    return new MelisEcomPriceTable($sm->get('MelisEcomPriceTableGateway'));
				},
				'MelisEcomPriceTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomPrice());
				    return new TableGateway('melis_ecom_price', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomVariantStockTable' =>  function($sm) {
				    return new MelisEcomVariantStockTable($sm->get('MelisEcomVariantStockTableGateway'));
				},
				'MelisEcomVariantStockTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomVariantStock());
				    return new TableGateway('melis_ecom_variant_stock', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomAttributeTable' =>  function($sm) {
				    return new MelisEcomAttributeTable($sm->get('MelisEcomAttributeTableGateway'));
				},
				'MelisEcomAttributeTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomAttribute());
				    return new TableGateway('melis_ecom_attribute', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable' =>  function($sm) {
				    return new MelisEcomAttributeTransTable($sm->get('MelisEcomAttributeTransTableGateway'));
				},
				'MelisEcomAttributeTransTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomAttributeTrans());
				    return new TableGateway('melis_ecom_attribute_trans', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomAttributeValueTable' =>  function($sm) {
				    return new MelisEcomAttributeValueTable($sm->get('MelisEcomAttributeValueTableGateway'));
				},
				'MelisEcomAttributeValueTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomAttributeValue());
				    return new TableGateway('melis_ecom_attribute_value', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable' =>  function($sm) {
				    return new MelisEcomAttributeValueTransTable($sm->get('MelisEcomAttributeValueTransTableGateway'));
				},
				'MelisEcomAttributeValueTransTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomAttributeValueTrans());
				    return new TableGateway('melis_ecom_attribute_value_trans', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomProductAttributeTable' =>  function($sm) {
				    return new MelisEcomProductAttributeTable($sm->get('MelisEcomProductAttributeTableGateway'));
				},
				'MelisEcomProductAttributeTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomProductAttribute());
				    return new TableGateway('melis_ecom_product_attribute', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable' =>  function($sm) {
				    return new MelisEcomVariantAttributeValueTable($sm->get('MelisEcomVariantAttributeValueTableGateway'));
				},
				'MelisEcomVariantAttributeValueTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomVariantAttributeValue());
				    return new TableGateway('melis_ecom_variant_attribute_value', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable' =>  function($sm) {
				    return new MelisEcomAttributeTypeTable($sm->get('MelisEcomAttributeTypeTableGateway'));
				},
				'MelisEcomAttributeTypeTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomAttributeType());
				    return new TableGateway('melis_ecom_attribute_type', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomSeoTable' =>  function($sm) {
				    return new MelisEcomSeoTable($sm->get('MelisEcomSeoTableGateway'));
				},
				'MelisEcomSeoTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomSeo());
				    return new TableGateway('melis_ecom_seo', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCurrencyTable' =>  function($sm) {
				    return new MelisEcomCurrencyTable($sm->get('MelisEcomCurrencyTableGateway'));
				},
				'MelisEcomCurrencyTableGateway' => function ($sm) {
				    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCurrency());
				    return new TableGateway('melis_ecom_currency', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomClientTable' =>  function($sm) {
				    return new MelisEcomClientTable($sm->get('MelisEcomClientTableGateway'));
				},
				'MelisEcomClientTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomClient());
    				return new TableGateway('melis_ecom_client', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomClientPersonTable' =>  function($sm) {
				    return new MelisEcomClientPersonTable($sm->get('MelisEcomClientPersonTableGateway'));
				},
				'MelisEcomClientPersonTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomClientPerson());
    				return new TableGateway('melis_ecom_client_person', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomClientAddressTable' =>  function($sm) {
				    return new MelisEcomClientAddressTable($sm->get('MelisEcomClientAddressTableGateway'));
				},
				'MelisEcomClientAddressTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomClientAddress());
    				return new TableGateway('melis_ecom_client_address', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomClientCompanyTable' =>  function($sm) {
				    return new MelisEcomClientCompanyTable($sm->get('MelisEcomClientCompanyTableGateway'));
				},
				'MelisEcomClientCompanyTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomClientCompany());
    				return new TableGateway('melis_ecom_client_company', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCivilityTransTable' =>  function($sm) {
				    return new MelisEcomCivilityTransTable($sm->get('MelisEcomCivilityTransTableGateway'));
				},
				'MelisEcomCivilityTransTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCivilityTrans());
    				return new TableGateway('melis_ecom_civility_trans', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable' =>  function($sm) {
				    return new MelisEcomClientAddressTypeTable($sm->get('MelisEcomAddressTypeTableGateway'));
				},
				'MelisEcomAddressTypeTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomClientAddressType());
    				return new TableGateway('melis_ecom_client_address_type', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable' =>  function($sm) {
				    return new MelisEcomClientAddressTypeTransTable($sm->get('MelisEcomAddressTypeTransTableGateway'));
				},
				'MelisEcomAddressTypeTransTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomClientAddressTypeTrans());
    				return new TableGateway('melis_ecom_client_address_type_trans', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable' =>  function($sm) {
				    return new MelisEcomBasketPersistentTable($sm->get('MelisEcomBasketPersistentTableGateway'));
				},
				'MelisEcomBasketPersistentTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomBasketPersistent());
    				return new TableGateway('melis_ecom_basket_persistent', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable' =>  function($sm) {
				    return new MelisEcomBasketAnonymousTable($sm->get('MelisEcomBasketAnonymousTableGateway'));
				},
				'MelisEcomBasketAnonymousTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomBasketAnonymous());
    				return new TableGateway('melis_ecom_basket_anonymous', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderTable' =>  function($sm) {
				    return new MelisEcomOrderTable($sm->get('MelisEcomOrderTableGateway'));
				},
				'MelisEcomOrderTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrder());
    				return new TableGateway('melis_ecom_order', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderAddressTable' =>  function($sm) {
				    return new MelisEcomOrderAddressTable($sm->get('MelisEcomOrderAddressTableGateway'));
				},
				'MelisEcomOrderAddressTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderAddress());
    				return new TableGateway('melis_ecom_order_address', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCouponTable' =>  function($sm) {
				    return new MelisEcomCouponTable($sm->get('MelisEcomCouponTableGateway'));
				},
				'MelisEcomCouponTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCoupon());
    				return new TableGateway('melis_ecom_coupon', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCouponOrderTable' =>  function($sm) {
				    return new MelisEcomCouponOrderTable($sm->get('MelisEcomCouponOrderTableGateway'));
				},
				'MelisEcomCouponOrderTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCouponOrder());
    				return new TableGateway('melis_ecom_coupon_order', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomCouponClientTable' =>  function($sm) {
				return new MelisEcomCouponClientTable($sm->get('MelisEcomCouponClientTableGateway'));
				},
				'MelisEcomCouponClientTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCouponClient());
    				return new TableGateway('melis_ecom_coupon_client', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable' =>  function($sm) {
				    return new MelisEcomOrderPaymentTable($sm->get('MelisEcomOrderPaymentTableGateway'));
				},
				'MelisEcomOrderPaymentTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderPayment());
    				return new TableGateway('melis_ecom_order_payment', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable' =>  function($sm) {
				    return new MelisEcomOrderPaymentTypeTable($sm->get('MelisEcomOrderPaymentTypeTableGateway'));
				},
				'MelisEcomOrderPaymentTypeTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderPaymentType());
    				return new TableGateway('melis_ecom_order_payment_type', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderStatusTable' =>  function($sm) {
				    return new MelisEcomOrderStatusTable($sm->get('MelisEcomOrderStatusTableGateway'));
				},
				'MelisEcomOrderStatusTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderStatus());
    				return new TableGateway('melis_ecom_order_status', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable' =>  function($sm) {
				    return new MelisEcomOrderStatusTransTable($sm->get('MelisEcomOrderStatusTransTableGateway'));
				},
				'MelisEcomOrderStatusTransTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderStatusTrans());
    				return new TableGateway('melis_ecom_order_status_trans', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderMessageTable' =>  function($sm) {
				    return new MelisEcomOrderMessageTable($sm->get('MelisEcomOrderMessageTableGateway'));
				},
				'MelisEcomOrderMessageTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderMessage());
    				return new TableGateway('melis_ecom_order_messages', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderShippingTable' =>  function($sm) {
				    return new MelisEcomOrderShippingTable($sm->get('MelisEcomOrderShippingTableGateway'));
				},
				'MelisEcomOrderShippingTableGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderShipping());
    				return new TableGateway('melis_ecom_order_shipping', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
				'MelisCommerce\Model\Tables\MelisEcomOrderBasketTable' =>  function($sm) {
				    return new MelisEcomOrderBasketTable($sm->get('MelisEcomOrderBasketGateway'));
				},
				'MelisEcomOrderBasketGateway' => function ($sm) {
    				$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomOrderBasket());
    				return new TableGateway('melis_ecom_order_basket', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
				},
			),
        );
    }
}
