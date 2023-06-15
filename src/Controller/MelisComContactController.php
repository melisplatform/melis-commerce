<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 6/14/2023
 * Time: 12:20 PM
 */

namespace MelisCommerce\Controller;


use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComContactController extends MelisAbstractActionController
{
    const PLUGIN_INDEX = 'meliscommerce';

    /**
     * @return ViewModel
     */
    public function renderAccountContactListTableSearchAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactListTableLimitAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactListTableRefreshAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $accountId = $this->params()->fromQuery('clientId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->accountId = $accountId;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactListTableEditAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountListPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountListPageHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountListPageContentAction()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_contact_list');

        $clientId = $this->params()->fromQuery('clientId', '');

        // DataTable costume configuration
        $columns = $melisTool->getColumns();
        $translator = $this->getServiceManager()->get('translator');
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_clients_common_label_action'));

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->clientId = $clientId;
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$clientId.'_contactList', null, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }

    /**
     * @return JsonModel
     */
    public function getContactListAction()
    {
        return new JsonModel(['data' => []]);
    }
}