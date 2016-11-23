<?php 


/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
class MelisComAssociateVariantController extends AbstractActionController
{
    private function getPrefix()
    {
        $variantId = (int) $this->params()->fromQuery('variantId', '');
        return $variantId;
    }

    public function renderTabAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;

    }

    public function renderTabContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;
    }

    public function renderTabContentAssocVarListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $assocVarTable = $this->getServiceLocator()->get('MelisEcomAssocVariantTypeTable');
        $data = $assocVarTable->fetchAll()->toArray();

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        $view->data = $data;
        return $view;
    }

    public function renderTabContentVarListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->prefixId = $this->getPrefix();
        return $view;
    }
}