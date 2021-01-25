<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Session\Container;
use MelisCore\Controller\MelisAbstractActionController;


class MelisComCategoryListController extends MelisAbstractActionController
{
    /**
     * Render Categories page
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoriesPageAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryListAction(){
    	$melisKey = $this->params()->fromRoute('melisKey', '');
    	$view = new ViewModel();
    	$view->melisKey = $melisKey;
    	return $view;
    }

    public function searchCategoryTreeViewAction($categoryData, $fatherId, $newParent){
        $datas = $categoryData;
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $catData = $melisEcomCategoryTable->getChildrenCategoriesOrderedByOrder($fatherId);
        $catDatas = $catData->toArray();

        if (empty($catDatas)){
            // Parent Category doesn't have yet Children
            $melisEcomCategoryTable->save($datas,$datas['cat_id']);
        }else{
        }
    }

    /**
     * Render Category List Header
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryListHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List Content
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryListContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List Header Add Catalog Button
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryListHeaderAddCatalogAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List Header Add Category Button
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryListHeaderAddCategoryAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List Serch Input
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryListSearchInputAction(){
        // Category Tree view Search Input
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_categories/meliscommerce_categories_search_input','meliscommerce_categories_search_input');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_categories_search_input', $propertyForm);
        return $view;
    }
    
    /**
     * Render Category List Tree View
     * This method also return the list for Commerce Languages
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryListTreeViewAction(){
        
        $melisEcomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');
        $ecomLang = $melisEcomLangTable->langOrderByName();
        $ecomLangData = $ecomLang->toArray(); 
        
        // Get the locale used from meliscore session
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        
        $currentLangData = $melisEcomLangTable->getEntryByField('elang_locale',$locale);
        $currentLangImg = '<i class="fa fa-language"></i>';
        $currentLangName = '';
        $currentLang = $currentLangData->current();
        if (!empty($currentLang)){
            $currentLangName = $currentLang->elang_name;
            $imageData = $currentLang->elang_flag ? '<img src="data:image/jpeg;base64,'. ($currentLang->elang_flag) .'" class="imgDisplay" width="24" height="24"/>' : '<i class="fa fa-language"></i>';
            $currentLangImg  = $imageData;
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->ecomLang = $ecomLangData;
        $view->currentLangLocale = $locale;
        $view->currentLangName = $currentLangName;
        $view->currentLangImg  = $currentLangImg;
        return $view;
    }
    
    /**
     * This method return Datas of the Category Tree view
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function getCategoryTreeViewAction(){
        
        $langLocale = $this->params()->fromQuery('langlocale');
        $selected = $this->params()->fromQuery('selected');
        $openStateParent = $this->params()->fromQuery('openStateParent');
        
        $idAndNameOnly = $this->params()->fromQuery('idAndNameOnly');
        $categoriesChecked = $this->params()->fromQuery('categoriesChecked');

        if (!empty($openStateParent)){
            $openStateParent = explode(',', $openStateParent);
        }
        
        // Getting the Current language
        $melisComCategoryService = $this->getServiceManager()->get('MelisComCategoryService');
        $currentLang = $melisComCategoryService->getEcomLang($langLocale);
        
        // Getting Category Tree View form the Category Service
        $melisComCategoryService = $this->getServiceManager()->get('MelisComCategoryService');
        $categoryListData = $melisComCategoryService->getCategoryTreeview(null, $currentLang->elang_id);

        // Category Tree View Preparation
        $categoryList = $this->prepareCategoryDataForTreeView($categoryListData, $selected, $openStateParent, $idAndNameOnly, $categoriesChecked, $currentLang->elang_id);
        
        return new JsonModel($categoryList);
    }
    
    /**
     * This mothod prepare the the Datas form the Category Service data to array that supported of the JSTree used for 
     * Category Tree View
     * 
     * @param int Array $categoryList list of Categories
     * @param int $selected, Selected node that assign to category bu JsTree Plugin
     * @param array $openedStateParent, this contain Categories Id's that has to be open node after the request responded
     * 
     * @return int Array[]
     */
    public function prepareCategoryDataForTreeView($categoryList, $selected = false, $openedStateParent = array(), $idAndNameOnly = false, $categoryChecked = array(), $langId = null){

        $translator = $this->getServiceManager()->get('translator');
        
        $melisEcomProductCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        $categorySvc = $this->getServiceManager()->get('MelisComCategoryService');
        foreach ($categoryList As $key => $val)
        {
            
            $numProducts = ($idAndNameOnly) ? '' : ' <span title="'.$translator->translate('tr_meliscommerce_categories_list_tree_view_product_num').'">(%s)</span>';
            $numProducts = sprintf($numProducts, $melisEcomProductCategoryTable->getTotalData('pcat_cat_id', $val['cat_id']));
            
            $numProds = $melisEcomProductCategoryTable->getTotalData('pcat_cat_id', $val['cat_id']);
            
            $categoryList[$key]['id'] = $val['cat_id'].'_categoryId';
            
            $checked = false;
            if (!empty($categoryChecked))
            {
                if (in_array($val['cat_id'], $categoryChecked))
                {
                    $checked = true;
                }
            }
            
            // Setting the Status of Category
            if ($val['cat_status'])
            {
                $categoryList[$key]['icon'] = 'fa fa-circle text-success';
            }
            else
            {
                $categoryList[$key]['icon'] = 'fa fa-circle text-danger';
            }
            unset($categoryList[$key]['cat_status']);
            
            // retrieves SEO page id
            $catSeo = $categorySvc->getCategorySeoById($val['cat_id'], $langId);
            $cseoPageId = '';
            foreach ($catSeo As $sVal)
            {
                if ($sVal->eseo_lang_id == $langId)
                {
                    $cseoPageId = $sVal->eseo_page_id;
                    break;
                }
            }
            
            $itemIcon = '';
            $categoryList[$key]['type'] = 'category';
            if ($val['cat_father_cat_id'] == -1)
            {
                $itemIcon = '<i class="fa fa-book"></i>';
                $categoryList[$key]['type'] = 'catalog';
                $categoryList[$key]['text'] = $val['cat_id'].' - '.$categoryList[$key]['text'];
            }
            else
            {
                $categoryList[$key]['text'] = $val['cat_id'].' - '.$categoryList[$key]['text'];
            }
            
            $categoryList[$key]['a_attr'] = array(
                'data-seopage' => $cseoPageId,
                'data-numprods' => $numProds,
                'data-textlang' => $categoryList[$key]['textLang'],
                'data-fathericon' => $itemIcon,
                'data-fathercateid' => $val['cat_father_cat_id'],
            );
            
            unset($categoryList[$key]['cat_father_cat_id']);
            
            $selectedState = false;
            if (!is_null($selected))
            {
                if ($selected==$val['cat_id'])
                {
                    $selectedState = true;
                }
            }
            
            $openState = false;
            if (!empty($openedStateParent))
            {
                if (is_array($openedStateParent))
                {
                    if(in_array($val['cat_id'], $openedStateParent))
                    {
                        $openState = true;
                    }
                }
            }
            
            // Node State
            $categoryList[$key]['state'] = array(
                'opened' => $openState,
                'selected' =>  $selectedState,
                'checked' =>  $checked,
            );
            
            if (!empty($val['children']))
            {
                $categoryList[$key]['children'] = $this->prepareCategoryDataForTreeView($categoryList[$key]['children'], $selected, $openedStateParent, $idAndNameOnly, $categoryChecked, $langId);
                
                /**
                 * Checking if the node children has a Open, Checked, Selected state
                 * the parent will set Open state
                 */
                if (!empty($categoryList[$key]['children']))
                {
                    foreach ($categoryList[$key]['children'] As $cKey => $cVal)
                    {
                        if (!empty($cVal['state']))
                        {
                            if ($cVal['state']['opened'] || $cVal['state']['selected'] ||$cVal['state']['checked'])
                            {
                                $categoryList[$key]['state']['opened'] = true;
                            }
                        }
                    }
                }
            }
        }
        
        return $categoryList;
    }
    
