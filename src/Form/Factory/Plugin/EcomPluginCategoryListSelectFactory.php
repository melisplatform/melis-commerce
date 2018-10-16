<?php

/**
 * Melis Technology (http://www.melistechnology.com)
*
* @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
*
*/

namespace MelisCommerce\Form\Factory\Plugin;

use Zend\ServiceManager\ServiceLocatorInterface;
use MelisCore\Form\Factory\MelisSelectFactory;
use Zend\Session\Container;
/**
 * MelisCommerce Category list Select
 */
class EcomPluginCategoryListSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $serviceManager = $formElementManager->getServiceLocator();
        
        // Getting Current Langauge ID
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $catTble = $serviceManager->get('MelisEcomCategoryTable');
        $catList = $catTble->getCategoryList(false, $langId);
        
        $melisComCategoryService = $serviceManager->get('MelisComCategoryService');
        
        foreach ($catList As $val)
        {
            $catName = '';
            if (!empty($val->catt_name))
            {
                $catName = $val->catt_name;
            }
            else
            {
                $catName = $melisComCategoryService->getCategoryNameById($val->cat_id, $langId);
            }
            
            $catalog = '';
            if ($val->cat_father_cat_id == -1)
            {
                $catalog = ' - (Catalog)';
            }
            
            $valueoptions[$val->cat_id] = $val->cat_id.' - '.$catName.$catalog;
        }
        
        return $valueoptions;
    }

}