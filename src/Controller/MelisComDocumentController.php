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
use Zend\Validator\File\Size;
use Zend\Validator\File\IsImage;
use Zend\File\Transfer\Adapter\Http;
use Zend\Session\Container;
class MelisComDocumentController extends AbstractActionController
{

    protected $formType;
    protected $relationTypes = array('category', 'product', 'variant', 'order');
    protected $docTypes = array('file', 'image');

    public function renderDocumentImagePluginAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $docRelationData = $this->getDocumentSessionValue();
        $relationId = $docRelationData['docRelationId'];


        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->relationId = $relationId;

        return $view;
    }

    public function renderDocumentImageListsAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $qDocRelationType = $this->params()->fromQuery('docRelationType');
        $qDocRelationId   = $this->params()->fromQuery('docRelationId');

        $docRelationData = $this->getDocumentSessionValue();
        $data  = array();
        $images = array();
        $docRelationType = null;
        $docRelationId   = null;

        if($docRelationData['docRelationType'] && $docRelationData['docRelationId']) {
            $docRelationType = $docRelationData['docRelationType'];
            $docRelationId   = $docRelationData['docRelationId'];
        }
        else {
            $docRelationType = $qDocRelationType;
            $docRelationId   = $qDocRelationId;
        }

        if($docRelationId) {
            $data = $this->getDocSvc()->getDocumentsByRelationAndTypes($docRelationType, $docRelationId, 'IMG');
            if($data) {
                $ctryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
                $countryName = null;
                if($data) {
                    foreach($data as $doc) {
                        $doc = $doc->getArrayCopy();
                        $countryData = $ctryTable->getEntryById($doc['rdoc_country_id'])->current();
                        if($countryData) {
                            $countryName = $countryData->ctry_name;
                        }

                        if($doc['rdoc_country_id'] == '-1') {
                            $countryName = $this->getTool()->getTranslation('tr_meliscommerce_documents_action_all_countries');
                        }
                        $images[] = array_merge($doc, array('country_name' => $countryName));
                    }
                }
            }
        }
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->country = $this->getCountries();
        $view->imageType = $this->getImageTypes();
        $view->images = $images;
        $view->docRelationType = $docRelationData['docRelationType'];
        $view->docRelationId = $docRelationData['docRelationId'];
        return $view;
    }

    public function renderDocumentFilePluginAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $docRelationData = $this->getDocumentSessionValue();
        $relationId = $docRelationData['docRelationId'];

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->relationId = $relationId;

        return $view;
    }

    public function renderDocumentFileListsAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $zoneConfig = $this->params()->fromRoute('zoneconfig', '');
        $qDocRelationType = $this->params()->fromQuery('docRelationType');
        $qDocRelationId   = $this->params()->fromQuery('docRelationId');

        $docRelationData = $this->getDocumentSessionValue();
        $data  = array();
        $files = array();

        $docRelationType = null;
        $docRelationId   = null;

        if($docRelationData['docRelationType'] && $docRelationData['docRelationId']) {
            $docRelationType = $docRelationData['docRelationType'];
            $docRelationId   = $docRelationData['docRelationId'];
        }
        else {
            $docRelationType = $qDocRelationType;
            $docRelationId   = $qDocRelationId;
        }

        $data = $this->getDocSvc()->getDocumentsByRelation($docRelationType, $docRelationId);
        if($data) {
            foreach($data->getDocument() as $doc) {
                if((int) $doc['doc_type_id'] != 1) {
                    $doc['doc_name'] = $this->getTool()->limitedText($doc['doc_name'], 25);
                    $files[] = $doc;
                }
            }
        }

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->files = $files;
        return $view;
    }


    public function renderDocumentGenericModalContainerAction(){
        $id = $this->params()->fromRoute('id', $this->params()->fromQuery('id', ''));
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->melisKey = $melisKey;
        $view->id = $id;
        return $view;
    }

    public function renderDocumentGenericModalFormAction(){
        $typeUpload = $this->params()->fromQuery('typeUpload');
        $relationId = $this->params()->fromQuery('docRelationId');
        $relationType = $this->params()->fromQuery('docRelationType');
        $modalText = '';
        
        $docId = (int) $this->params()->fromQuery('docId');
        $saveType = $this->params()->fromQuery('saveType');

        $translator = $this->getServiceLocator()->get('translator');
        
        $docImageData = array();
        $fileData = array();

        $title = 'tr_meliscommerce_documents_attachment';
        $formUpload = 'meliscommerce_documents_file_upload_form';
        $fileTitle = 'tr_meliscommerce_documents_main_information_upload_file_select';
        
        if($typeUpload=='image'){
            $title = 'tr_meliscommerce_documents_add_image_button';
            $formUpload = 'meliscommerce_documents_image_upload_form';
            $fileTitle = 'tr_meliscommerce_documents_main_information_upload_select_img';
            if($docId) {
                $docImageData = $this->getDocSvc()->getDocumentById($docId);
                $docRelData = (array) $this->getDocSvc()->getDocumentRelationByDocumentId($docId);
                $document = $docImageData->getDocument();
                $docImageData = array_merge($docRelData, $document);
            }
        }
        
        if($typeUpload == 'file') {
            if($docId) {
                $docFileData = $this->getDocSvc()->getDocumentById($docId);
                $docRelData = (array)$this->getDocSvc()->getDocumentRelationByDocumentId($docId);
                $document = $docFileData->getDocument();
        
                $fileData = array_merge($docRelData, $document);
            }
        }
        
        // Category Tree view Search Input
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_documents/'.$formUpload,$formUpload);
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);

        // modal texts
        if($relationType == 'product'){
            // product
            if($typeUpload == 'file'){
                $modalText = 'tr_meliscommerce_documents_modal_text_prod_file';
            }else{
                $propertyForm->get('doc_name')->setOption('tooltip', $translator->translate('tr_meliscommerce_documents_upload_doc_name_image tooltip'));
                $propertyForm->get('doc_subtype_id')->setOption('tooltip', $translator->translate('tr_meliscommerce_documents_main_information_upload_select_type_image tooltip'));
                $propertyForm->get('rdoc_country_id')->setOption('tooltip', $translator->translate('tr_meliscommerce_documents_main_information_update_file_country_image tooltip'));
                
                $modalText = 'tr_meliscommerce_documents_modal_text_prod_image';
            }
        }else{
            // variant
            if($typeUpload == 'file'){
                $modalText = 'tr_meliscommerce_documents_modal_text_var_file';
            }else{
                $modalText = 'tr_meliscommerce_documents_modal_text_var_image';
            }
        }

        // Image Type Form
        $imageTypeFormConfig = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_documents/meliscommerce_documents_image_type_form','meliscommerce_documents_image_type_form');
        $imageTypeForm = $factory->createForm($imageTypeFormConfig);
        $imageTypeForm->setAttribute('data-upload-type', $typeUpload);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_documents_upload_form', $propertyForm);
        $view->title = $title;
        $view->fileTitle = $fileTitle;
        $view->docType = $typeUpload;
        $view->relationId = $relationId;
        $view->relationType = $relationType;
        $view->docId = $docId;
        $view->uploadType = $typeUpload;
        $view->docImageData = $docImageData;
        $view->docFileData = $fileData;
        $view->saveType = $saveType;
        $view->modalText = $modalText;
        $view->setVariable('meliscommerce_documents_image_type_form', $imageTypeForm);

        return $view;
    }

    /**
     * EVENTS
     */

    public function saveDocumentAction()
    {
        $documentId = null;
        $success = 0;
        $responseData = array();
        $errors = array();
        $textMessage = '';
        $textTitle = '';
        $request = $this->getRequest();
        $docType = '';
        $logTypeCode = '';
        $isImgTypeOk = true;
        $isImg = true;
        $melisCoreConfig = $this->getServiceLocator()->get('MelisCoreConfig');
        if($request->isPost()) {

            $this->getEventManager()->trigger('meliscommerce_document_update_'.$docType.'_start', $this, $request->getPost());

            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            $logTypeCode = 'ECOM_'.strtoupper($postValues['docRelationType']).'_'.strtoupper($postValues['docType']);
            if (!empty($postValues['doc_id'])){
                $logTypeCode .= '_UPDATE';
            }else{
                $logTypeCode .= '_ADD';
            }

            if(in_array($postValues['docType'], $this->docTypes)) {
                if(in_array($postValues['docRelationType'], $this->relationTypes)) {
                    $docType   = $postValues['docType'];
                    $docId = (int) $postValues['doc_id'];
                    $relationType = $postValues['docRelationType'];
                    $relationId   = (int) $postValues['relationId'];
                    $countryId = (int) $postValues['rdoc_country_id'];

                    $textTitle = 'tr_meliscommerce_documents_'.$docType.'_attachments';
                    $textMessage = 'tr_meliscommerce_documents_save_fail_need_review';

                    $comMediaDir = 'public/media/commerce/'.$relationType.'/'.$relationId.'/';
                    $comMediaPublicPath = '/media/commerce/'.$relationType.'/'.$relationId.'/';

                    $confDocUpload = $melisCoreConfig->getItem('meliscommerce/conf/documents');
                    $minSize = $confDocUpload['minUploadSize'];
                    $maxSize = $confDocUpload['maxUploadSize'];

                    $uploadedFile = $request->getFiles()->toArray()['doc_path'];
                    $fileName = $uploadedFile['name'];
                    $formattedFileName = $this->getFormattedFileName($fileName);


                    $size = new Size(array(
                        'min'=> $minSize,
                        'max' => $maxSize,
                        'messages' => array(
                            'fileSizeTooBig' => $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_too_big', array($this->formatBytes($maxSize))),
                            'fileSizeTooSmall' => $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_too_small', array($this->formatBytes($minSize))),
                            'fileSizeNotFound' => $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_file_does_not_exists'),
                        )
                    ));

                    $imageValidator = new IsImage(array(
                        'messages' => array(
                            'fileIsImageFalseType' => $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_image_fileIsImageFalseType'),
                            'fileIsImageNotDetected' => $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_image_fileIsImageNotDetected'),
                            'fileIsImageNotReadable' => $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_image_fileIsImageNotReadable'),
                        ),
                    ));

                    // if docType is file
                    $validator = array($size);

                    // if docType is image
                    if($docType == 'image') {
                        $validator = array($size, $imageValidator);

                        $imginfo_array = getimagesize($uploadedFile['tmp_name']);

                        if ($imginfo_array !== false) {
                            $isImg = true;
                            $mime_type = $imginfo_array['mime'];

                            $acceptedImageType = array("image/png","image/gif","image/jpeg","image/jpeg");
                            if(in_array($mime_type,$acceptedImageType)){
                                $isImgTypeOk = true;
                            }else{
                                $isImgTypeOk = false;
                                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_image_fileIsImageFalseType');
                            }

                        }else{
                            $isImg = false;
                            $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_documents_upload_image_fileIsImageNotDetected');
                        }
                    }

                    $adapter = new Http();

                    // validate if form is valid
                    $form = $this->getDocForm($docType);
                    $form->setData($postValues);


                    if($form->isValid()) {
                        if($this->createFolder($relationType, $relationId)) {
                            $data = $form->getData();

                            // do saving
                            $adapter->setValidators($validator, $fileName);
                            if($docType == 'image') {
                                $data['doc_type_id'] = 1;
                            }
                            else {
                                $data['doc_type_id'] = isset($postValues['doc_type_id']) ? $postValues['doc_type_id'] : 0;
                            }
                            // if post doesnt have a doc id value, then it is a new data, then forcily need to have an upload validation
                            if(!$postValues['doc_id']) {
                                if($fileName) {
                                    if($adapter->isValid()) {
                                        if($isImgTypeOk && $isImg){
                                            $adapter->setDestination($comMediaDir);
                                            $savedDocFileName =  'public'.$this->renameIfDuplicateFile($comMediaPublicPath . $fileName);
                                            $adapter->addFilter('File\Rename', array(
                                                'target' => $savedDocFileName,
                                                'overwrite' => true,
                                            ));

                                            // if uploaded successfully
                                            if($adapter->receive()) {
                                                //$data['doc_name'] = !empty($data['doc_name']) ? $this->renameIfDuplicateName($data['doc_name']) : $this->renameIfDuplicateName($formattedFileName);
                                                $fname = pathinfo($fileName, PATHINFO_FILENAME);
                                                $data['doc_name'] = !empty($data['doc_name']) ? $this->renameIfDuplicateName($data['doc_name']) : $fname;
                                                $data['doc_subtype_id'] = isset($postValues['doc_subtype_id']) ? $postValues['doc_subtype_id'] : 0;
                                                $data['doc_path'] = str_replace('public/media/commerce', '/media/commerce', $savedDocFileName);
                                                $documentId = $this->getDocSvc()->saveDocument($relationType, $relationId, $countryId, $data, $docId);
                                                if($documentId) {
                                                    $textMessage = 'tr_meliscommerce_documents_'.$docType.'_save_success';
                                                    $success = 1;
                                                }
                                            }
                                            else {
                                                $textMessage = 'tr_meliscommerce_documents_upload_fail_file';
                                            }
                                        }

                                    }
                                    else {
                                        foreach($adapter->getMessages() as $message) {
                                            $textMessage = $message;
                                        }
                                    }
                                }
                                else {
                                    $textMessage = 'tr_meliscommerce_documents_form_'.$docType.'_type_empty';
                                }

                            }
                            else {
                                // or else just save it, this will be used for updating
                                if($fileName) {
                                    // upload the file
                                    if($adapter->isValid()) {
                                        $adapter->setDestination($comMediaDir);
                                        $savedDocFileName =  'public'.$this->renameIfDuplicateFile($comMediaPublicPath . $fileName);
                                        $adapter->addFilter('File\Rename', array(
                                            'target' => $savedDocFileName,
                                            'overwrite' => true,
                                        ));

                                        // if uploaded successfully
                                        if($adapter->receive()) {
                                            if(empty($data['doc_name'])) {
                                                unset($data['doc_name']);
                                            }
                                            
                                            $documentData = $this->getDocSvc()->getDocumentById($docId)->getDocument();
                                            $fname = pathinfo($fileName, PATHINFO_FILENAME);
                                            //$data['doc_name'] = !empty($data['doc_name']) ? $this->renameIfDuplicateName($data['doc_name']) : $this->getFormattedFileName($adapter->getFileName());
                                            $data['doc_name'] = !empty($data['doc_name']) ? $this->renameIfDuplicateName($data['doc_name']) : $fname;
                                            $data['doc_subtype_id'] = isset($postValues['doc_subtype_id']) ? $postValues['doc_subtype_id'] : 0;
                                            $data['doc_path'] = str_replace('public/media/commerce', '/media/commerce', $savedDocFileName);

                                            $documentId = $this->getDocSvc()->saveDocument($relationType, $relationId, $countryId, $data, $docId);
                                            if($documentId) {
                                                
                                                if (!empty($documentData))
                                                {
                                                    // if the file exists, delete the file after update
                                                    if(file_exists('public'.$documentData['doc_path'])) {
                                                        unlink('public'.$documentData['doc_path']);
                                                    }
                                                }
                                                
                                                $textMessage = 'tr_meliscommerce_documents_'.$docType.'_save_success';
                                                $success = 1;
                                            }
                                        }
                                        else {
                                            $textMessage = 'tr_meliscommerce_documents_upload_fail_file';
                                        }
                                    }
                                    else {
                                        foreach($adapter->getMessages() as $message) {
                                            $textMessage = $message;
                                        }
                                    }
                                }
                                else {
                                    if(empty($data['doc_name'])) {
                                        $docData = $this->getDocSvc()->getDocumentById( (int) $data['doc_id']);
                                        $docData = $docData->getDocument();
                                        $fileName = pathinfo($docData['doc_path'], PATHINFO_FILENAME);
                                        $data['doc_name'] = $fileName;
                                    }

                                    $data['doc_subtype_id'] = isset($postValues['doc_subtype_id']) ? $postValues['doc_subtype_id'] : 0;
                                    $documentId = $this->getDocSvc()->saveDocument($relationType, $relationId, $countryId, $data, $docId);
                                    if($documentId) {
                                        $textMessage = 'tr_meliscommerce_documents_'.$docType.'_update_success';
                                        $success = 1;
                                    }
                                }
                            }
                        }
                        else {
                            $textMessage = 'tr_meliscommerce_documents_upload_path_rights_error';
                        }
                    }
                    else {
                        $errors = $form->getMessages();
                    }

                }

                $appConfigForm = $melisCoreConfig->getItem('meliscommerce/forms/meliscommerce_documents/'.$this->formString($postValues['docType']));
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
        }

        $response = array(
            'success' => $success,
            'type' => $docType,
            'errors' => $errors,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage
        );

        $this->getEventManager()->trigger('meliscommerce_document_save_'.$docType.'_end',
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $documentId)));

        return new JsonModel($response);
    }

    public function getDocFilesAction()
    {
        $success = 0;
        $files = array();

        if($this->getRequest()->isPost()) {
            $relation = $this->getRequest()->getPost('relation');
            $uniqueId = (int) $this->getRequest()->getPost('id');
            if($relation && $uniqueId) {
                $data = $this->getDocSvc()->getDocumentsByRelation($relation, $uniqueId);
                if($data) {
                    foreach($data->getDocument() as $doc) {
                        if((int) $doc['doc_type_id'] != 1) {
                            $files[] = $doc;
                        }
                    }
                    $success = 1;
                }

            }
        }

        return new JsonModel(array(
            'success' => $success,
            'files' => $files
        ));
    }

    public function getDocImagesAction()
    {
        $success = 0;
        $files = array();
        $id = null;
        if($this->getRequest()->isPost()) {
            $relation = $this->getRequest()->getPost('relation');
            $uniqueId = (int) $this->getRequest()->getPost('id');
            $type = $this->getRequest()->getPost('type');
            if($relation && $uniqueId) {
                $id = (int) $uniqueId; //$this->getUniqueId();
                $data = $this->getDocSvc()->getDocumentsByRelationAndTypes($relation, $id, 'IMG');
                $ctryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
                $countryName = null;
                if($data) {
                    foreach($data->getDocument() as $doc) {
                        $countryData = $ctryTable->getEntryById($doc['rdoc_country_id'])->current();
                        if($countryData) {
                            $countryName = $countryData->ctry_name;
                        }

                        if($doc['rdoc_country_id'] == '-1') {
                            $countryName = $this->getTool()->getTranslation('tr_meliscommerce_documents_action_all_countries');
                        }
                        $files[] = array_merge($doc, array('country_name' => $countryName));
                    }
                    $success = 1;
                }
            }
        }

        return new JsonModel(array(
            'success' => $success,
            'images' => $files,
        ));
    }

    public function setUniqueIdAction()
    {
        $id = null;

        if($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getPost('id');
            $container = new Container('meliscommerce');
            $container->uniqueId = $id;
        }

        return new JsonModel(array(
            'id' => $id
        ));
    }

    public function getUniqueIdAction()
    {
        $id = 1;

        if($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getUniqueId();
        }

        return new JsonModel(array(
            'id' => $id
        ));
    }



    public function deleteAction()
    {
        $id = null;
        $success = 0;
        $textTitle = 'tr_meliscommerce_documents_Documents';
        $textMessage = 'tr_meliscommerce_documents_delete_file_fail';


        if($this->getRequest()->isPost()) {
            $this->getEventManager()->trigger('meliscommerce_document_delete_start', $this, $this->getRequest()->getPost());
            $id = (int) $this->getRequest()->getPost('id');
            $uniqueId = (int) $this->getRequest()->getPost('uniqueId');
            $type = $this->getRequest()->getPost('docType');
            $formType = in_array($this->getRequest()->getPost('formType'), $this->relationTypes) ? $this->getRequest()->getPost('formType') : 'undefined';
            $docTable = $this->getServiceLocator()->get('MelisEcomDocumentTable');
            $docRelTable = $this->getServiceLocator()->get('MelisEcomDocRelationsTable');


            if(in_array($type, $this->docTypes)) {
                // check if the path exists
                $fileUploadPath = 'public/media/commerce/'.$formType.'/'.$uniqueId.'/';
                if(file_exists($fileUploadPath)) {
                    if(is_readable($fileUploadPath) && is_writable($fileUploadPath)) {
                        $data = $docTable->getEntryById($id)->current();
                        if($data) {
                            $docName = $data->doc_name;
                            // if the file exists, delete the file
                            if(file_exists($fileUploadPath.$docName)) {
                                unlink($fileUploadPath.$docName);
                            }
                        }
                    }
                    else {
                        $textMessage = 'tr_meliscommerce_documents_delete_'.$type.'_rights_issue';
                    }
                }

                // if not, just delete the db table record
                $this->getDocSvc()->deleteDocument($id);
                $data = $docTable->getEntryById($id)->current();
                if(!$data) {
                    $success = 1;
                    $textMessage = 'tr_meliscommerce_documents_delete_'.$type.'_success';
                }
                else {
                    $textMessage = 'tr_meliscommerce_documents_upload_path_not_exists';
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage
        );
        
        $this->getEventManager()->trigger('meliscommerce_document_delete_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_'.strtoupper($formType).'_'.strtoupper($type).'_DELETE', 'itemId' => $id)));
        
        return new JsonModel($response);
    }

    public function addImageTypeAction()
    {
        $docTypeId = null;
        $success = 0;
        $errors = array();
        $textTitle = 'tr_meliscommerce_documents_image_type_add';
        $textMessage = 'tr_meliscommerce_documents_image_type_add_failed';
        $request = $this->getRequest();
        $typeUpload = $this->params()->fromQuery('typeUpload');

        if($request->isPost()) {
            $this->getEventManager()->trigger('meliscommerce_document_add_image_type_start', $this, $request->getPost());

            $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
            $factory = new \Zend\Form\Factory();
            $formElements = $this->serviceLocator->get('FormElementManager');
            $appTextTypeForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_documents/meliscommerce_documents_image_type_form','meliscommerce_documents_image_type_form');

            $factory->setFormElementManager($formElements);
            $form = $factory->createForm($appTextTypeForm);

            $filter = $form->getInputFilter();
            $filter->remove('dtype_name');
            $filter->remove('dtype_code');
            
            // add update filters
            $customFilters = $melisMelisCoreConfig->getItem('meliscommerce/forms/meliscommerce_documents/custom_filters');
            $filter->add($customFilters[$typeUpload]['dtype_name']);
            $filter->add($customFilters[$typeUpload]['dtype_code']);
            $form->setInputFilter($filter);

            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            $form->setData($postValues);

            if($form->isValid()) {
                $docService = $this->getServiceLocator()->get('MelisComDocumentService');
                $data = $form->getData();
                $data['dtype_parent_id'] = 1; // IMG
                $docTypeId = $docService->saveDocumentType($data['dtype_code'], $data['dtype_name'], $data['dtype_parent_id']);
                if($docTypeId) {
                    $textMessage = 'tr_meliscommerce_documents_image_type_add_success';
                    $success = 1;
                }
            }
            else {
                $errors = $form->getMessages();
            }

            // front-end error display
            $appConfigForm = $melisMelisCoreConfig->getItem('meliscommerce/forms/meliscommerce_documents/meliscommerce_documents_image_type_form');
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

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors
        );

        $this->getEventManager()->trigger('meliscommerce_document_add_image_type_end',  
            $this, array_merge($response, array('typeCode' => 'ECOM_DOCUMENT_IMAGE_TYPE_ADD', 'itemId' => $docTypeId)));
        
        return new JsonModel($response);
    }

    public function addFileTypeAction()
    {
        $docTypeId = null;
        $success = 0;
        $errors = array();
        $textTitle = 'tr_meliscommerce_documents_file_add_banner_title';
        $textMessage = 'tr_meliscommerce_documents_File_type_add_failed';
        $request = $this->getRequest();
        $typeUpload = $this->params()->fromQuery('typeUpload');
        
        if($request->isPost()) {
            $this->getEventManager()->trigger('meliscommerce_document_add_file_type_start', $this, $request->getPost());

            $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
            $factory = new \Zend\Form\Factory();
            $formElements = $this->serviceLocator->get('FormElementManager');
            $appTextTypeForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_documents/meliscommerce_documents_image_type_form','meliscommerce_documents_image_type_form');

            $factory->setFormElementManager($formElements);
            $form = $factory->createForm($appTextTypeForm);
            
            $filter = $form->getInputFilter();
            $filter->remove('dtype_name');
            $filter->remove('dtype_code');
            
            // add update filters
            $customFilters = $melisMelisCoreConfig->getItem('meliscommerce/forms/meliscommerce_documents/custom_filters');
            $filter->add($customFilters[$typeUpload]['dtype_name']);
            $filter->add($customFilters[$typeUpload]['dtype_code']);
            $form->setInputFilter($filter);
           
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            $form->setData($postValues);

            if($form->isValid()) {
                $docService = $this->getServiceLocator()->get('MelisComDocumentService');
                $data = $form->getData();
                $data['dtype_parent_id'] = null;
                $docTypeId = $docService->saveDocumentType($data['dtype_code'], $data['dtype_name'], $data['dtype_parent_id']);
                if($docTypeId) {
                    $textMessage = 'tr_meliscommerce_documents_file_type_add_success';
                    $success = 1;
                }
            }
            else {
                $errors = $form->getMessages();
            }

            // front-end error display
            $appConfigForm = $melisMelisCoreConfig->getItem('meliscommerce/forms/meliscommerce_documents/meliscommerce_documents_image_type_form');
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

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors
        );

        $this->getEventManager()->trigger('meliscommerce_document_add_file_type_end',  
            $this, array_merge($response, array('typeCode' => 'ECOM_DOCUMENT_FILE_TYPE_ADD', 'itemId' => $docTypeId)));
        return new JsonModel($response);
    }

    private function getTool()
    {
        $melisCoreTool = $this->getServiceLocator()->get('MelisCoreTool');

        return $melisCoreTool;

    }

    public function getDocSvc()
    {
        $docService = $this->getServiceLocator()->get('MelisComDocumentService');
        return $docService;
    }

    private function formatBytes($bytes) {
        $size = $bytes;
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return round(number_format($size / pow(1024, $power), 2, '.', ',')) . ' ' . $units[$power];
    }



    private function getUniqueId()
    {
        $container = new Container('meliscommerce');
        if(isset($container->uniqueId)) {
            return $container->uniqueId;
        }
        return null;
    }

    private function getDocForm($formConf)
    {
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_documents/'.$this->formString($formConf),$this->formString($formConf));
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($appConfigForm);

        return $form;
    }

    private function formString($type) {
        switch($type) {
            case 'file' :
                return 'meliscommerce_documents_file_upload_form';
                break;
            case 'image' :
                return 'meliscommerce_documents_image_upload_form';
                break;
        }
    }

    private function getCountries()
    {
        $melisEcomCountryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        $ecomCountries = $melisEcomCountryTable->getCountries()->toArray();
        return $ecomCountries;

    }

    private function getImageTypes()
    {
        $docTypeSvc = $this->getServiceLocator()->get('MelisComDocumentService');
        $ecomImageType = $docTypeSvc->getDocumentTypes(1);
        return $ecomImageType;
    }

    /**
     * Creates a folder inside "public/media/commerce" with full permission (for now)
     * @param String $folderType, enum: category, product, variant
     * @param int $folderId
     * @return bool
     */
    private function createFolder($folderType, $folderId)
    {
        $status = false;
        $path = 'public/media/commerce/'.$folderType.'/'.$folderId;
        if(file_exists($path)) {
            $status = true;
        }
        else {
            $status = mkdir($path, 0777, true);
            $this->createFolder($folderType, $folderId);
        }

        return $status;
    }

    /**
     * Returns the permission numeric mode
     * @param string $path
     * @param bool $changePermission
     * @param number $mode
     * @return \MelisCommerce\Controller\Json|boolean
     */
    private function getPermission($path, $changePermission = false, $mode = 0777)
    {
        $permission = substr(sprintf('%o', fileperms($path)), -4);
        if($changePermission) {
            $this->changeFilePermission($path, $mode);
            // Re-check permission
            $permission = substr(sprintf('%o', fileperms($path)), -4);
        }
        return $permission;
    }

    /**
     * Changes the file permission
     * @param String $path
     * @param int $mode
     * @return Json
     */
    private function changeFilePermission($path, $mode)
    {
        $results = array();
        $success = false;
        if(file_exists($path)) {

            if(!is_writable($path))
                chmod($path, $mode);

            if(!is_readable($path))
                chmod($path, $mode);

            if(is_readable($path) && is_writable($path))
                $success = true;
        }

        return $success;
    }

    public function renameIfDuplicateFile($filePath)
    {
        $docTable = $this->getServiceLocator()->get('MelisEcomDocumentTable');
        $docData = $docTable->getEntryByFieldUsingLike('doc_path', $filePath)->toArray();
        $totalFile = count($docData) ? ' (' .count($docData) . ')' : null;
        $fileDir = pathinfo($filePath, PATHINFO_DIRNAME);
        $fileName = pathinfo($filePath, PATHINFO_FILENAME) . $totalFile;
        // replace space with underscores
        $fileName = str_replace(' ', '_', $fileName);
        $fileExt  = pathinfo($filePath, PATHINFO_EXTENSION) ? '.' . pathinfo($filePath, PATHINFO_EXTENSION) : '';
        $newFilePathAndName = $fileDir . '/'. $fileName . $fileExt;

        return $newFilePathAndName;

    }


    public function setDocFileAction()
    {
        $success = 0;
        $files = array();

        if($this->getRequest()->isPost()) {
            $relation = $this->getRequest()->getPost('relation');
            $uniqueId = (int) $this->getRequest()->getPost('id');
        }

        return new JsonModel(array(
            'success' => $success,
            'files' => $files
        ));
    }

    public function renameIfDuplicateName($name)
    {
//         $docTable = $this->getServiceLocator()->get('MelisEcomDocumentTable');
//         $docData = $docTable->getEntryByFieldUsingLike('doc_name', $name)->toArray();

//         $totalNames = count($docData) ? ' (' .count($docData) . ')' : null;
//        // $formatName = preg_replace('/\(([^)]+)\)/', '', $name);
//         //$newName = $formatName . $totalNames;
//         $newName = $formatName . $totalNames;
        $newName = $name;
        return $newName;
    }

    public function getFormattedFileName($fileName)
    {
        $file = pathinfo($fileName, PATHINFO_FILENAME);
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $file;

        return $newFileName;
    }

    public function testAction()
    {
        $test = 'Pants_icon_57ea5d3489096.png';
        echo $this->renameIfDuplicateName('real deal');
        die;
    }

    public function sessionsAction()
    {
        $container = new Container('meliscommerce');
        //unset($container->platforms);
        print '<pre>';
        print_r($container->getArrayCopy());
        print '</pre>';

        echo $this->getDocumentSessionValue()['docRelationType'];
        die;
    }

    public function clearSessionAction()
    {
        $container = new Container('meliscommerce');
        $container->getManager()->destroy();

        die;
    }

    private function getDocumentSessionValue()
    {
        $container = new Container('meliscommerce');
        if($container) {
            return $container['documents'];
        }

        return array('documents' => array(
            'docRelationType' => null,
            'docRelationId' => null
        ));
    }





}