    /**
     * Saving Category Tree View form moving to another parent category
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function saveCategoryTreeViewAction(){
        $translator = $this->getServiceManager()->get('translator');
        
        // Initialize Response Variable into Default Values
        $status  = 0;
        $textMessage = '';
        $textMessage = '';
        $textTitle = '';
         
        $request = $this->getRequest();
         
        if($request->isPost()) {
             
            $datas = $request->getPost()->toArray();
             
            if (!empty($datas)){
                
                $fatherId = $datas['cat_father_cat_id'];
                $old_fatherId = $datas['old_parent'];
                unset($datas['old_parent']);
                
                // Updating the current Parent Category with new Children
                $this->udpateCategoryTreeViewAction($datas, $fatherId, true);
                
                if ($old_fatherId!=$fatherId){
                    // updating Old Parent Category
                    $this->udpateCategoryTreeViewAction($datas, $old_fatherId, false);
                }
                
                $status = 1;
            }
        }
         
        $response = array(
            'success' => $status,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
        );
         
        return new JsonModel($response);
    }
    
    /**
     * Saving Parent Category Children
     * @param int $categoryData, category Data form Post Data
     * @param int $fatherId, the Parent ID of the Category
     * @param boolean $newParent, if true this will update to a new Parent category, otherwise stay on current Parent Id
     */
    public function udpateCategoryTreeViewAction($categoryData, $fatherId, $newParent){
        $datas = $categoryData;
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $catData = $melisEcomCategoryTable->getChildrenCategoriesOrderedByOrder($fatherId);
        $catDatas = $catData->toArray();
        
        if (empty($catDatas)){
            // Parent Category doesn't have yet Children
            $melisEcomCategoryTable->save($datas,$datas['cat_id']);
        }else{
            // Parent Category has already Children
            
            // If Category has a new Parent
            if ($newParent){
                foreach ($catDatas As $key => $val){
                    if ($catDatas[$key]['cat_id']==$datas['cat_id']){
                        // removing duplication of Category ID
                        unset($catDatas[$key]);
                    }
                }
                // Adding to specific index of the result array
                array_splice($catDatas, ($categoryData['cat_order'] - 1), 0, array($categoryData));
            }
            
            // Re-ordering the Children of the Parent Category
            $ctr = 1;
            foreach ($catDatas As $key => $val){
                $catDatas[$key]['cat_order'] = $ctr++;
            }
            
            // Updating  Children of the Parent Category one by one
            foreach ($catDatas As $key => $val){
                // delete  parent cache
                $this->getServiceManager()->get('MelisComCacheService')->deleteCache('category', $val['cat_father_cat_id']);
                // save
                $melisEcomCategoryTable->save($catDatas[$key],$catDatas[$key]['cat_id']);
            }
        }
    }

}
