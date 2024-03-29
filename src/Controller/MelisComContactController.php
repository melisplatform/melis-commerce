<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 6/14/2023
 * Time: 12:20 PM
 */

namespace MelisCommerce\Controller;


use Laminas\Http\Headers;
use Laminas\Http\Response;
use Laminas\Session\Container;
use Laminas\Stdlib\ArrayUtils;
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
    public function renderAccountContactListTableDeleteAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contacts_delete_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
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
    public function renderAccountContactListTableAccountSelectAction()
    {
        $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
        $translator = $this->getServiceManager()->get('translator');
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //check commerce settings for ordering
        $accountSettings = $melisComClientService->getAccountNameSetting();
        $type = !(empty($accountSettings)) ? $accountSettings->sa_type : 'manual_input';
        $orderCol = 'cli_name';
        if($type == 'company_name'){
            $orderCol = 'cli_company';
        }

        $options = '<option  value="">'.$translator->translate('tr_meliscommerce_contact_common_choose').'</option>';

        $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientTable');
        $lists = $melisEcomClientPersonTable->getAccountToolList(array(
            'where' => array(

            ),
            'order' => array(
                'key' => $orderCol,
                'dir' => 'ASC',
            ),
            'start' => null,
            'limit' => null,
            'columns' => [],
            'date_filter' => array(),
            'groupId' => null,
            'clientStatus' => 1,
            'count' => false
        ))->toArray();

        $clientIds = array_map(function ($account) {
            return $account['cli_id'];
        }, $lists);
        $optionsList = $melisComClientService->getAccountNamesByClientIdArray($clientIds);
        foreach($optionsList as $key => $account){
            $options .= '<option value="'.$account['ccomp_client_id'].'">'.$account['ccomp_name'].'</option>';
        }

        $view =  new ViewModel();
        $view->selectOption = $options;
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactListTableStatusSelectAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $groups = [
            '1' => $translator->translate('tr_meliscommerce_client_status_active'),
            '0' => $translator->translate('tr_meliscommerce_client_status_inactive'),
        ];

        $options = '<option  value="">'.$translator->translate('tr_meliscommerce_clients_common_label_all').'</option>';
        foreach($groups as $val => $name){
            $selected = ($val == 1) ? 'selected' : '';
            $options .= '<option '.$selected.' value="'.$val.'">'.$name.'</option>';
        }

        $view =  new ViewModel();
        $view->selectOptions = $options;
        return $view;
    }

    public function renderAccountContactListTableTypeSelectAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $translator = $this->getServiceManager()->get('translator');
        $lists = [
            'person' => $translator->translate('tr_meliscommerce_contact_common_person'),
            'company' => $translator->translate('tr_meliscommerce_contact_common_company'),
        ];
        $options = '<option  value="">'.$translator->translate('tr_meliscommerce_contact_common_all').'</option>';
        foreach($lists as $key => $type){
            $options .= '<option value="'.$key.'">'.$type.'</option>';
        }

        $view =  new ViewModel();
        $view->selectOption = $options;
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

    public function renderAccountListPageHeaderButtonAddContactAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contacts_list_add_contact_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
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
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#contactList', null, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }

    /**
     * Function to get contact lists
     * Used in contact tool
     *
     * @return JsonModel
     */
    public function getContactListAction()
    {
        $dataCount = 0;
        $draw = 0;
        $tableData = array();

        if($this->getRequest()->isPost())
        {
            $defaultAccountOnly = true;

            $accountId = $this->getRequest()->getPost('accountId', null);
            $type = $this->getRequest()->getPost('type', null);
            $status = $this->getRequest()->getPost('status', null);

            $translator = $this->getServiceManager()->get('translator');
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $melisTool = $this->getServiceManager()->get('MelisCoreTool');
            $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_contact_list');

            $colId = array_keys($melisTool->getColumns());

            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];

            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];

            $draw = $this->getRequest()->getPost('draw');

            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');

            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];

            $contactService = $this->getServiceManager()->get('MelisComContactService');
            $personTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');

            $tableData = $personTable->getContactLists($accountId, $type, $status, $search, $melisTool->getSearchableColumns(), $start, $length, $selCol, $sortOrder, $defaultAccountOnly)->toArray();
            $dataCount = $personTable->getContactLists($accountId, $type, $status, $search, $melisTool->getSearchableColumns(), null, null, null, 'ASC', $defaultAccountOnly, false, true)->current();

            foreach ($tableData as $key => $val)
            {
                $contactStatus = '<i class="fa fa-circle text-danger"></i>';
                // Generating contact status html form
                if ($val['cper_status'])
                {
                    $contactStatus = '<i class="fa fa-circle text-success"></i>';
                }

                $cliName = '';
                if($val['cpr_default_client']) {
                    $cliName = $melisComClientService->getAccountName($val['cli_id']);
                    if (!empty($cliName))
                        $cliName = "<span class='d-none td-tooltip'>" . $cliName . "</span>" . mb_strimwidth($cliName, 0, 30, '...');
                }
                if(!empty($val['cper_tags'])){
                    $str = '';
                    $tags = explode(',',$val['cper_tags']);
                    foreach($tags as $t){
                        $str .= ' '.'<label class="badge badge-secondary">'.$t.'</label>';
                    }
                    $tableData[$key]['cper_tags'] = $str;
                }

                $tableData[$key]['cli_name'] = $cliName;
                $tableData[$key]['cper_status'] = $contactStatus;
                $tableData[$key]['cper_firstname'] = (!empty($val['cper_firstname'])) ? "<span class='d-none td-tooltip'>".$val['cper_firstname']."</span>".mb_strimwidth($val['cper_firstname'], 0, 30, '...') : '';
                $tableData[$key]['cper_name'] = !empty($val['cper_name']) ? "<span class='d-none td-tooltip'>".$val['cper_name']."</span>".mb_strimwidth($val['cper_name'], 0, 30, '...') : '';
                $tableData[$key]['cper_type'] = ($val['cper_type'] == 'person') ? $translator->translate('tr_meliscommerce_contact_common_person') : $translator->translate('tr_meliscommerce_contact_common_company');

                //check if contact has an associated account
                $assocAccounts = $contactService->getContactAssocAccountLists($val['cper_id'])->toArray();
                $tableData[$key]['DT_RowAttr'] = [
                    'data-has_assoc_accounts' => !empty($assocAccounts) ? 1 : 0
                ];
            }
        }
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($tableData),
            'recordsFiltered' => $dataCount->totalRecords,
            'data' => $tableData,
        ));
    }

    /**
     * This method validate Client Contact Form and Add to the Client Contact list
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function saveContactAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $personId = null;
        $clientContactName = null;
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_contact_common_contact');
        $textMessage = 'tr_meliscommerce_contact_save_failed';
        $errors = array();
        $logTypeCode = 'ECOM_CONTACT_ADD';

        $request = $this->getRequest();

        if($request->isPost())
        {
            if(empty($errors)) {
                $contactData = [];
                $addressData = [];
                /**
                 * validate contact data
                 */
                $this->validateContact($errors, $contactData);
                /**
                 * Validate contact address data
                 */
