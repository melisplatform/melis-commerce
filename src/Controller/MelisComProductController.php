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
use MelisCommerce\Model\Product;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComProductController extends MelisAbstractActionController
{
    /**
     * Main container of Product All View
     * @return \Laminas\View\Model\ViewModel
     */

    public function renderProductsPageAction()
    {

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $this->setProductVariables($productId, $this->getTool()->getCurrentLocaleID());

        $container = new Container('meliscommerce');
        $container['documents'] = array('docRelationType' => 'product', 'docRelationId' => $productId);

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * products page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageHeaderContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * products page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextModalCloseAction()
    {
        $productId = $this->params()->fromQuery('productId');
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->close_text = $this->getTool()->getTranslation('tr_meliscommerce_products_text_close');
        $view->melisKey = $melisKey;
        $view->productId = $productId;

        return $view;
    }

    /**
     * products page header Left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageHeaderLeftAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * products page header Right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageHeaderRightAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * products page header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageHeaderTitleAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * Render product save button in header
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageHeaderProductSaveAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * Render product save button in header
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageHeaderProductSaveCancelAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentAction()
    {

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page tabs container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabsAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page generic tab head
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentGenericTabHeadAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * redners the product page tab container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page tab main container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabMainContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page tab main header
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabMainHeaderContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page tab main left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabMainHeaderLeftAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the product page tab main right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabMainHeaderRightAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page tab main header
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabMainHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page tab main header switch
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabMainHeaderSwitchAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page tab main content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabMainContentContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }


    /**
     * renders the product page tab left content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentMainTabLeftContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders  product tab main categories container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabLeftChildContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders product tab main categories header
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabLeftChildHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders product tab main categories header see all button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabCategoriesHeaderAllAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * Render Category list in modal
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabCategoriesModalAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');
        $isFilter = $this->params()->fromQuery('isFilter', false);

        $langTable = $this->getServiceManager()->get('MelisEcomLangTable');
        $langData = $langTable->langOrderByName();
        $recLangData = array();
        foreach ($langData as $data) {
            if ($data->elang_status) {
                $recLangData[] = $data;
            }
        }

        $currentLangName = 'English';
        $locale = 'en_EN';
        $container = new Container('meliscore');
        if ($container) {
            $melisEcomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');
            $locale = $container['melis-lang-locale'];
            $currentLangData = $melisEcomLangTable->getEntryByField('elang_locale', $locale);

            $currentLangName = '';
            $currentLang = $currentLangData->current();
            if (!empty($currentLang)) {
                $currentLangName = $currentLang->elang_name;
            }
        }

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->langData = $recLangData;
        $view->currentLangName = $currentLangName;
        $view->currentLangLocale = $locale;
        $view->isFilter = $isFilter;
        return $view;
    }

    /**
     * redners product tab main categories content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabLeftContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product tab main categories list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabCategoriesListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the products main tab files header attach button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabFilesHeaderAttachAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the products main tab files list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabFilesListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the products main tab attributes content
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabAttributesAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $attributes = $this->getAttributesExceptAttributesOnProductId($productId);
        $this->setProductVariables($productId, $this->getTool()->getCurrentLocaleID());

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->attributes = $attributes;
        return $view;
    }

    public function renderProductsMainTabAttributesContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    public function renderHeaderAddAttributeButtonAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }



    /**
     * renders the product tab images gallery
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabImagesGalleryAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product tab text select text button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextHeaderSelectTextAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * redners the product tab text select language button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextHeaderAddButtonAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page content tab text container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextContentContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product page content tab left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextContentLeftContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the tab text content container for languages
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextLanguagesContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;

        return $view;
    }

    /**
     * renders the tab text content container for each language
     */
    public function renderProductsPageContentTabTextLanguageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $ecomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');
        $ecomLangData = $ecomLangTable->langOrderByName();
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->lang = $ecomLangData;
        return $view;
    }

    /**
     * renders the tab text content right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextContentRightContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product tab text language text field container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextContentLangFormContAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        //         $view->melisKey = $melisKey;
        //         $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product tab text modal form container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextModalFormAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;

        return $view;
    }

    public function renderProductsPageContentTabTextModalFormTextAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $appTextForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_form', 'meliscommerce_product_text_form');

        $factory->setFormElementManager($formElements);
        $productTextForm = $factory->createForm($appTextForm);

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->productTextForm = $productTextForm;
        return $view;
    }

    public function renderProductsPageContentTabTextModalFormTypeTextAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $appTextTypeForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_type_form', 'meliscommerce_product_text_type_form');

        $factory->setFormElementManager($formElements);
        $productTextTypeForm = $factory->createForm($appTextTypeForm);

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->productTextTypeForm = $productTextTypeForm;
        return $view;
    }

    /**
     * renders the products page content tab text content laungauge text fields
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabTextContentLanguageFormAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $ecomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');
        $ecomLangData = $ecomLangTable->langOrderByName();

        $productId = (int) $this->params()->fromQuery('productId', '');
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_form', 'meliscommerce_product_text_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $productTextForm = $factory->createForm($appConfigForm);

        $this->setProductVariables($productId, $this->getTool()->getCurrentLocaleID());

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->lang = $ecomLangData;
        $view->productId = $productId;
        $view->productTextForm = $productTextForm;
        return $view;
    }

    /**
     * renders the product page content Tab price header add country button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabPriceHeaderAddCountryAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the products page tab text left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageTabTextLeftContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product tab price country list container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsTabPriceCountryListContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the product tab price country list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsTabPriceCountryListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $ctyGeneral =   '<li class="nav-tem">
                            <a class="nav-link clearfix" data-bs-toggle="tab" href="#'.$productId.'_productprice-General" data-country="General" aria-expanded="true"><span>General</span>
                                <i class="fa fa-globe"></i>
                            </a>
                        </li>';
        $ctyFormat =    '<li class="nav-item">
                            <a class="nav-link clearfix" data-bs-toggle="tab" href="#%s_productprice-%s" data-country="%s" aria-expanded="true"><span>%s</span>
                                <i class="fa fa-times"></i>
                            </a>
                        </li>';

        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $countries = $countryTable->fetchAll();
        $ctyData[] = $ctyGeneral;
        foreach ($countries as $country) {
            $ctyData[] = sprintf($ctyFormat, $productId, str_replace(' ', '', $country->ctry_name), $country->ctry_name, $country->ctry_name);
        }


        $view = new ViewModel();
        $view->countries = $ctyData;
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the products page tab text right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageTabTextRightContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the products variants tab add variant button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabVariantsHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * renders the products variants tab add variant button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsMainTabLeftContentNoContAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    public function renderProductsPageContentPriceFormAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/melisecommerce_products_price_form', 'melisecommerce_products_price_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $priceForm = $factory->createForm($appConfigForm);

        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $countries = $countryTable->fetchAll();
        $productSvc = $this->getServiceManager()->get('MelisComProductService');
        $priceList = $productSvc->getProductPricesById($productId);
        $data = array();
        $c = 1;
        //set general price
        $data[0]['ctry_name'] = 'General';
        $data[0]['ctry_id'] = 'a0';
        $data[0]['ctry_currency_id'] = 'a0';

        foreach ($priceList as $price) {
            if ($price->price_country_id == 0) {
                $data[0] = array_merge($data[0], (array)$price);
            }
        }

        //set country price
        foreach ($countries as $country) {
            $data[$c] = (array)$country;
            foreach ((array)$priceList as $price) {
                if ($price->price_country_id == $country->ctry_id) {
                    $data[$c] = array_merge($data[$c], (array)$price);
                }
            }
            $c++;
        }
        //         echo '<pre>'; print_r($emptyData); echo '</pre>';die();
        $view = new ViewModel();
        $view->data = $data;
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->priceForm = $priceForm;
        return $view;
    }

    public function renderProductsPageProductReferenceFormAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_products_reference_form', 'meliscommerce_products_reference_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $referenceForm = $factory->createForm($appConfigForm);

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->referenceForm = $referenceForm;
        return $view;
    }

    public function renderProductsFormTextEmptyAction()
    {
        $typeId = (int) $this->params()->fromRoute('textTypeId', '');
        $text = $this->params()->fromRoute('text', '');

        $typeTable = $this->getServiceManager()->get('MelisEcomProductTextTypeTable');
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_form', 'meliscommerce_product_text_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $productTextForm = $factory->createForm($appConfigForm);
        $typeData = $typeTable->getEntryById($typeId)->current();

        $view = new ViewModel();
        $view->productTextForm = $productTextForm;
        $view->typeData = $typeData;
        $view->text = $text;
        return $view;
    }

    public function getEmptyProductTextFormAction()
    {
        $typeId = (int) $this->params()->fromQuery('textTypeId', '');
        $text = $this->params()->fromQuery('text', '');

        $forward = $this->getServiceManager()->get('ControllerPluginManager')->get('forward');
        $module = 'MelisCommerce';
        $controller = 'MelisComProduct';
        $actionView = 'renderProductsFormTextEmpty';

        $viewModel = new ViewModel();
        $viewModel = $forward->dispatch($module . '\\Controller\\' . $controller, array_merge(array('action' => $actionView), array('textTypeId' => $typeId, 'text' => $text)));

        $renderer = $this->getServiceManager()->get('Laminas\View\Renderer\RendererInterface');
        $html = new \Laminas\Mime\Part($renderer->render($viewModel));

        $content = $html->getContent();

        // replace single quote with duoble quote
        $content = (str_replace('\'', '"', $content));

        return new JsonModel(array(
            'content' => $content
        ));
    }

    public function renderProductsInfoContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        return $view;
    }

    /**
     * EVENTS
     */

    /**
     * Adds new product text type in `melis_ecom_product_text_type` table
     * @return \Laminas\View\Model\JsonModel
     */
    public function addProductTextTypeAction()
    {
        $prdTextTypeId = null;
        $success = 0;
        $translator = $this->getServiceManager()->get('translator');
        $textTypeTable = $this->getServiceManager()->get('MelisEcomProductTextTypeTable');
        $errors = array();
        $textTitle = 'tr_meliscommerce_products_text_type';
        $textMessage = 'tr_meliscommerce_product_text_type_update_fail';
        if ($this->getRequest()->isPost()) {

            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $appTextTypeForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_type_form', 'meliscommerce_product_text_type_form');

            $factory->setFormElementManager($formElements);
            $form = $factory->createForm($appTextTypeForm);

            $postValues = $this->getRequest()->getPost()->toArray();
            $form->setData($postValues);

            if ($form->isValid()) {
                $data = $form->getData();
                $id = null;
                $code = $data['ptt_code'];
                $code = $this->getTool()->replaceAccents($code);
                $code = strtoupper(trim(str_replace(' ', '', $code)));
                $code = preg_replace('/\W/', '', $code);
                $prodTextTypeData = $textTypeTable->getEntryByField('ptt_code', $code)->current();
                $prodTextTypeNameData = $textTypeTable->getEntryByField('ptt_name', $data['ptt_name'])->current();
                if ($prodTextTypeData) {
                    $textMessage = 'tr_meliscommerce_product_text_type_add_fail';
                    $errors = array(
                        'ptt_code' => array(
                            'invalidEntry' => $this->getTool()->getTranslation('tr_meliscommerce_product_text_type_add_duplicate'),
                            'label' => $this->getTool()->getTranslation('tr_meliscommerce_product_text_type_code'),
                        ),
                    );
                }

                if ($prodTextTypeNameData) {
                    $textMessage = 'tr_meliscommerce_product_text_type_add_fail';
                    $errors = array(
                        'ptt_name' => array(
                            'invalidEntry' => $this->getTool()->getTranslation('tr_meliscommerce_product_text_type_add_name_duplicate'),
                            'label' => $this->getTool()->getTranslation('tr_meliscommerce_product_text_type_name'),
                        ),
                    );
                }

                if (!$prodTextTypeData && !$prodTextTypeNameData) {
                    $textMessage = 'tr_meliscommerce_product_text_type_add_success';
                    $data['ptt_code'] = $code;
                    $prdTextTypeId = $textTypeTable->save($data);
                    $success = 1;
                }
            } else {
                $errors = $form->getMessages();
            }

            // front-end error display
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getItem('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_type_form');
            $appConfigForm = $appConfigForm['elements'];

            foreach ($errors as $keyError => $valueError) {
                foreach ($appConfigForm as $keyForm => $valueForm) {
                    if (
                        $valueForm['spec']['name'] == $keyError &&
                        !empty($valueForm['spec']['options']['label'])
                    )
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );

        $this->getEventManager()->trigger(
            'meliscommerce_product_add_text_type_end',
            $this,
            array_merge($response, array('typeCode' => 'ECOM_PRODUCT_TEXT_TYPE_ADD', 'itemId' => $prdTextTypeId))
        );

        return new JsonModel($response);
    }

    /**
     * Populates table `melis_ecom_product_text` with the text and the product ID
     * @return \Laminas\View\Model\JsonModel
     */
    public function addProductTextAction()
    {
        $productId = null;
        $success = 0;
        $translator = $this->getServiceManager()->get('translator');
        $textTypeTable = $this->getServiceManager()->get('MelisEcomProductTextTable');
        $ecomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');
        $errors = array();
        $textTitle = 'tr_meliscommerce_products_text_type';
        $textMessage = 'tr_meliscommerce_product_text_type_update_fail';
        if ($this->getRequest()->isPost()) {

            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $factory = new \Laminas\Form\Factory();
            $formElements = $this->getServiceManager()->get('FormElementManager');
            $appTextForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_form', 'meliscommerce_product_text_form');

            $factory->setFormElementManager($formElements);
            $form = $factory->createForm($appTextForm);

            $postValues = $this->getRequest()->getPost()->toArray();
            $productId = $postValues['ptxt_prd_id'];

            $form->setData($postValues);

            if ($form->isValid()) {

                $langData = $ecomLangTable->fetchAll();
                $data = $form->getData();
                foreach ($langData as $lang) {

                    $textTypeTable->save(array(
                        'ptxt_prd_id' => $postValues['ptxt_prd_id'],
                        'ptxt_lang_id' => $lang->elang_id,
                        'ptxt_type' => $postValues['ptxt_type']
                    ));
                }

                $textMessage = 'tr_meliscommerce_product_text_type_update_success';
                $success = 1;
            } else {
                $errors = $form->getMessages();
            }

            // front-end error display
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getItem('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_form');
            $appConfigForm = $appConfigForm['elements'];

            foreach ($errors as $keyError => $valueError) {
                foreach ($appConfigForm as $keyForm => $valueForm) {
                    if (
                        $valueForm['spec']['name'] == $keyError &&
                        !empty($valueForm['spec']['options']['label'])
                    )
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );

        $this->getEventManager()->trigger(
            'meliscommerce_product_add_text_save_end',
            $this,
            array_merge($response, array('typeCode' => 'ECOM_PRODUCT_TEXT_ADD', 'itemId' => $productId))
        );

        return new JsonModel($response);
    }

    /**
     * This function handles all the saving process in Melis Commerce Products
     * @return \Laminas\View\Model\JsonModel
     */
    public function saveProductAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $success = 0;
        $errors = array();
        $textTitle = 'tr_meliscommerce_products_Products';
        $textMessage = 'tr_meliscommerce_products_save_fail';
        $productId = 0;
        $isNew = false;
        $prodName = '';
        $logTypeCode = '';
        $translator = $this->getServiceManager()->get('translator');

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost()->toArray();
            $data = $this->getTool()->sanitizeRecursive($data, array('ptxt_field_short', 'ptxt_field_long'));

            $productId = $data['product'][0]['prd_id'] ? (int) $data['product'][0]['prd_id'] : null;

            if ($productId) {
                $logTypeCode = 'ECOM_PRODUCT_UPDATE';
            } else {
                $logTypeCode = 'ECOM_PRODUCT_ADD';
            }


            $prodName = $data['product'][0]['prd_reference'];
            $mergeProdData = array(
                'prd_date_edit' => date('Y-m-d H:i:s'),
                'prd_user_id_edit' => $this->getTool()->getCurrentUserId(),
            );

            if (!$productId) {
                $mergeProdData = array(
                    'prd_date_creation' => date('Y-m-d H:i:s'),
                    'prd_user_id_creation' => $this->getTool()->getCurrentUserId(),
                );
            }

            $data['product'][0] = array_merge($data['product'][0], $mergeProdData);

            $this->getEventManager()->trigger('meliscommerce_product_save_start', $this, array('data' => $data));

            $container = new Container('meliscommerce');
            $success = 0;
            $errors = array();
            $data = array();

            if (!empty($container['product-tmp-data'])) {
                if (!empty($container['product-tmp-data']['success']))
                    $success = $container['product-tmp-data']['success'];
                if (!empty($container['product-tmp-data']['errors']))
                    $errors = $container['product-tmp-data']['errors'];
                if (!empty($container['product-tmp-data']['datas']))
                    $data = $container['product-tmp-data']['datas'];
            }

            unset($container['product-tmp-data']);
            if ($data) {
                $productId = $data['productId'];
            }

            if ($success) {
                $textMessage = 'tr_meliscommerce_products_save_success';
                $prodName = $this->getProductSvc()->getProductName($productId, $this->getTool()->getCurrentLocaleID());
            }
        }

        //override prd_reference validation translations
        if (isset($errors["prd_reference"])) {
            $errors[$translator->translate('tr_prd_reference')] = $errors["prd_reference"];
            unset($errors['prd_reference']);
        }
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => array('productId' => $productId, 'isNew' => $isNew, 'prodName' => $prodName),
        );

        $this->getEventManager()->trigger(
            'meliscommerce_product_save_end',
            $this,
            array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $productId, ''))
        );

        return new JsonModel($response);
    }

    /**
     * Handles the saving of data in Melis Commerce Table `melis_ecom_product`
     */
    public function saveProductDataAction()
    {
        $requestData = $this->params()->fromRoute('data', $this->params()->fromQuery('data', ''));
        $success = 0;
        $errors = array();
        $data = array();
        $prodClean = array();
        $textClean = array();
        $priceClean = array();
        $attributes = array();
        $categories = array();
        $priceId_arr = array();
        $productPageAssociations = [];

        $seo = array();

        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');

        $prodRefFormConfig = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_products_reference_form', 'meliscommerce_products_reference_form');
        $prodRefForm = $factory->createForm($prodRefFormConfig);

        $prodTextFormConfig = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_products/meliscommerce_product_text_form', 'meliscommerce_product_text_form');
        $prodTextForm = $factory->createForm($prodTextFormConfig);

        $prodPriceFormConfig = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_prices/meliscommerce_prices_form', 'meliscommerce_prices_form');
        $prodPriceForm = $factory->createForm($prodPriceFormConfig);

        $stockAlertFormConfig = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_settings/meliscommerce_settings_alert_form', 'meliscommerce_settings_alert_form');
        $stocksAlertForm  = $factory->createForm($stockAlertFormConfig);

        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        $prodTextTypeTable = $this->getServiceManager()->get('MelisEcomProductTextTypeTable');
        $prdAttrTable = $this->getServiceManager()->get('MelisEcomProductAttributeTable');
        $prodCatTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        $prodTextTable = ($this->getServiceManager()->get('MelisEcomProductTextTable'));
        $categorySvc = $this->getServiceManager()->get('MelisComCategoryService');
        $melisEcomProductCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        $priceTable = $this->getServiceManager()->get('MelisEcomPriceTable');
        $translator = $this->getServiceManager()->get('translator');

        if ($this->getRequest()->isPost()) {

            if ($requestData) {

                foreach ($requestData['product'] as $product) {
                    $prodRefForm->setData($product);
                    if (!$prodRefForm->isValid()) {
                        $prodError = $prodRefForm->getMessages();
                        foreach ($prodError as $keyError => $valueError) {
                            foreach ($prodRefFormConfig['elements'] as $keyForm => $valueForm) {
                                if (
                                    $valueForm['spec']['name'] == $keyError &&
                                    !empty($valueForm['spec']['options']['label'])
                                )
                                    $prodError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                        array_push($errors, $prodError);
                    }
                    $prodClean = $product;
                }

                foreach ($requestData['productTextForm'] as $prodText) {
                    if (isset($prodText['ptxt_type'])) {
                        $ptxt_type = $prodText['ptxt_type'];
                        $ptxt_lang_id = (int) $prodText['ptxt_lang_id'];
                        $ptt_id = $prodText['ptt_id'];
                        $ptt_code = $prodText['ptt_code'];
                        $ptt_name = $prodText['ptt_name'];
                        unset($prodText['ptxt_type']);
                        unset($prodText['ptxt_lang_id']);
                        unset($prodText['ptt_code']);
                        unset($prodText['ptt_name']);
                        unset($prodText['ptt_id']);
                        $prodText = array_filter($prodText);

                        //                         if(!empty($prodText)){
                        $prodText['ptxt_lang_id'] = $ptxt_lang_id;
                        $prodText['ptxt_type'] = $ptxt_type;
                        $prodTextForm->setData($prodText);
                        if ($prodTextForm->isValid()) {
                            $prodText = $prodTextForm->getData();
                            if (empty($ptt_id)) {
                                $textType = [
                                    'ptt_code' => $ptt_code,
                                    'ptt_name' => $ptt_name,
                                ];
                            }
                        }
                        unset($prodText['ptt_code']);
                        unset($prodText['ptt_name']);
                        unset($prodText['ptt_id']);
                        $textClean[] = $prodText;
                        //                         }

                    }
                }
                foreach ($requestData['priceForm'] as $prodPrice) {
                    $tmp = $prodPrice;

                    unset($tmp['price_country_id']);
                    unset($tmp['price_currency']);
                    unset($tmp['price_id']);
                    unset($tmp['price_prd_id']);


                    if (array_filter($tmp)) {
                        $prodPrice['price_country_id'] = (int) $prodPrice['price_country_id'];
                        $prodPrice['price_currency'] = (int) $prodPrice['price_currency'];
                        $prodPriceForm->setData($prodPrice);

                        if (!$prodPriceForm->isValid()) {
                            $prodPriceError = $prodPriceForm->getMessages();
                            foreach ($prodPriceError as $keyError => $valueError) {
                                foreach ($prodPriceFormConfig['elements'] as $keyForm => $valueForm) {
                                    if (
                                        $valueForm['spec']['name'] == $keyError &&
                                        !empty($valueForm['spec']['options']['label'])
                                    )
                                        $prodPriceError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                }
                            }
                            array_push($errors, $prodPriceError);
                        }
                        $prodPrice = array_map(function ($item) {
                            return is_numeric($item) ? $item : NULL;
                        }, $prodPriceForm->getData());
                        $priceClean[] = $prodPrice;
                    } else {
                        if (isset($prodPrice['price_id'])) {
                            array_push($priceId_arr, $prodPrice['price_id']);
                        }
                    }
                }

                if (isset($requestData['attributes'])) {
                    $attributes = $requestData['attributes'];
                }
                if (isset($requestData['categories'])) {
                    $categories = $requestData['categories'];
                }

                // Get Product SEO
                $melisComSeoService = $this->getServiceManager()->get('MelisComSeoService');
                $seoResult = $melisComSeoService->validateSEOData('product', $requestData['product_seo']);

                $seoSuccess =  $seoResult['success'];
                $seoErrors = $seoResult['errors']['seo_errors'];
                $seo = $seoResult['datas']['seo_data'];
                if (!$seoSuccess) {
                    array_push($errors, $seoErrors);
                }

                // Using loop to set data because the index is not 0. not sure if this will always be the case.
                foreach ($requestData['settings_stock_alert'] as $stock) {
                    $stocksAlertForm->setData($stock);
                }

                if (!$stocksAlertForm->isValid()) {

                    $stocksAlertError = $stocksAlertForm->getMessages();

                    foreach ($stocksAlertError as $keyError => $valueError) {
                        foreach ($stockAlertFormConfig['elements'] as $keyForm => $valueForm) {
                            if (
                                $valueForm['spec']['name'] == $keyError &&
                                !empty($valueForm['spec']['options']['label'])
                            )
                                $stocksAlertError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                        }
                    }
                    array_push($errors, $stocksAlertError);
                } else {

                    if (!empty($requestData['recipients'])) {
                        foreach ($requestData['recipients'] as $recipient) {
                            if (!filter_var($recipient['sea_email'], FILTER_VALIDATE_EMAIL)) {
                                if (empty($errors['sea_email'])) {
                                    $errors['sea_email'] = array(
                                        'inValidEmail' => $translator->translate('tr_meliscommerce_settings_save_add_recipients_failed') . ': ' . $recipient['sea_email'],
                                        'label' =>  $translator->translate('tr_meliscommerce_settings_label_recipients')
                                    );
                                } else {
                                    $errors['sea_email']['inValidEmail'] = $errors['sea_email']['inValidEmail'] . ', ' . $recipient['sea_email'];
                                }
                            }
                        }
                    }
                    $product['prd_stock_low'] = $stocksAlertForm->get('sea_stock_level_alert')->getValue();
                    $product['prd_stock_low'] = !empty($product['prd_stock_low']) ? $product['prd_stock_low'] : null;
                }

                $this->checkProductPageAssociations(
                    $requestData['meliscommerce_products_page_associations_form'],
                    $productPageAssociations,
                    $errors
                );
            }
        }

        if (!$errors) {
            $prodTextTable->deleteByField('ptxt_prd_id', (int) $product['prd_id']);
            $prdAttrTable->deleteByField('patt_product_id', $product['prd_id']);

            $delCategories = isset($requestData['delcategories']) && !empty($requestData['delcategories']) ? $requestData['delcategories'] : null;
            if ($delCategories) {
                foreach ($delCategories as $delCat) {
                    $categorySvc->deleteCategoryProduct($delCat['pcat_id']);
                }
            }

            //remove prices if the array is not empty
            if (!empty($priceId_arr)) {
                foreach ($priceId_arr as $price_id) {
                    $priceTable->deleteById($price_id);
                }
            }

            // stopped here, validation and insertion

            $success = $prodSvc->saveProduct($product, $textClean, $attributes, $categories, $priceClean, $seo, (int) $product['prd_id'], $productPageAssociations);

            $data['productId'] = (int) $success;
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );

        return new JsonModel($results);
    }


    /**
     * Returns all the attributes from @private getAttributes function
     * @return \Laminas\View\Model\JsonModel
     */
    public function getAttributesAction()
    {
        $attributes = array();

        if ($this->getRequest()->isXmlHttpRequest()) {
            $attributes = $this->getAttributes();
        }

        return new JsonModel(array('source' => $attributes));
    }

    /**
     * Returns all the data from Melis Attribute Service
     * @return array
     */
    private function getAttributes()
    {
        $attrSvc = $this->getServiceManager()->get('MelisComAttributeService');
        $attrData = $attrSvc->getAttributes();
        $attributes = array();

        foreach ($attrData as $attr) {
            $attr = $attr->getAttribute();
            if (!empty($attr->attr_trans)) {
                $found = false;

                foreach ($attr->attr_trans as $trans) {
                    // check if there is a localized translation
                    if ($this->getTool()->getCurrentLocaleID() == $trans->atrans_lang_id) {
                        $found = true;
                        $attributes[] = array(
                            'id' => $attr->attr_id,
                            'value' => $trans->atrans_name,
                        );
                    }
                }

                // if no localized translation, get the first available translation
                if (!$found) {
                    foreach ($attr->attr_trans as $trans) {
                        $attributes[] = array(
                            'id' => $attr->attr_id,
                            'value' => $trans->atrans_name,
                        );
                        break;
                    }
                }
            } else {
                // if no translations available use the reference
                $attributes[] = array(
                    'id' => $attr->attr_id,
                    'value' => $attr->attr_reference,
                );
            }
        }

        return $attributes;
    }

    public function getAttributesExceptAttributesOnProductIdAction()
    {
        $attributes = array();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $productId = (int) $this->params()->fromQuery('productId');
            $langId = $this->getTool()->getCurrentLocaleID();
            $prodAttrTable = $this->getServiceManager()->get('MelisEcomProductAttributeTable');
            $attrSvc = $this->getServiceManager()->get('MelisComAttributeService');
            $attrData = $attrSvc->getAttributes($this->getTool()->getCurrentLocaleID(), null, null, null);
            $prodAttribData = $prodAttrTable->getEntryByField('patt_product_id', $productId)->toArray();

            foreach ($attrData as $attr) {
                $text = $attrSvc->getAttributeText($attr->getId(), $langId);
                if ($text) {
                    $exist = false;
                    foreach ($prodAttribData as $prodAttr) {
                        if ($attr->getId() == $prodAttr['patt_attribute_id']) {
                            $exist = true;
                            break;
                        }
                    }

                    if (!$exist)
                        array_push($attributes, array('id' => $attr->getId(), 'text' => $text));
                }
            }

            $attributes = array_values($attributes);
        }

        return new JsonModel($attributes);
    }

    protected function getAttributesExceptAttributesOnProductId($productId)
    {
        $attributes = array();

        $langId = $this->getTool()->getCurrentLocaleID();
        $prodAttrTable = $this->getServiceManager()->get('MelisEcomProductAttributeTable');
        $attrSvc = $this->getServiceManager()->get('MelisComAttributeService');
        $attrData = $attrSvc->getAttributes($langId, null, null, null);
        $prodAttribData = $prodAttrTable->getEntryByField('patt_product_id', $productId)->toArray();

        foreach ($attrData as $attr) {
            $text = $attrSvc->getAttributeText($attr->getId(), $langId);
            if ($text) {
                $exist = false;
                foreach ($prodAttribData as $prodAttr) {
                    if ($attr->getId() == $prodAttr['patt_attribute_id']) {
                        $exist = true;
                        break;
                    }
                }

                if (!$exist)
                    array_push($attributes, array('id' => $attr->getId(), 'text' => $text));
            }
        }

        $attributes = array_values($attributes);
        // Sorting Alphabetically attributes text
        if (!empty($attributes)) {
            usort($attributes, function ($a, $b) {
                return strcasecmp($a['text'], $b['text']);
            });
        }

        return $attributes;
    }

    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool($module = 'meliscommerce', $toolKey = 'meliscommerce_products_list')
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey($module, $toolKey);

        return $melisTool;
    }


    /**
     * Returns the Product Service, instead of redeclaring in a function multiple times.
     * @return MelisComProductService
     */
    private function getProductSvc()
    {
        $productSvc = $this->getServiceManager()->get('MelisComProductService');

        return $productSvc;
    }

    private function getProduct($productId, $langId = null, $countryId = null)
    {
        if (is_int($productId) && $productId) {
            $prodSvc = $this->getServiceManager()->get('MelisComProductService');
            $prodData = $prodSvc->getProductById($productId, $langId, $countryId);
            if ($prodData) {
                return $prodData;
            }
        }

        return null;
    }

    public function setAttributeText()
    {
        $attributes = array();

        if ($this->getRequest()->isXmlHttpRequest()) {
            $attributes = $this->setAttributes();
        }

        return new JsonModel(array('source' => $attributes));
    }

    /**
     * Sets the layout variables, that will be used throughout all view files
     * that is under on this Controller
     * @param int $productId
     */
    private function setProductVariables($productId, $langId = null)
    {
        $product = (new Product())
            ->where('prd_id', '=', $productId)
            ->with([
                'productTexts' => function ($query) use ($langId) {
                    $query->select(['melis_ecom_product_text.*', 'melis_ecom_product_text_type.*'])
                        ->leftJoin('melis_ecom_product_text_type', 'ptt_id', '=', 'ptxt_type')
                        ->where('ptxt_lang_id', $langId);
                },
                'categories' => function ($query) use ($langId) {
                    $query->select(['melis_ecom_product_category.*', 'melis_ecom_category_trans.*'])
                        ->leftJoin('melis_ecom_category_trans', 'catt_category_id', '=', 'pcat_cat_id')
                        ->where('catt_lang_id', $langId);
                },
                'attributes' => function ($query) use ($langId) {
                    $query->with(['attribute' => function ($query) use ($langId) {
                        $query->with(['translations' => function ($query) use ($langId) {
                            $query->where('atrans_lang_id', $langId);
                        }]);
                    }]);
                }
            ])
            ->first();



        $prodTextTypeTable = $this->getServiceManager()->get('MelisEcomProductTextTypeTable');
        $prodTexts = $product ? $product->productTexts->toArray() : [];
        $ecomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');

        if (empty($prodTexts)) {
            // set default title prodtext field
            foreach($prodTextTypeTable->fetchAll()->toArray() as $textType)
            {
                if($textType['ptt_name'] == 'Title')
                {
                    foreach($ecomLangTable->fetchAll()->toArray() as $lang)
                    {
                        $textType['ptxt_lang_id'] = $lang['elang_id'];
                        $prodTexts[] = $textType;
                    }
                }
            }
        }

        $layoutVar['product'] = $product ? $product->toArray() : null;
        $layoutVar['prodText'] = $prodTexts;
        $layoutVar['prodCategories'] = $product ? $product->categories->toArray() : [];
        $attributes = [];

        if ($product)
            foreach ($product->attributes->toArray() as $attribute) {
                $attributes[] = $this->flatten($attribute);
            }

        if ($attributes) {
            $layoutVar['prodAttributes'] = $attributes;
        }

        $this->layout()->setVariables(array_merge(array(
            'productId' => $product ? $product->prd_id : $productId,
            'prodName' => $product ? $product->label: '',
        ), $layoutVar));
    }

    private function flatten($array, $depth = INF)
    {
        $result = [];

        foreach ($array as $key => $item) {
            if (!is_array($item)) {
                $result[$key] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : $this->flatten($item, $depth - 1);

                foreach ($values as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    public function getProductTextAction()
    {
        $data = array();
        $success = 0;
        $errors = 0;
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        if ($this->getRequest()->isPost()) {
            $requestData = $this->getRequest()->getPost()->toArray();
            $data = $prodSvc->getProductTextsById($requestData['prodId']);
            $success = 1;
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );

        return new JsonModel($results);
    }

    public function deleteAction()
    {
        $productId = null;
        $success = 0;
        $textTitle = 'tr_meliscommerce_products_Products';
        $textMessage = 'tr_meliscommerce_product_remove_fail';
        $request = $this->getRequest();
        if ($request->isPost()) {

            $this->getEventManager()->trigger('meliscommerce_product_delete_start', $this, $this->getRequest()->getPost());
            $productId = (int) $this->getRequest()->getPost('productId');
            $success = $this->getProductSvc()->deleteProductById($productId);
            if ($success) {
                $textMessage = 'tr_meliscommerce_product_remove_success';
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage
        );

        $this->getEventManager()->trigger(
            'meliscommerce_product_delete_end',
            $this,
            array_merge($response, array('typeCode' => 'ECOM_PRODUCT_DELETE', 'itemId' => $productId))
        );

        return new JsonModel($response);
    }

    public function getProductTextTypeAction()
    {
        $type = null;

        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = (int) $this->getRequest()->getQuery('id');
            $prodTextTypeTable = $this->getServiceManager()->get('MelisEcomProductTextTypeTable');
            $prodTextTypeData  = $prodTextTypeTable->getEntryById($id)->current();
            if ($prodTextTypeData) {
                $type = $prodTextTypeData->ptt_field_type;
            }
        }

        return new JsonModel(array('type' => $type));
    }

    public function getProductCategoryLastOrderNumAction()
    {
        $id = "";
        $order = null;
        if ($this->getRequest()->isXmlHttpRequest()) {

            $catId = (int) $this->params()->fromQuery('catId');
            $prodId = (int) $this->params()->fromQuery('prodId');
            $prodCatTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');


            $prodCatData1 = $prodCatTable->getEntryByField('pcat_prd_id', $prodId);
            foreach ($prodCatData1 as $data) {
                if (($data->pcat_prd_id == $prodId) && ($data->pcat_cat_id == $catId)) {
                    $id = (int) $data->pcat_id;
                    $order = (int) $data->pcat_order;
                }
            }

            if (!$order) {
                $prodCatData = $prodCatTable->getCategoryProductsByCategoryId($catId)->toArray();
                if ($prodCatData) {
                    foreach ($prodCatData as $data) {
                        $order = $data['pcat_order'] + 1;
                    }
                } else {
                    // if not data available, then return 1
                    $order = 1;
                }
            }
        }

        return new JsonModel(array(
            'id' => $id,
            'order' => $order
        ));
    }


    private function cmp($a, $b)
    {
        return strcmp($a->ptt_id, $b->ptt_id);
    }


    /**
     * Product Page modal container
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $melisKey = $this->params()->fromQuery('melisKey');

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->id = $id;
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Function to check if product attribute is being used by it's variant
     * @return JsonModel
     */
    public function checkAttributeOnVariantAction()
    {
        $attr_is_used = false;

        $productId = $this->params()->fromRoute('productId', $this->params()->fromQuery('productId', ''));
        $patt_attr_id = $this->params()->fromRoute('patt_attr_id', $this->params()->fromQuery('patt_attr_id', ''));

        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $attrService = $this->getServiceManager()->get('MelisComAttributeService');
        $variantAttrTable = $this->getServiceManager()->get('MelisEcomProductVariantAttributeValueTable');

        /**
         * get attribute value by attribute id
         */
        $attributeValue = $attrService->getAttributeValuesList($patt_attr_id);
        $attributeValueArr = [];

        for ($x = 0; $x < sizeof($attributeValue); $x++) {
            array_push($attributeValueArr, $attributeValue[$x]->atval_id);
        }

        $langId = $this->getTool()->getCurrentLocaleID();
        $variantsData = $variantSvc->getVariantListByProductId($productId, $langId);
        $variantIds = array();
        //get all the variant id
        foreach ($variantsData as $var) {
            array_push($variantIds, $var->getId());
        }
        //get variant attribute value by variant id
        $varAttr = $variantAttrTable->getVariantAttributeValueIdByVariantId($variantIds)->toArray();
        /**
         * check if attribute is being used by variants
         * by checking if attribute value exist on variant attribute value
         */
        for ($i = 0; $i < sizeof($varAttr); $i++) {
            if (in_array($varAttr[$i]['vatv_attribute_value_id'], $attributeValueArr)) {
                $attr_is_used = true;
                break;
            }
        }

        return new JsonModel(array(
            'attribute_is_used' => $attr_is_used
        ));
    }

    /**
     * Renders the page associations
     * @return ViewModel
     */
    public function renderPageAssociationsAction()
    {
        $productId = (int) $this->params()->fromQuery('productId', '');
        $melisTool = $this->getTool('meliscommerce', 'meliscommerce_products');
        $pageAssociationsForm = $melisTool->getForm('meliscommerce_products_page_associations_form');

        if ($productId != 0) {
            $productPageAssociations = $this->getProductPageAssociations($productId);
            $pageAssociationsForm->setData($productPageAssociations);
        }

        $view = new ViewModel();
        $view->pageAssociationsForm = $pageAssociationsForm;
        return $view;
    }

    private function getProductPageAssociations($productId)
    {
        $productService = $this->getProductSvc();
        return $productService->getProductPageAssociationsByProductId($productId);
    }

    private function checkProductPageAssociations($productPageAssociations, &$data, &$errors)
    {
        $melisTool = $this->getTool('meliscommerce', 'meliscommerce_products');
        $pageAssociationsForm = $melisTool->getForm('meliscommerce_products_page_associations_form');

        foreach ($productPageAssociations as $productPageAssociation) {
            $pageAssociationsForm->setData($productPageAssociation);

            if (!$pageAssociationsForm->isValid()) {
                $formErrors = $this->getFormErrors($pageAssociationsForm);
                array_push($errors, $formErrors);
            } else {
                $data = $productPageAssociation;
            }
        }
    }

    private function getFormErrors($form)
    {
        $formErrors = [];
        foreach ($form->getMessages() as $element => $errors) {
            foreach ($errors as $errorKey => $error) {
                $label = $form->get($element)->getOption('label') ?? '';
                $formErrors[$element][$errorKey] = $error;
                $formErrors[$element]['label'] = $label;
            }
        }

        return $formErrors;
    }
}

