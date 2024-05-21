<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\Json\Json;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Session\Container;
use MelisCore\Controller\MelisAbstractActionController;
use MelisCommerce\Model\Variant;

class MelisComVariantListController extends MelisAbstractActionController
{
    public function renderVariantListPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the products variants tab table limit filter
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsVariantTabTableLimitAction()
    {
        return new ViewModel();
    }

    /**
     * renders the products variatns tab table search filter
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsVariantTabTableSearchAction()
    {
        return new ViewModel();
    }

    /**
     * renders the products variatns tab table list filter
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsVariantTabTableListAction()
    {
        return new ViewModel();
    }

    /**
     * renders the products variatns tab table Grid filter
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsVariantTabTableGridAction()
    {
        return new ViewModel();
    }

    /**
     * renders the products variatns tab table refresh filter
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsVariantTabTableRefreshAction()
    {
        return new ViewModel();
    }

    /**
     * renders the product variant table's edit button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderToolVariantActionEditAction()
    {
        return new ViewModel();
    }

    /**
     * renders the product variant tables' delete button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderToolVariantActionDeleteAction()
    {
        return new ViewModel();
    }

    /**
     * renders the product variant table's update status button
     * @return ViewModel
     */
    public function renderToolVariantActionUpdateStatusAction()
    {
        return new ViewModel();
    }

