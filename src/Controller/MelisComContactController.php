<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 6/14/2023
 * Time: 12:20 PM
 */

namespace MelisCommerce\Controller;


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

    public function renderAccountListPageHeaderButtonAddContactAction()
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
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#contactList', null, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }

    /**
     * Function to get contact lists
     * Used in contact tool and contact lists inside the account tool
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
            /**
             * This condition is used in contact tab inside account tool
             */
            $accountId = $this->getRequest()->getPost('clientId', null);
            if($accountId)
                $defaultAccountOnly = false;

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

            $tableData = $contactService->getContactLists($accountId, $search, $melisTool->getSearchableColumns(), $start, $length, $selCol, $sortOrder, $defaultAccountOnly)->toArray();
            $dataCount = $contactService->getContactLists($accountId, $search, $melisTool->getSearchableColumns(), null, null, null, 'ASC', $defaultAccountOnly, true)->current();

            // store fetched data for data modification (if needed)

            $contactStatus = '<i class="fa fa-circle text-danger"></i>';
            foreach ($tableData as $key => $val)
            {
                // Generating contact status html form
                if ($val['cper_status'])
                {
                    $contactStatus = '<i class="fa fa-circle text-success"></i>';
                }

                $tableData[$key]['cper_status'] = $contactStatus;
//                $tableData[$key]['contact_name'] = $val['cper_firstname'].' '.$val['cper_name'];
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
    public function addContactAction()
    {
        $translator = $this->getServiceManager()->get('translator');

        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_clients_add_contact');
        $textMessage = 'tr_meliscommerce_contact_save_failed';
        $errors = array();

        $request = $this->getRequest();

        if($request->isPost())
        {
            // Getting Client Contact Form from Config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_clients/meliscommerce_clients_contact_form','meliscommerce_clients_contact_form');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);
            $clientSvc = $this->getServiceManager()->get('MelisComClientService');

            // Getting the Elements/fields of the Client COntact form
            $appConfigFormElements = $appConfigForm['elements'];

            // Gtting Data from Post and Set as values to the Client Contact Form
            $postValues = $request->getPost()->toArray();
            $propertyForm->setData($postValues);
            $emailList = explode(',', $postValues['emailList']);

            if (! empty($postValues['cper_id'])) {
                // Checking if the Contact Form has data of the password
                if (empty($val['cper_password'])) {
                    // If the existing Contact password empty, this means contact not updating the current password
                    // removing Input from Contact form will also remove from the validation
                    $propertyForm->getInputFilter()->remove('cper_password');
                    $propertyForm->getInputFilter()->remove('cper_confirm_password');
                }

                // email checking
                if (! empty($postValues['cper_email'])) {
                    // check if email is not used twice
                    $personWithSameEmail = $clientSvc->getPersonsByEmail($postValues['cper_email']);

                    foreach ($personWithSameEmail as $mail) {
                        if ($mail['cpmail_cper_id'] != $postValues['cper_id']) {
                            $errors['cper_email'] = [
                                'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                                'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                            ];
                        }
                    }
                }
            } else {
                if (! empty($postValues['cper_email'])) {
                    // check if email is not used twice
                    if (! in_array($postValues['cper_email'], $emailList)) {
                        $emailList[] = $postValues['cper_email'];
                        $personWithSameEmail = $clientSvc->getPersonsByEmail($postValues['cper_email']);

                        if (! empty($personWithSameEmail)) {
                            $errors['cper_email'] = [
                                'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                                'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                            ];
                        }
                    } else {
                        $errors['cper_email'] = [
                            'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                            'emailExist' => $translator->translate('tr_meliscommerce_client_email_not_available'),
                        ];
                    }
                }
            }

            // Cehcking if the Client Contact form is valid after set the Data from Post
            if ($propertyForm->isValid())
            {
                // Getting Validated Data from Client Contact Form
                $data = $propertyForm->getData();
                $service = $this->getServiceManager()->get('MelisComContactService');
                unset($data['cper_confirm_password']);
                $res = $service->saveContact($data);
                if($res) {
                    $success = 1;
                    $textMessage = 'tr_meliscommerce_contact_save_success';
                }
            }
            else
            {
                $textMessage = $translator->translate('tr_meliscommerce_client_Contact_unable_to_add');
                $errors = ArrayUtils::merge($errors, $propertyForm->getMessages());
            }

            // Preparing the error messages if error is occured
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

        if(!empty($errors)){
            $success = 0;
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $translator->translate($textMessage),
            'errors' => $errors
        );

        return new JsonModel($response);
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

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->contactId = $contactId;
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
        $view->clientId = $contactId;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAddressAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $contactId;
        return $view;
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderContactPageContentTabAssociationAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $contactId = $this->params()->fromQuery('contactId', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientId = $contactId;
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
        $view->clientId = $contactId;
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

        $contactForm->remove('cper_password');
        $contactForm->remove('cper_confirm_password');

        $translator = $this->getServiceManager()->get('translator');

        if(!empty($contactId)) {
            $melisComContactService = $this->getServiceManager()->get('MelisComContactService');
            $contactData = $melisComContactService->getContactById($contactId);
            if(!empty($contactData)) {
                $contactForm->setData((array)$contactData);

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
}