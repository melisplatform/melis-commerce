<?php


namespace MelisCommerce\Controller;


use Laminas\View\Model\ViewModel;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComClientsGroupController extends MelisAbstractActionController
{
    public function renderClientsGroupAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }
}