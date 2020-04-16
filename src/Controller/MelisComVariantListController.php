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
use MelisCore\Controller\AbstractActionController;

class MelisComVariantListController extends AbstractActionController
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
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$productId.'_tableProductVariantList', true, false, array('order' => '[[ 0, "desc" ]]'));
    
        return $view;
    
    }
    
    /**
     * generates the data needed for variant list table
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function renderProductsVariantDataAction()
    {
        $getValues = get_object_vars($this->getRequest()->getQuery());//echo '<pre>'; print_r($this->getRequest()->getPost()); echo '</pre>'; die();
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
        if(!empty($selCol)){
            $order = $selCol. ' '. $sortOrder;
        }else{
            $order = 'var_main_variant '. $sortOrder;
        }
        $langId = $this->getTool()->getCurrentLocaleID();
        $total = $variantService->getVariantListByProductId($productId, null, null, null, null, null, null, $search, $order);
        foreach($variantService->getVariantListByProductId($productId, null, null, null, null, $start, $length, $search, $order) as $variantObj){
            
            $variant = $variantObj->getVariant();
            $attributes = '';
            $mainVariant = '';
            $variantStatus = '';
            foreach($variantObj->getAttributeValues() as $attributeValue){
                $attributeText = $attrSrv->getAttributeText($attributeValue->atval_attribute_id, $langId);
                $valCol = 'avt_v_'.$attributeValue->atype_column_value;
                
                //check for attribute value translations
                $foundTrans = false;
                foreach($attributeValue->atval_trans as $valTrans){
                    if($valTrans->avt_lang_id == $langId){
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                    }
                }
                
                //if no corresponding tranlsation get the first available trans
                if(!$foundTrans){
                    foreach($attributeValue->atval_trans as $valTrans){
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                        break;
                    }
                }
                
                //use the attribute value reference as name if no translation
                if(!$foundTrans){
                    $value = $attributeValue->atval_reference;
                }
                
                // edit value before rendering to table if necessary
                switch($valCol){
                    case 'avt_v_datetime': $value = $this->getTool()->dateFormatLocale($value); break;
                    case 'avt_v_text': 
                    case 'avt_v_varchar' : $value = $this->getTool()->limitedText($value,50); break;
                }                   
                
                $attributes .= sprintf($attrLayout, $attributeText, $this->getTool()->escapeHtml($value));
            }
            if($variant->var_status){
                $variantStatus = $varOnline;
            }else{
                $variantStatus = $varOffline;
            }

            //switch for variant status
//            $varStatChk = '<div class="make-switch '.$productId.'_variantStatusChk triggerVarUpdate" data-on-label="'.$this->getTool()->getTranslation('tr_meliscore_common_active').'" data-off-label="'.$this->getTool()->getTranslation('tr_meliscore_common_inactive').'" data-text-label="'.$this->getTool()->getTranslation('tr_meliscommerce_product_list_col_status').'">
//                        <input type="checkbox" '.$variantStatus.' />
//                    </div>';
             
            if($variant->var_main_variant){
                $mainVariant = $varMain;
            }
             
            $toolTipTable->setTable('variantTable'.$variant->var_id, 'table-row-'.($ctr+1), 'border:1px solid;');
            $toolTipTable->setColumns($this->getToolTipColumns());
            
            // Detect if Mobile remove qTipTable
            $useragent=$_SERVER['HTTP_USER_AGENT'];
            if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
                $sku = sprintf($toolTipTextTag, $variant->var_id, $variant->var_id, $this->getTool()->escapeHtml($variant->var_sku), ($ctr+1), $variant->var_sku);
            } else {
                $sku = sprintf($toolTipTextTag, $variant->var_id, $variant->var_id, $this->getTool()->escapeHtml($variant->var_sku), ($ctr+1), $variant->var_sku) . $toolTipTable->render();
            }
            
             
            $imgData = $docSvc->getDocumentsByRelationAndTypes('variant', $variantObj->getId(), 'IMG', array('DEFAULT'));
            $variantimg = '';
            if($imgData) {
                $imgData = $imgData;
                $variantimg = sprintf($prodImage,$imgData[0]->getArrayCopy()['doc_path']); 
            }else{
                $src = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAgAAZABkAAD/7AARRHVja3kAAQAEAAAAPAAA/+EDLWh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8APD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMTQgNzkuMTUxNDgxLCAyMDEzLzAzLzEzLTEyOjA5OjE1ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozRkNFMzU3RDg2QUYxMUU1OEM4OENCQkI2QTc0MTkwRSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozRkNFMzU3Qzg2QUYxMUU1OEM4OENCQkI2QTc0MTkwRSIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChNYWNpbnRvc2gpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MDEwNzlDODNCQThDMTFFMjg5NTlFMDAzODgzMjZDMkIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MDEwNzlDODRCQThDMTFFMjg5NTlFMDAzODgzMjZDMkIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAAGBAQEBQQGBQUGCQYFBgkLCAYGCAsMCgoL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mhNky5fTkBYwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP/9k=';    
                $variantimg = sprintf($prodImage,$src);
            }

            $tableData[] = array(
                'var_id' => $variant->var_id,
                'var_main_variant' => $mainVariant,
                'var_image' => $variantimg,
                'var_status' => $variantStatus,
                'var_sku' => $sku,
                'var_attributes' => $attributes,
                'DT_RowId' => $variant->var_id,
                'DT_RowAttr' => array('var_status' => $variant->var_status),
            );
            $ctr++;      
        }
        
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $ctr,
            'recordsFiltered' => count($total),
            'data' => $tableData,
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
            ' ' => array(
            ),
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
        
        if($this->getRequest()->isPost()) {
            $variantId = $this->getRequest()->getPost('variantId');
            $sContent = '';
            $variant = $variantSvc->getVariantById($variantId);
            
            //set general price
            foreach($variant->getPrices() as $price){
                if($price->price_country_id == 0){                   
                    $data['price'] = $price->price_net;                   
                }
            }
            
            //set general stocks
            foreach($variant->getStocks() as $stock){
                if($stock->stock_country_id == 0){
                    $data['stocks'] = $stock->stock_quantity;
                }
            }      
            
            if(array_filter($data)){
                $data['country']= 'General';
                $rows[] = $data;
            }

            foreach($countryTable->getCountries() as $country){
                $data = array();
                //set country price
                foreach($variant->getPrices() as $price){                   
                    if($price->price_country_id == $country->ctry_id){
                        $data['price'] = $price->price_net;                        
                    }
                }
                
                //set country stocks
                foreach($variant->getStocks() as $stock){
                    if($stock->stock_country_id == $country->ctry_id){
                        $data['stocks'] = $stock->stock_quantity;
                    }
                }
                
                if(array_filter($data)){
                    $data['country']= $this->getTool()->escapeHtml($country->ctry_name);
                    $rows[] = $data;
                }
            }

            // TBODY START
            
            foreach($rows as $row){
                
                $tmp = '';
                $tmp .= $table->getBody();
                $tmp .= $table->openTableRow();
                
                // country
                $tmp .= $table->setRowData($this->getTool()->escapeHtml($row['country']), array('class' => 'center', 'style' => 'width:120px'));
                
                // price
                $price = isset($row['price'])? $row['price']: '';
                $tmp .= $table->setRowData($price, array('class' => 'center', 'style' => 'width:100px'));
                
                // stocks
                $stocks = isset($row['stocks'])? $row['stocks']: '';
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

        if($res){
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
    
            if($this->isWindows()) {
                $sessionLocale = substr($sessionLocale, 0, 2);
            }
    
            setlocale(LC_MONETARY, $sessionLocale);
            $locale = localeconv();
    
            $curSymbol = trim($locale['currency_symbol']);
            $curSymbolInt = trim($locale['int_curr_symbol']);
            $decimalPoint = trim($locale['mon_decimal_point']);
            $thousandSeparator = trim($locale['mon_thousands_sep']);
            $fmt = new \NumberFormatter($sessionLocale, \NumberFormatter::CURRENCY );
            //$value =  money_format('%.2n', $price); // not windows compatible
            $value = $fmt->formatCurrency($price, $curSymbolInt);
    
            if(count($value) === 1) {
                $value = $fmt->formatCurrency($price, $curSymbolInt);
            }
    
            $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value); // replace special characters
            $value = preg_replace('/[a-zA-Z$ ]/', '', $value); // replace $, [space] and alphabets in price
    
            $newVal = $price;
    
            if($decimalPoint) {
                $value = explode($decimalPoint, $value);
                if(is_array($value)) {
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