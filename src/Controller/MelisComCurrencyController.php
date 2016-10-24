<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
class MelisComCurrencyController extends AbstractActionController
{

    public function containerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', $this->params()->fromQuery('melisKey', null));
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        
        $view = new ViewModel();
        $view->title = $this->getTool()->getTitle();
        $view->melisKey = $melisKey;
        $view->noAccess = null;
        
        return $view;
    }
    
    public function headerAction()
    {

        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $view = new ViewModel();
        $view->title = $this->getTool()->getTitle();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function contentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', $this->params()->fromQuery('melisKey', null));
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        
        return $view;
    }
    
    
    private function getTool()
    {
        $tool = $this->getServiceLocator()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_currency');
        
        return $tool;
    }
    
//     private function hasAccess($key = 'meliscommerce_currency_lists')
//     {
//         $melisCoreAuth = $this->getServiceLocator()->get('MelisCoreAuth');
//         $melisCoreRights = $this->getServiceLocator()->get('MelisCoreRights');
//         $xmlRights = $melisCoreAuth->getAuthRights();
        
//         $isAccessible = $melisCoreRights->isAccessible($xmlRights, MelisCoreRightsService::MELISCORE_PREFIX_TOOLS, $key);
        
//         return $isAccessible;
//     }

    public function hasAccessAction()
    {
        $melisCoreAuth = $this->getServiceLocator()->get('MelisCoreAuth');
        $melisCoreRights = $this->getServiceLocator()->get('MelisCoreRights');
        $xmlRights = $melisCoreAuth->getAuthRights();

        print_r($xmlRights);
        $key = 'meliscommerce_currency_conf';

        $isAccessible = $melisCoreRights->isAccessible($xmlRights, MelisCoreRightsService::MELISCORE_PREFIX_TOOLS, $key);

        //return $isAccessible;
        
        die;
    }
    
    

    
}