//                $this->validateContactAddress($errors, $addressData);

                if(empty($errors)){
                    $postValues = $this->getRequest()->getPost()->toArray();
                    if (! empty($postValues['cper_id'])) {
                        $personId = $postValues['cper_id'];
                        $logTypeCode = 'ECOM_CONTACT_UPDATE';
                    }

                    $service = $this->getServiceManager()->get('MelisComContactService');
                    $id = $service->saveContact($contactData, $addressData, $personId);
                    if ($id) {
                        $melisComContactService = $this->getServiceManager()->get('MelisComContactService');
                        $contactData = $melisComContactService->getContactById($id);

                        if (!empty($contactData))
                        {
                            if($contactData->cper_type == 'company')
                                $clientContactName = $contactData->cper_firstname;
                            else
                                $clientContactName = $contactData->cper_firstname.' '.$contactData->cper_name;
                        }

                        $success = 1;
                        $textMessage = 'tr_meliscommerce_contact_save_success';
                    }
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $translator->translate($textMessage),
            'errors' => $errors,
            'clientContactName' => $clientContactName
        );

        $this->getEventManager()->trigger('meliscommerce_contact_save_end',
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $personId)));

        return new JsonModel($response);
    }

    /**
     * @param $errors
     * @param $contactData
     */
    public function validateContact(&$errors, &$contactData)
    {
        $personEmail = [];
        $translator = $this->getServiceManager()->get('translator');
        // Getting Client Contact Form from Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        $clientSvc = $this->getServiceManager()->get('MelisComClientService');

        $postValues = $this->getRequest()->getPost()->toArray();
        unset($postValues['contactAddress']);

        if($postValues['cper_type'] == 'company'){
            $propertyForm->getInputFilter()->remove('cper_name');
            unset($postValues['cper_name']);
        }

        // Getting the Elements/fields of the Client COntact form
        $appConfigFormElements = $appConfigForm['elements'];

        $propertyForm->setData($postValues);

        //change firstname label to company
        if($postValues['cper_type'] == 'company'){
            $propertyForm->get('cper_firstname')->setLabel($translator->translate('tr_meliscommerce_contact_common_company'));
        }

        if (! empty($postValues['cper_id'])) {
            // Checking if the Contact Form has data of the password
            if (empty($postValues['cper_password'])) {
                // If the existing Contact password empty, this means contact not updating the current password
                // removing Input from Contact form will also remove from the validation
                $propertyForm->getInputFilter()->remove('cper_password');
                $propertyForm->getInputFilter()->remove('cper_confirm_password');
            }
        }

        if (! empty($postValues['cper_email'])) {
            // check if email is not used twice
            $personWithSameEmail = $clientSvc->getPersonsByEmail($postValues['cper_email']);
            if(!empty($personWithSameEmail)) {
                foreach ($personWithSameEmail as $mail) {
                    if ($mail['cpmail_cper_id'] != $postValues['cper_id']) {
                        $errors['cper_email'] = [
                            'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                            'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                        ];
                        break;
                    } else {
                        $personEmail[$mail['cpmail_id']] = [
                            'cpmail_email' => $postValues['cper_email']
                        ];
                    }
                }
            }else{
                $personEmail[0] = [
                    'cpmail_email' => $postValues['cper_email']
                ];
            }
        }

        if ($propertyForm->isValid()) {
            // Getting Validated Data from Client Contact Form
            $contactData = $propertyForm->getData();
            $contactData['emails'] = $personEmail;
            unset($contactData['cper_confirm_password']);
        } else {
            $errors = ArrayUtils::merge($errors, $propertyForm->getMessages());
        }

        // Preparing the error messages if error is occured
        foreach ($errors as $keyError => $valueError)
        {
            foreach ($appConfigFormElements as $keyForm => $valueForm)
            {
                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                {
                    $label = $valueForm['spec']['options']['label'];

                    //change firstname label to company
                    if($postValues['cper_type'] == 'company'){
                        if($valueForm['spec']['name'] == 'cper_firstname') {
                            $label = $translator->translate('tr_meliscommerce_contact_common_company');
                        }
                    }

                    $errors[$keyError]['label'] = $label;
                }
            }
        }
    }

    /**
     * @param $errors
     * @param $addressData
     */
    public function validateContactAddress(&$errors, &$addressData)
    {
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);

        // Getting Data from Post and set data to Client Address Form
        $postValues = $this->getRequest()->getPost()->toArray();
        if(!empty($postValues['contactAddress'])) {
            foreach($postValues['contactAddress'] as $key => $add) {
                $propertyForm->setData($add);
                // Getting Client Address form elements/fields
                $appConfigFormElements = $appConfigForm['elements'];

                // Checking if Data setted to Form is valid
                if ($propertyForm->isValid()) {
                    $addressData[$key] = $propertyForm->getData();
                } else {
                    $errors = array_merge_recursive($errors, $propertyForm->getMessages());
                }
                // Preparing Error message if error occured
                foreach ($errors as $keyError => $valueError) {
                    foreach ($appConfigFormElements as $keyForm => $valueForm) {
                        if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])) {
                            $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                        }
                    }
                }
            }
        }
    }

    /**
     * Render Contact Page
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        return $view;
    }

    /**
     * Render Client Page Header
     * This method return also the Title of the Page
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        $translator = $this->getServiceManager()->get('translator');

        $title = $translator->translate('tr_meliscommerce_clients_add_client');

        if ($contactId)
        {
            $melisComContactService = $this->getServiceManager()->get('MelisComContactService');
            $contactData = $melisComContactService->getContactById($contactId);

            if (!empty($contactData))
            {
                if($contactData->cper_type == 'company')
                    $mainContactName = $contactData->cper_firstname;
                else
                    $mainContactName = $contactData->cper_firstname.' '.$contactData->cper_name;

                $title = $translator->translate('tr_meliscommerce_contact').' / '.$mainContactName;
            }
        }

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->title = $title;
        return $view;
    }

    /**
     * This method will return the Contact name
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function getContactNameAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_contact_get_contact_name');
        $textMessage = $translator->translate('tr_meliscore_error_message');;
        $errors = array();

        $clientContactName = '';

        $request = $this->getRequest();

        if($request->isPost())
        {
            $postValues = $request->getPost()->toArray();

            if (!empty($postValues['contactId']))
            {
                $contactId = $postValues['contactId'];
                // Getting Client Data from Client Service
                $melisComContactService = $this->getServiceManager()->get('MelisComContactService');
                $contactData = $melisComContactService->getContactById($contactId);

                if (!empty($contactData))
                {
                    if($contactData->cper_type == 'company')
                        $clientContactName = $contactData->cper_firstname;
                    else
                        $clientContactName = $contactData->cper_firstname.' '.$contactData->cper_name;

                    $textMessage = '';
                    $success = 1;
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'clientId' => $textTitle,
            'clientContactName' => $clientContactName,
            'errors' => $errors,
        );

        return new JsonModel($response);
    }

    /**
     * @return ViewModel
     */
    public function renderContactPageHeaderButtonSaveAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contacts_save_contact_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * Render Contact Page Content
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabInformationAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAddressAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $reload = $this->params()->fromQuery('reload', false);
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->reload = $reload;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAssociationAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $activateTab = $this->params()->fromQuery('activateTab', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->activateTab = $activateTab ? 'active' : '';
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabInformationHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderContactPageContentTabInformationHeaderStatusAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        $status = '';
        if (!empty($contactId))
        {
            // Getting Client Data from Client Service
            $melisComClientService = $this->getServiceManager()->get('MelisComContactService');
            $contactData = $melisComClientService->getContactById($contactId);
            // Assigning Data to init the Client Status Swithc plugin
            $status = ($contactData->cper_status) ? 'checked' : '';
        }

        $view = new ViewModel();
        $view->status = $status;
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabInformationContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        // Getting  Contact Form form Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $contactForm = $factory->createForm($appConfigForm);
        $contactForm->setAttribute('name', $contactId.'_contactForm');
        $contactForm->setAttribute('id', $contactId.'_contactForm');

        $translator = $this->getServiceManager()->get('translator');

        if(!empty($contactId)) {
            $melisComContactService = $this->getServiceManager()->get('MelisComContactService');
            $contactData = $melisComContactService->getContactById($contactId);
            if(!empty($contactData)) {
                $contactForm->setData((array)$contactData);

                $contactForm->get('cper_password')->setValue('');//set empty value on the password
                $contactForm->get('cper_confirm_password')->setValue('');//set empty value on the password

                if($contactData->cper_type == 'company'){
                    $contactForm->get('cper_firstname')->setLabel($translator->translate('tr_meliscommerce_contact_common_company'));
                }
            }
        }

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $contactId;
        $view->contactForm = $contactForm;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAddressHeaderAction()
    {
        $tabId = md5(date('YmdHis'));

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->tabId = $tabId;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAddressHeaderButtonAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contact_page_content_tab_address_header_button_ad');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAddressContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        // Getting Client Contact Address form from Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);

        $clientService = $this->getServiceManager()->get('MelisComClientService');
        $contactAddresses = $clientService->getClientAddressesByClientPersonId($contactId);

        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contact_page_content_tab_address_header_button_delete_address');

        $view = new ViewModel();
        $view->form = $propertyForm;
        $view->addresses = $contactAddresses;
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * This method add Contact Address
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function addContactAddressAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_clients_add_contact_address');
        $textMessage = 'tr_meliscommerce_contact_page_content_tab_address_save_failed';
        $errors = array();

        $request = $this->getRequest();

        if($request->isPost())
        {
            // Geting Client Address from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);

            // Getting Data from Post and set data to Client Address Form
            $postValues = $request->getPost()->toArray();
            $propertyForm->setData($postValues);
            // Getting Client Address form elements/fields
            $appConfigFormElements = $appConfigForm['elements'];

            // Checking if Data setted to Form is valid
            if ($propertyForm->isValid())
            {
                if($postValues['contactId']) {
                    // Getting Validated Data from Form
                    $data = $propertyForm->getData();
                    $clientService = $this->getServiceManager()->get('MelisComClientService');
                    //set person id
                    $data['cadd_client_person'] = $postValues['contactId'];
                    $data['cadd_client_id'] = 0;
                    $res = $clientService->saveClientAddress($data);
                    if ($res) {
                        $success = 1;
                        $textMessage = 'tr_meliscommerce_contact_page_content_tab_address_save_success';
                    }
                }else{
                    $errors = $propertyForm->getMessages();
                }
            }
            else
            {
                $errors = $propertyForm->getMessages();
            }

            // Preparing Error message if error occured
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigFormElements as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                    {
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors
        );

        return new JsonModel($response);
    }

    /**
     * Render Client Contact Address Form modal
     * This method return Client Contact Address Form
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactModalContactAddressFormAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId');
        $tabId = $this->params()->fromQuery('tabId');

        // Getting Client Contact Address Form from Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_addresses_form','meliscommerce_clients_addresses_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tabId = $tabId;
        $view->setVariable('meliscommerce_clients_addresses_form', $propertyForm);
        $view->contactId = ($contactId) ? $contactId : 0;
        return $view;
    }

    /**
     * @return JsonModel
     */
    public function deleteContactAddressAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_contact_page_content_tab_address_delete');
        $textMessage = 'tr_meliscommerce_contact_page_content_tab_address_delete_failed';
        $errors = array();

        $request = $this->getRequest();

        $addressId = $request->getPost('addressId', null);
        $contactId = $request->getPost('contactId', null);

        if($request->isPost())
        {
            if(!empty($addressId)) {
                $clientService = $this->getServiceManager()->get('MelisComClientService');
                $res = $clientService->deleteClientAddress($addressId);
                if($res){
                    $success = 1;
                    $textMessage = 'tr_meliscommerce_contact_page_content_tab_address_delete_success';
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $translator->translate($textMessage),
            'errors' => $errors,
            'contactId' => $contactId
        );

        return new JsonModel($response);
    }

    /**
     * @return JsonModel
     */
    public function deleteContactAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_contact_delete_contact_title');
        $textMessage = 'tr_meliscommerce_contact_delete_contact_message_failed';
        $errors = array();

        $request = $this->getRequest();
        $contactId = $request->getPost('contactId', null);

        if($request->isPost())
        {
            if(!empty($contactId)) {
                $contactService = $this->getServiceManager()->get('MelisComContactService');
                $res = $contactService->deleteContact($contactId);
                if($res){
                    $success = 1;
                    $textMessage = 'tr_meliscommerce_contact_delete_contact_message_success';
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $translator->translate($textMessage),
            'errors' => $errors,
            'contactId' => $contactId
        );

        $this->getEventManager()->trigger('meliscommerce_contact_delete_end',
            $this, array_merge($response, array('typeCode' => 'ECOM_CONTACT_DELETE', 'itemId' => $contactId)));

        return new JsonModel($response);
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAssociationHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAssociationHeaderAddAccountAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contact_page_content_tab_association_header_add_account');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAssociationContentAction()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_contact_associated_account_list');

        $contactId = $this->params()->fromQuery('contactId', '');

        // DataTable costume configuration
        $columns = $melisTool->getColumns();
        $translator = $this->getServiceManager()->get('translator');
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_clients_common_label_action'));

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->contactId = $contactId;
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$contactId.'_contactAssocAccountList', null, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactAssocAccountListTableSearchAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactAssocAccountListTableLimitAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactAssocAccountListTableRefreshAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactAssocAccountListTableEditAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactAssocAccountListTableSetDefaultAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contacts_set_default_account_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactAssocAccountListTableUnlinkAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();

        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contacts_unlink_account_button');

        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * @return JsonModel
     */
    public function getContactAssociatedAccountListAction()
    {
        $dataCount = 0;
        $draw = 0;
        $tableData = array();

        if($this->getRequest()->isPost())
        {
            $contactId = $this->getRequest()->getPost('contactId', null);

            $melisTool = $this->getServiceManager()->get('MelisCoreTool');
            $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_contact_associated_account_list');

            $colId = array_keys($melisTool->getColumns());

            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];

            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];

//            if($selCol == 'default_contact')
//                $selCol = null;
            if($selCol == 'default_account')
                $selCol = 'cpr_default_client';

            $draw = $this->getRequest()->getPost('draw');

            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');

            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];

            $contactService = $this->getServiceManager()->get('MelisComContactService');
            $clientService = $this->getServiceManager()->get('MelisComClientService');

            $tableData = $contactService->getContactAssocAccountLists($contactId, $search, $melisTool->getSearchableColumns(), $start, $length, $selCol, $sortOrder)->toArray();
            $dataCount = $contactService->getContactAssocAccountLists($contactId, $search, $melisTool->getSearchableColumns(), null, null, null, 'ASC', true)->current();

            $contactStatus = '<i class="fa fa-circle text-danger"></i>';
            foreach ($tableData as $key => $val)
            {
                $isDefault = '';
                // Generating contact status html form
                if ($val['cli_status'])
                {
                    $contactStatus = '<i class="fa fa-circle text-success"></i>';
                }
                if($val['cpr_default_client']){
                    $isDefault = '<i class="fa fa-star fa-2x"></i>';
                }


                $tableData[$key]['cli_status'] = $contactStatus;
                //check if this account is the default of this contact
                $tableData[$key]['default_account'] = $isDefault;
                //check if this contact is the default of this account
                $isDefaultAccount = $this->isDefaultContact($val['cli_id'], $contactId);
                $tableData[$key]['default_contact'] = $isDefaultAccount;

                $cliName = $clientService->getAccountName($val['cli_id']);
                $tableData[$key]['cli_name'] = !empty($cliName) ? "<span class='d-none td-tooltip'>".$cliName."</span>".mb_strimwidth($cliName, 0, 30, '...') : null;

                $showUnlinkBtn = 1;
                $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
                $accountSettings = $melisComClientService->getAccountNameSetting();
                if(!empty($accountSettings)){
                    /**
                     * If commerce account settings is set to contact_name,
                     * we show unlink button EXCEPT for the default contact
                     */
                    if($accountSettings->sa_type == 'contact_name'){
                        if($val['cpr_default_client'])
                            $showUnlinkBtn = 0;
                    }
                }

                $tableData[$key]['DT_RowAttr']    = [
                    'data-isdefault' => $val['cpr_default_client'],
                    'data-isdefaultcontact' => !empty($isDefaultAccount) ? 1 : 0,
                    'data-cprid' => $val['cpr_id'],
                    'data-contactid' => $val['cper_id'],
                    'data-showunlink' => $showUnlinkBtn
                ];
            }
        }
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($tableData),
            'recordsFiltered' => $dataCount->totalRecords,
            'data' => $tableData,
        ));
    }

    public function isDefaultContact($accountId, $contactId)
    {
        $accountTable = $this->getServiceManager()->get('MelisEcomClientAccountRelTable');
        $data = $accountTable->getDataByAccountAndContactId($accountId, $contactId)->current();
        if(!empty($data)){
            if($data->car_default_person)
                return '<i class="fa fa-star fa-2x"></i>';
        }
        return '';
    }

    /**
     * Function used for auto suggest
     *
     * @return JsonModel
     */
    public function fetchAllContactAction()
    {
        $searchPhrase = $this->params()->fromQuery('phrase', '');
        $accountId = $this->params()->fromQuery('accountId', '');

        $lists = [];
        if (!empty($searchPhrase)) {
            $contactService = $this->getServiceManager()->get('MelisComContactService');
            $data = $contactService->getContactLists(null, null, 1, $searchPhrase, ['cper_name', 'cper_firstname'], null, null, 'cper_firstname', 'ASC', true)->toArray();

            $personRelTable = $this->getServiceManager()->get('MelisEcomClientAccountRelTable');
            $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
            $accountSettings = $melisComClientService->getAccountNameSetting();

            $linkContacts = [];
            $ids = [];
            if(!empty($accountId)) {
                //fetch first all linked contact
                $linkContacts = $personRelTable->getEntryByField('car_client_id', $accountId)->toArray();
            }else{
                //remove already those selected contact(for creation of account)
                $container = new Container('meliscommerce');
                $selectedContacts = $container['temp-linked-contacts'];

                if (!empty($selectedContacts)) {
                    foreach ($selectedContacts as $k => $v) {
                        $ids[] = $v['cper_id'];
                    }
                }
            }

            if(!empty($accountSettings)){
                /**
                 * If commerce account settings is set to contact name,
                 * we limit the selecting of contact
                 * 1 account = 1 contact
                 */
                if($accountSettings->sa_type == 'contact_name'){
                    $linkContacts = $personRelTable->fetchAll()->toArray();
                }
            }

            if (!empty($linkContacts)) {
                foreach ($linkContacts as $k => $v) {
                    $ids[] = $v['car_client_person_id'];
                }
            }

            /**
             * Exclude contact in the list if it is already selected
             */
            foreach ($data as $key => $val) {
                if (!in_array($val['cper_id'], $ids)) {
                    $lists[] = $val;
                }
            }
        }

        return new JsonModel($lists);
    }

    /**
     * Function used for auto suggest
     *
     * @return JsonModel
     */
    public function fetchAllAccountAction()
    {
        $searchPhrase = $this->params()->fromQuery('phrase', '');
        $contactId = $this->params()->fromQuery('contactId', '');

        $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
        $accountSettings = $melisComClientService->getAccountNameSetting();

        $searchColumn = 'cli_name';
        if(!empty($accountSettings)){
            $type = !(empty($accountSettings)) ? $accountSettings->sa_type : 'manual_input';
            if($type == 'company_name'){
                $searchColumn = 'melis_ecom_client_company.ccomp_name';
            }
        }

        $lists = [];
        if (!empty($searchPhrase)) {
            $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientTable');
            $data = $melisEcomClientPersonTable->getAccountToolList(array(
                'where' => array(
                    'key' => 'cli_name',
                    'value' => $searchPhrase,
                ),
                'order' => array(
                    'key' => 'cli_name',
                    'dir' => 'ASC',
                ),
                'start' => null,
                'limit' => null,
                'columns' => [$searchColumn],
                'date_filter' => array(),
                'groupId' => null,
                'clientStatus' => 1
            ))->toArray();

            if(!empty($contactId)) {
                //fetch first all linked contact
                $personRelTable = $this->getServiceManager()->get('MelisEcomClientPersonRelTable');
                $linkAccounts = $personRelTable->getEntryByField('cpr_client_person_id', $contactId)->toArray();

                $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
                $accountSettings = $melisComClientService->getAccountNameSetting();
                if(!empty($accountSettings)){
                    /**
                     * If commerce account settings is set to contact name,
                     * we limit the selecting of account
                     * 1 account = 1 contact
                     */
                    if($accountSettings->sa_type == 'contact_name'){
                        $linkAccounts = $personRelTable->fetchAll()->toArray();
                    }
                }

                if (!empty($linkAccounts)) {
                    $ids = [];
                    foreach ($linkAccounts as $k => $v) {
                        $ids[] = $v['cpr_client_id'];
                    }
                    /**
                     * Exclude account in the list if it is already selected
                     */
                    foreach ($data as $key => $val) {
                        if (!in_array($val['cli_id'], $ids)) {
                            $lists[] = $val;
                        }
                    }
                }else{
                    $lists = $data;
                }
            }else{
                $lists = $data;
            }

            foreach($lists as $key => $account){
                $lists[$key]['cli_name'] = $melisComClientService->getAccountName($account['cli_id']);
            }
        }

        return new JsonModel($lists);
    }

    /**
     * @return JsonModel
     */
    public function linkContactAccountAction()
    {
        $accountId = $this->getRequest()->getPost('accountId', '');
        $contactId = $this->getRequest()->getPost('contactId', '');

        $success = 0;
        $error = [];
        $title = 'tr_meliscommerce_contact_link_account';
        $message = 'tr_meliscommerce_contact_link_account_failed';

        $translator = $this->getServiceManager()->get('translator');
        $contactService = $this->getServiceManager()->get('MelisComContactService');
        if($this->request->isPost()){
            if(!empty($contactId)) {
                if(!empty($accountId)) {//insert new linked contact
                    $res = $contactService->linkAccountContact(['cpr_client_id' => $accountId, 'cpr_client_person_id' => $contactId]);
                    if ($res) {
                        $success = 1;
                        $message = 'tr_meliscommerce_contact_link_account_success';
                    }
                }
            }
        }

        return new JsonModel([
            'success' => $success,
            'accountId' => $accountId,
            'error' => $error,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message)
        ]);
    }

    /**
     * @return JsonModel
     */
    public function updateDefaultAccountAction()
    {
        $cpr_default_client = $this->getRequest()->getPost('cpr_default_client', 0);
        $cprId = $this->getRequest()->getPost('cprId', '');
        $contactId = $this->getRequest()->getPost('contactId', '');

        $success = 0;
        $error = [];
        $title = ($cpr_default_client) ? 'tr_meliscommerce_contact_set_default' : 'tr_meliscommerce_contact_remove_default';
        $message = ($cpr_default_client) ? 'tr_meliscommerce_contact_set_default_account_failed' : 'tr_meliscommerce_contact_remove_default_account_failed';

        $translator = $this->getServiceManager()->get('translator');
        $contactRelTable = $this->getServiceManager()->get('MelisEcomClientPersonRelTable');
        if($this->request->isPost()){
            if(!empty($cprId)) {
                if($cpr_default_client)
                    //remove first the current default account
                    $contactRelTable->update(['cpr_default_client' => 0], 'cpr_client_person_id', $contactId);
                //set the new default account
                $res = $contactRelTable->save(['cpr_default_client' => $cpr_default_client], $cprId);
                if ($res) {
                    $success = 1;
                    $message = ($cpr_default_client) ? 'tr_meliscommerce_contact_set_default_account_success' : 'tr_meliscommerce_contact_remove_default_account_success';
                }
            }
        }

        return new JsonModel([
            'success' => $success,
            'error' => $error,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message)
        ]);
    }

    /**
     * @return JsonModel
     */
    public function unlinkAccountContactAction()
    {
        $accountId = $this->getRequest()->getPost('accountId', '');
        $contactId = $this->getRequest()->getPost('contactId', '');

        $success = 0;
        $error = [];
        $title = 'tr_meliscommerce_contact_unlink_account';
        $message = 'tr_meliscommerce_contact_unlink_account_failed';
        $accountName = '';

        $translator = $this->getServiceManager()->get('translator');
        $contactService = $this->getServiceManager()->get('MelisComContactService');
        $clientService = $this->getServiceManager()->get('MelisComClientService');
        if($this->request->isPost()){
            $res = $contactService->unlinkAccountContact($accountId, $contactId);
            //unlink also the data in account
            $resAccount = $clientService->unlinkAccountContact($accountId, $contactId);
            if($res && $resAccount){
                $success = 1;
                $message = 'tr_meliscommerce_contact_unlink_account_success';

                $accountName = $clientService->getAccountName($accountId);
            }
        }

        return new JsonModel([
            'success' => $success,
            'accountId' => $accountId,
            'contactId' => $contactId,
            'accountName' => $accountName,
            'error' => $error,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message)
        ]);
    }

    /**
     * renders the client list modal container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactListModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(true);
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderContactListContentExportContactsFormAction()
    {
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_contact/meliscommerce_contact_list_export_contacts_form','meliscommerce_contact_list_export_contacts_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $contactExportForm = $factory->createForm($appConfigForm);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_contact_list_export_contacts_form', $contactExportForm);
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactListTableExportAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contacts_list_export_contact_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
        return $view;
    }

    /**
     * Function to export accounts
     *
     * @return HttpResponse|string
     */
    public function exportContactsAction()
    {
        ini_set('max_execution_time', -1);
        // set memory limit to infinte
        ini_set('memory_limit', '-1');
        $queryData = $this->request->getQuery()->toArray();

        $translator = $this->getServiceManager()->get('translator');
        $melisComClientService = $this->getServiceManager()->get('MelisComClientService');
        $melisTranslation = $this->getServiceManager()->get('MelisCoreTranslation');
        $langTable = $this->getServiceManager()->get('MelisEcomLangTable');
        $civilityTable = $this->getServiceManager()->get('MelisEcomCivilityTransTable');

        // Get the locale used from meliscore session
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        $langId = $container['melis-lang-id'];

        $delimiter = $queryData['separator'] ?? ';';
        $accountId = $queryData['accountId'] ?? null;
        $search = $queryData['search'] ?? null;
        $status = $queryData['status'] ?? null;
        $type = $queryData['type'] ?? null;

        $fileName = date('Ymd').'_'.strtolower($translator->translate('tr_meliscommerce_contact')).'.csv';

        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_contact_list');

        $personTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
        $tableData = $personTable->getContactLists($accountId, $type, $status, $search, $melisTool->getSearchableColumns(), null, null, 'cper_id', 'ASC', true)->toArray();

        $data = [];
        //loop through each to modify or add new data
        if(!empty($tableData)){
            foreach($tableData as $key => $val){
                //get client name
                $tableData[$key]['cli_name'] = $melisComClientService->getAccountName($val['cli_id']);
                //format date
                $accountCreated = !empty($val['cper_date_creation'])? mb_substr($melisTool->formatDate(strtotime($val['cper_date_creation'])), 0, 10) : '';
                $tableData[$key]['cper_date_creation'] = $accountCreated;
                //get language name
                if(!empty($val['cper_lang_id'])){
                    $langData = $langTable->getEntryById($val['cper_lang_id'])->current();
                    $tableData[$key]['cper_lang_id'] = $langData->elang_name ?? null;
                }
                //get civility
                //get contact civility
                $cvName = null;
                $civility = $civilityTable->getCivilityTransByCivilityId($val['cper_civility'], $langId)->current();
                if(!empty($civility))
                    $cvName = $civility->civt_min_name;

                $tableData[$key]['cper_civility'] = $cvName;
                $tableData[$key]['cper_status'] = ($tableData[$key]['cper_status']) ? $translator->translate('tr_meliscommerce_client_status_active') : $translator->translate('tr_meliscommerce_client_status_inactive');

                //remove confidential fields
                unset($tableData[$key]['cper_password']);
                unset($tableData[$key]['cper_password_recovery_key']);
                //useless field
                unset($tableData[$key]['cper_is_main_person']);
                unset($tableData[$key]['cper_date_edit']);
                unset($tableData[$key]['DT_RowId']);
                unset($tableData[$key]['cper_client_id']);
                unset($tableData[$key]['cgroup_name']);
                unset($tableData[$key]['cpr_default_client']);
            }

            //now we include contact accounts
            $contactAccounts = [];
            foreach($tableData as $key => $val){
                //lets include billing address
                $this->processContactAccounts($contactAccounts, $key, $val['cper_id']);
            }

            /**
             * This will match keys for every data
             * For example if field 1 doesn't exist in array 1, it will put field 1 in array 1 to match all the keys
             */
            $keys = [];
            $this->matchKeys($keys, $contactAccounts);

            $tableData = ArrayUtils::merge($tableData, $contactAccounts, true);
            $tableData = $this->processKeysToMatch($keys, $tableData);

            $exportData = [];
            foreach($tableData as $key => $val){
                $dt = [];
                foreach($val as $k => $d){
                    if(strpos($k, 'translated_') !== false)
                        $fname = str_replace('translated_', '', $k);
                    else
                        $fname = $translator->translate('tr_contact_export_col_'.$k);


                    $dt["$fname"] = $d;
                }
                $exportData[$key] = $dt;
            }
            $data = $exportData;
        }
        $data = $this->mbEncode($data);

        return $this->executeCompanyContactExport($data, $fileName, $delimiter);
    }

    private function processContactAccounts(&$contactData, $key, $contactId)
    {
        $translator = $this->getServiceManager()->get('translator');
        $contactService = $this->getServiceManager()->get('MelisComContactService');
        $clientService = $this->getServiceManager()->get('MelisComClientService');

        $contactDatas = $contactService->getContactAssocAccountLists($contactId)->toArray();

        $datas = [];
        foreach ($contactDatas As $aVal)
        {
            array_push($datas, $aVal);
        }

        $contactCount = 1;
        $type = $translator->translate('tr_meliscommerce_clients_common_label_client');
        foreach($datas as $i => $val){
            foreach($val as $k => $v){
                if(!in_array($k, ['cli_id'])) {
                    $cliName = $clientService->getAccountName($val['cli_id']);

                    $contactData[$key]['translated_'.$type . ' ' . $contactCount] = $cliName;
                }
            }
            $contactCount++;
        }
    }

    public function matchKeys(&$keys, $data)
    {
        foreach($data as $i => $val){
            foreach($val as $k => $v){
                if(!array_key_exists($k, $keys)){
                    $keys[$k] = null;
                }
            }
        }
        return $keys;
    }

    public function processKeysToMatch($keys, $data)
    {
        foreach($data as $i => $val){
            foreach($keys as $k => $b){
                if(!array_key_exists($k, $val)){
                    $data[$i][$k] = null;
                }
            }
        }
        return $data;
    }

    /**
     * applied utf8_encode
     */
    public function mbEncode($data)
    {
        $coreTool = $this->getServiceManager()->get('MelisCoreTool');
        $newData = [];
        if (! empty($data)) {
            foreach ($data as $idx => $val) {
                foreach (array_keys($val) as $key) {
                    $tmp = $val[$key];
                    // encode utf8_encode
                    $newData[$idx][$coreTool->iso8859_1ToUtf8($key)] = !empty($tmp) ? $tmp : '';

                }
            }
        }

        return $newData;
    }

    /**
     * @param $data
     * @param $fileName
     * @param null $customSeparator
     * @param null $customIsEnclosed
     * @return HttpResponse|string
     */
    public function executeCompanyContactExport($data, $fileName, $customSeparator = null, $customIsEnclosed = null)
    {
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $coreTool = $this->getServiceManager()->get('MelisCoreTool');

        $csvConfig = $melisCoreConfig->getItem('meliscore/datas/default/export/csv');
        $separator = empty($customSeparator) ? $csvConfig['separator'] : $customSeparator;

        if($customIsEnclosed != null)
            $enclosed = $customIsEnclosed == 0 ? '' : '"';
        else
            $enclosed = $csvConfig['enclosed'];

        $striptags = (int) $csvConfig['striptags'] == 1 ? true : false;
        $response = '';

        if ($data) {
            $csvColumn = $data[0];

            $content = '';

            // for columns
            foreach ($csvColumn as $key => $colText) {
                $content .= $key . $separator;
            }
            $content .= "\r\n";

            // for contents
            foreach ($data as $dataKey => $dataValue) {

                foreach ($dataValue as $key => $value) {

                    if ($striptags) {
                        $value = $coreTool->iso8859_1ToUtf8($value);
                    } else {
                        if (is_int($value)) {
                            $value = (string) $value;
                            $value = $coreTool->iso8859_1ToUtf8($value);
                        }
                    }

                    /**
                     * to solve the issue of 1 double quoute is to replace it with 2 double quote
                     * so that it will not break the csv file
                     */
                    $value = str_replace('"', '""', $value);
                    $value = str_replace(array("\r", "\n"), '', $value);
                    // content
                    $content .= $enclosed . $value . $enclosed . $separator;

                }
                $content .= "\r\n";
            }

            $response = new Response();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'text/csv; charset=utf-8');
            $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"" . $fileName . "\"");
            $headers->addHeaderLine('Accept-Ranges', 'bytes');
            $headers->addHeaderLine('Content-Length', strlen($content));
            $headers->addHeaderLine('fileName', $fileName);
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * @return ViewModel
     */
    public function renderContactListContentImportContactsFormAction()
    {
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_contact/meliscommerce_contact_list_import_contacts_form','meliscommerce_contact_list_import_contacts_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $contactImportForm = $factory->createForm($appConfigForm);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_contact_list_import_contacts_form', $contactImportForm);
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderAccountContactListTableImportAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        //get user rights
        $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
        $xmlRights = $melisCoreAuth->getAuthRights();
        $rights = $this->getServiceManager()->get('MelisCoreRights');
        $canAccess = $rights->isAccessible($xmlRights, 'meliscore_interface', 'meliscommerce_contacts_list_import_contact_button');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->canAccess = $canAccess;
        return $view;
    }

    public function importContactsAction()
    {
        $success = 0;
        $message = 'tr_meliscommerce_contact_import_failed';
        $title = 'tr_meliscommerce_contact_import_title';
        $errors = [];
        $overrideExistingRecord = false;
        $request = $this->getRequest();
        $translator = $this->getServiceManager()->get('translator');

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();

            $overrideExistingRecord = $post['overrideExistingRecord'] ?? false;

            $contactService = $this->getServiceManager()->get('MelisComContactService');

            $file = $this->params()->fromFiles('contact_file');
            //check if file is csv
            $filename = $file['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($ext == 'csv') {
                $csvDefaultDelimiter = $this->getCsvDelimiter($file['tmp_name']);
                $delimiter = !empty($post['separator']) ? $post['separator'] : $csvDefaultDelimiter;
                $fileContents = $this->readImportedCsv($file);
                $result = $contactService->importFileValidator($fileContents, $delimiter, $overrideExistingRecord);

//                if (empty($result['errors'])) {
                if ($result['proceedImporting']) {
                    //execute saving records with transactions
                    $adapter = $this->getServiceManager()->get('Laminas\Db\Adapter\Adapter');
                    $con = $adapter->getDriver()->getConnection();//get db driver connection
                    $con->beginTransaction();//begin transaction
                    try {
                        $contactService->importContacts($fileContents, $post, $delimiter, $result['allowOverride']);
                        $con->commit();
                        $success = 1;
                        $message = 'tr_meliscommerce_contact_import_success';
                    } catch (\Exception $ex) {
                        $success = 0;
                        $message = 'tr_meliscommerce_contact_import_failed';
                        $con->rollback();
                    }
                } else {
                    $errors = $result['errors'];
                }
            }else{
                $success = 0;
                $message = 'tr_meliscommerce_contact_common_file_not_csv';
            }
        }

        $response = [
            'success' => $success,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message),
            'errors' => $errors,
            'overrideExistingRecord' => $overrideExistingRecord,
            'typeCode' => 'ECOM_CONTACTS_IMPORT'
        ];

        $this->getEventManager()->trigger('meliscommerce_contacts_import_end', $this, $response);

        return new JsonModel($response);
    }

    /**
     * @return JsonModel
     */
    public function testImportContactsAction()
    {
        $success = 0;
        $message = 'tr_meliscommerce_contact_import_failed';
        $title = 'tr_meliscommerce_contact_import_title';
        $errors = [];
        $overrideExistingRecord = false;
        $allowOverride = false;
        $proceedImporting = false;
        $request = $this->getRequest();
        $translator = $this->getServiceManager()->get('translator');

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();

            $overrideExistingRecord = $post['overrideExistingRecord'] ?? false;

            $contactService = $this->getServiceManager()->get('MelisComContactService');

            $file = $this->params()->fromFiles('contact_file');
            //check if file is csv
            $filename = $file['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($ext == 'csv') {
                $csvDefaultDelimiter = $this->getCsvDelimiter($file['tmp_name']);
                $delimiter = !empty($post['separator']) ? $post['separator'] : $csvDefaultDelimiter;

                $fileContents = $this->readImportedCsv($file);

                $result = $contactService->importFileValidator($fileContents, $delimiter, $overrideExistingRecord);
                if (empty($result['errors'])) {
                    $success = 1;
                    $message = 'tr_meliscommerce_contact_import_test_success';
                } else {
                    $errors = $result['errors'];
                    $success = 0;
                    $message = 'tr_meliscommerce_contact_import_test_failed';
                }

                $allowOverride = $result['allowOverride'];
                $proceedImporting = $result['proceedImporting'];
            }else{
                $success = 0;
                $message = 'tr_meliscommerce_contact_common_file_not_csv';
            }
        }

        $response = [
            'success' => $success,
            'textTitle' => $translator->translate($title),
            'textMessage' => $translator->translate($message),
            'errors' => $errors,
            'overrideExistingRecord' => $overrideExistingRecord,
            'allowOverride' => $allowOverride,
            'proceedImporting' => $proceedImporting,
            'typeCode' => 'ECOM_CONTACTS_IMPORT'
        ];

        return new JsonModel($response);
    }

    /**
     * Function to check the csv delimiter
     *
     * @param string $filePath
     * @param int $checkLines
     * @return string
     */
    public function getCsvDelimiter(string $filePath, int $checkLines = 3): string
    {
        $delimiters =[",", ";", "\t"];

        $default =";";

        if(!empty($filePath)) {
            $fileObject = new \SplFileObject($filePath);
            $results = [];
            $counter = 0;
            while ($fileObject->valid() && $counter <= $checkLines) {
                $line = $fileObject->fgets();
                foreach ($delimiters as $delimiter) {
                    $fields = explode($delimiter, $line);
                    $totalFields = count($fields);
                    if ($totalFields > 1) {
                        if (!empty($results[$delimiter])) {
                            $results[$delimiter] += $totalFields;
                        } else {
                            $results[$delimiter] = $totalFields;
                        }
                    }
                }
                $counter++;
            }
            if (!empty($results)) {
                $results = array_keys($results, max($results));

                return $results[0];
            }
        }

        return $default;
    }

    /**
     * @return JsonModel
     */
    public function validateContactImportsFormAction()
    {
        $success = 0;
        $errors = [];
        $message = '';

        $translator = $this->getServiceManager()->get('translator');
        $request = $this->getRequest();

        //merge the file for validation
        $postData = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_contact/meliscommerce_contact_list_import_contacts_form','meliscommerce_contact_list_import_contacts_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $importsForm = $factory->createForm($appConfigForm);

        //set data to the form
        $importsForm->setData($postData);

        if ($importsForm->isValid()){
            $success = 1;
        }else{
            $errors = $importsForm->getMessages();
            $appConfigForm = $appConfigForm['elements'];

            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigForm as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError &&
                        !empty($valueForm['spec']['options']['label']))
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }

        $result = [
            'title' => $translator->translate('tr_meliscommerce_contact_import_title'),
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
        ];

        return new JsonModel($result);
    }

    /**
     * Reads the imported CSV file
     * Returns null if the file's encoding is not in UTF-8
     * @param null $fileParameters
     * @return array|bool|null|string
     */
    public function readImportedCsv($fileParameters = null)
    {
        $data = array();
        $coreTool = $this->getServiceManager()->get('MelisCoreTool');
        if (!empty($fileParameters['tmp_name'])) {
            $data = file_get_contents($fileParameters['tmp_name']);
            if (!mb_check_encoding($data, 'UTF-8')) {
                //encode data to utf
                $data = $coreTool->iso8859_1ToUtf8($data);
            }
        }

        return $data;
    }

    /**
     * @return HttpResponse|string
     */
    public function downloadImportTemplateAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        $config = $this->getServiceManager()->get('config');
        $coreTool = $this->getServiceManager()->get('MelisCoreTool');
        $dataTemplate = $config['plugins']['meliscommerce']['datas']['import_sample_template'];
        $data = [];
        foreach($dataTemplate as $key => $val){
            foreach($val as $k => $v){
                $name = $coreTool->iso8859_1ToUtf8($translator->translate($k));
                $data[$key][$name] = $v;
            }
        }
        $fileName = $translator->translate('tr_meliscommerce_contact_import_file_title');
        return $this->executeCompanyContactExport($data, $fileName, ';');
    }
}