    /**
     * renders the products variants tab content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductsPageContentTabVariantContentContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $productId = (int) $this->params()->fromQuery('productId', '');

        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_products');

        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => 'Action', 'width' => '10%');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#' . $productId . '_tableProductVariantList', true, false, array('order' => '[[ 0, "desc" ]]'));

        return $view;
    }

    /**
     * generates the data needed for variant list table
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function renderProductsVariantDataAction()
    {
        $getValues = $this->getRequest()->getQuery()->toArray(); //echo '<pre>'; print_r($this->getRequest()->getPost()); echo '</pre>'; die();
        $productId = $this->getRequest()->getPost('prodId');
        $variantService = $this->getServiceManager()->get('MelisComVariantService');
        $attrSrv = $this->getServiceManager()->get('MelisComAttributeService');
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_products');
        $viewHelperManager = $this->getServiceManager()->get('ViewHelperManager');
        $toolTipTable = $viewHelperManager->get('ToolTipTable');
        $docSvc = $this->getServiceManager()->get('MelisComDocumentService');

        $colId = array();
        $dataCount = 0;
        $draw = 1;
        $tableData = array();
        $ctr = 0;
        $total = 0;

        //table layout
        $attrLayout     = '<span class="btn btn-default cell-val-table" title="%s" style="border-radius: 4px;color: #7D7B7B;">%s</span>';
        $varOnline      = '<span class="text-success var-status-indicator"><i class="fa fa-fw fa-circle"></i></span>';
        $varOffline     = '<span class="text-danger var-status-indicator"><i class="fa fa-fw fa-circle"></i></span>';
        $varMain        = '<span class="text-success"><i class="fa fa-fw fa-star"></i></span>';
        $prodImage      = '<img src="%s" width="60"/>';
        $toolTipTextTag = '<a id="row-%s" class="toolTipVarHoverEvent tooltipTableVar" data-variantId="%s" data-variantname="%s" data-hasqtip="1" aria-describedby="qtip-%s">%s</a>';

        $colId = array_keys($melisTool->getColumns());

        $sortOrder = $this->getRequest()->getPost('order');
        $sortOrder = $sortOrder[0]['dir'];

        $selCol = $this->getRequest()->getPost('order');
        $selCol = $colId[$selCol[0]['column']];

        $draw = (int) $this->getRequest()->getPost('draw');

        $start = (int) $this->getRequest()->getPost('start');
        $length =  (int) $this->getRequest()->getPost('length');

        $search = $this->getRequest()->getPost('search');
        $search = $search['value'];
        if (!empty($selCol)) {
            $order = $selCol . ' ' . $sortOrder;
        } else {
            $order = 'var_main_variant ' . $sortOrder;
        }

        $langId = $this->getTool()->getCurrentLocaleID();
        $variants = Variant::setDataTable(true)
            ->setTooltipService($toolTipTable)
            ->setTooltipColumns($this->getToolTipColumns())
            ->setDocumentService($docSvc)
            ->getProductVariants($productId)->get();

        $pagination = Variant::getProductVariants($productId)->count();


        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsFiltered' => $pagination,
            'recordsTotal' => $variants->count(),
            'data' => $variants,
        ));
    }

    private function isLinux()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'LINX') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * generates the tooltip table
     * @return string[][]
     */
    public function getToolTipColumns()
    {
        $columns = array(
            $this->getTool()->getTranslation('tr_meliscommerce_product_tooltip_col_id') => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:10px;',
            ),
            ' ' => array(),
            $this->getTool()->getTranslation('tr_meliscommerce_product_tooltip_col_image') => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),
            $this->getTool()->getTranslation('tr_meliscommerce_product_tooltip_col_sku') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),

            $this->getTool()->getTranslation('tr_meliscommerce_product_tooltip_col_attributes') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),

            $this->getTool()->getTranslation('tr_meliscommerce_product_tooltip_col_country') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),
            $this->getTool()->getTranslation('tr_meliscommerce_product_tooltip_col_price') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),

            $this->getTool()->getTranslation('tr_meliscommerce_product_tooltip_col_stocks') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:20px;',
            ),

        );

        return $columns;
    }

    public function getToolTipAction()
    {
        $content = array();
        $rows = array();
        $data = array();
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $viewHelperManager = $this->getServiceManager()->get('ViewHelperManager');
        $table = $viewHelperManager->get('ToolTipTable');

        if ($this->getRequest()->isPost()) {
            $variantId = $this->getRequest()->getPost('variantId');
            $sContent = '';
            $variant = $variantSvc->getVariantById($variantId);

            //set general price
            foreach ($variant->getPrices() as $price) {
                if ($price->price_country_id == 0) {
                    $data['price'] = $price->price_net;
                }
            }

            //set general stocks
            foreach ($variant->getStocks() as $stock) {
                if ($stock->stock_country_id == 0) {
                    $data['stocks'] = $stock->stock_quantity;
                }
            }

            if (array_filter($data)) {
                $data['country'] = 'General';
                $rows[] = $data;
            }

            foreach ($countryTable->getCountries() as $country) {
                $data = array();
                //set country price
                foreach ($variant->getPrices() as $price) {
                    if ($price->price_country_id == $country->ctry_id) {
                        $data['price'] = $price->price_net;
                    }
                }

                //set country stocks
                foreach ($variant->getStocks() as $stock) {
                    if ($stock->stock_country_id == $country->ctry_id) {
                        $data['stocks'] = $stock->stock_quantity;
                    }
                }

                if (array_filter($data)) {
                    $data['country'] = $this->getTool()->escapeHtml($country->ctry_name);
                    $rows[] = $data;
                }
            }

            // TBODY START

            foreach ($rows as $row) {

                $tmp = '';
                $tmp .= $table->getBody();
                $tmp .= $table->openTableRow();

                // country
                $tmp .= $table->setRowData($this->getTool()->escapeHtml($row['country']), array('class' => 'center', 'style' => 'width:120px'));

                // price
                $price = isset($row['price']) ? $row['price'] : '';
                $tmp .= $table->setRowData($price, array('class' => 'center', 'style' => 'width:100px'));

                // stocks
                $stocks = isset($row['stocks']) ? $row['stocks'] : '';
                $tmp .= $table->setRowData($stocks, array('class' => 'center', 'style' => 'width:40px'));

                $tmp .= $table->closeTableRow();

                $tmp .= $table->closeBody();

                $content[] = $tmp;
            }
            // TBODY START
        }

        return new JsonModel(array(
            'content' => $content
        ));
    }

    /**
     * Function to update the status of the variant
     *
     * @return JsonModel
     */
    public function updateVariantStatusAction()
    {
        $varTbl = $this->getServiceManager()->get('MelisEcomVariantTable');
        $variantId = (int) $this->getRequest()->getPost('id');
        $status = (int) $this->getRequest()->getPost('var_status');

        $success = false;
        $data = array("var_status" => $status);
        $res = $varTbl->save($data, $variantId);

        if ($res) {
            $success = true;
        }

        return new JsonModel(array("success" => $success));
    }

    private function formatPrice($price)
    {
        $sessionLocale = 'en_EN';
        $container = new Container('meliscore');
        if (!empty($container['melis-lang-locale']))
            $sessionLocale = $container['melis-lang-locale'];

        if ($this->isWindows()) {
            $sessionLocale = substr($sessionLocale, 0, 2);
        }

        setlocale(LC_MONETARY, $sessionLocale);
        $locale = localeconv();

        $curSymbol = trim($locale['currency_symbol']);
        $curSymbolInt = trim($locale['int_curr_symbol']);
        $decimalPoint = trim($locale['mon_decimal_point']);
        $thousandSeparator = trim($locale['mon_thousands_sep']);
        $fmt = new \NumberFormatter($sessionLocale, \NumberFormatter::CURRENCY);
        //$value =  money_format('%.2n', $price); // not windows compatible
        $value = $fmt->formatCurrency($price, $curSymbolInt);

        if (count($value) === 1) {
            $value = $fmt->formatCurrency($price, $curSymbolInt);
        }

        $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value); // replace special characters
        $value = preg_replace('/[a-zA-Z$ ]/', '', $value); // replace $, [space] and alphabets in price

        $newVal = $price;

        if ($decimalPoint) {
            $value = explode($decimalPoint, $value);
            if (is_array($value)) {
                $tmpVal = str_replace($thousandSeparator, '', $value[0]);
                $newVal = $tmpVal . $decimalPoint . $value[1];
            }
        }

        return $newVal;
    }

    private function isWindows()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_variants');

        return $melisTool;
    }
}
