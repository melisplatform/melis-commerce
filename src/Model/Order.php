<?php

namespace MelisCommerce\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MelisCommerce\Model\Category;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'melis_ecom_order';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ord_id';

    public function status()
    {
        return $this->hasOne(OrderStatus::class, 'osta_id', 'ord_status');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'cli_id', 'ord_client_id');
    }

    public function contact()
    {
        return $this->hasOne(Contact::class, 'cper_id', 'ord_client_person_id');
    }

    public function basket()
    {
        return $this->hasMany(OrderBasket::class, 'obas_order_id', 'ord_id');
    }

    public function products()
    {
        return $this->hasOne(OrderBasket::class, 'obas_order_id', 'ord_id');
    }

    protected $appends = [];

    protected static $countFiltered = false;

    protected static $dataTable = false;

    protected static $langId = null;

    protected static $toolTipTableHelper;
    protected static $toolTipTableColumns;


    public static function setDataTable($dataTable = false)
    {
        self::$dataTable = $dataTable;

        return new static;
    }

    public static function scopeGetToolListOrder(
        $query,
        $statusId = null, 
        $clientId = null, 
        $clientPersonId = null, 
        $couponId = null, 
        $reference = null,  
        $pageStart = 0, 
        $pageLimit = null, 
        $orderBy = 'ord_id', 
        $order = 'DESC',
        $search = null, 
        $startDate = null, 
        $endDate = null
    ) {
        if (!self::getCountFiltered()) {

            // $this->setAppends([
            //     'DT_RowId',
            //     'order_table_checkbox'
            // ]);

            $query->select('melis_ecom_order.*');

            // $query->with([
            //     // 'client' => function ($query) {
            //     //     $query->select('melis_ecom_client.*');
            //     //         // ->where('cli_id', $clientId);
            //     // },
            //     // 'contact',
            //     // 'status',
            //     // 'basket' => fn($query) => $query->select('obas_order_id'),
            //     // 'products' => fn($query) => $query->selectRaw('count(obas_order_id) as cnt')
            // ]);

            $query->addSelect([
                'products' => OrderBasket::selectRaw('COUNT(obas_order_id) as cnt')
                                        ->whereColumn('ord_id', (new OrderBasket)->getTable().'.obas_order_id'),
                'ccomp_name' => ClientCompany::selectRaw('ccomp_name')
                                        ->whereColumn('ord_client_id', (new ClientCompany)->getTable().'.ccomp_client_id'),
                'cper_firstname' => Contact::selectRaw('cper_firstname')
                                        ->whereColumn('ord_client_person_id', (new Contact)->getTable().'.cper_id'),
                'cper_name' => Contact::selectRaw('cper_name')
                                        ->whereColumn('ord_client_person_id', (new Contact)->getTable().'.cper_id'),
                'price' => OrderPayment::selectRaw('FORMAT(SUM(CAST(opay_price_total AS decimal(18,2))), 2) as total')
                                        ->whereColumn('ord_id', (new OrderPayment)->getTable().'.opay_order_id')
            ]);
        } else {
            $query->selectRaw('COUNT(ord_id) As count');
        }

        if (!empty($search)) {

            $query->where('ord_id', 'like', "%$search%")
                ->orWhere('ord_reference', 'like', "%$search%")
                ->orWhereIn('ord_client_id', function($query) use ($search) {
                    $query->select('ccomp_client_id')
                        ->from((new ClientCompany)->getTable())
                        ->whereColumn('ord_client_id', (new ClientCompany)->table.'.ccomp_client_id')
                        ->where('ccomp_name', 'like', "%$search%");
                })
                ->orWhereIn('ord_client_person_id', function($query) use ($search) {
                    $query->select('cper_id')
                        ->from((new Contact)->getTable())
                        ->whereColumn('ord_client_person_id', (new Contact)->table.'.cper_id')
                        ->where('cper_firstname', 'like', "%$search%");
                })
                ->orWhereIn('ord_client_person_id', function($query) use ($search) {
                    $query->select('cper_id')
                        ->from((new Contact)->getTable())
                        ->whereColumn('ord_client_person_id', (new Contact)->table.'.cper_id')
                        ->where('cper_name', 'like', "%$search%");
                });
        }

        if (!is_null($clientId)){
            $query->where('ord_client_id', $clientId);
        }

        if (!is_null($clientPersonId)){
            $query->where('ord_client_person_id', $clientPersonId);
        }
        
        if (!is_null($startDate)){
            $query->whereDate('ord_date_creation', '>=' , $startDate);
        }

        if (!is_null($endDate)){
            $query->whereDate('ord_date_creation', '<=', $endDate);
        }

        if (!is_null($statusId) && $statusId != '') 
            $query->where('ord_status', $statusId);

        if (!self::getCountFiltered()) {

            if (!empty($pageStart) && $pageStart > 0) 
                $query->offset($pageStart);
    
            if ((!is_null($pageLimit) && $pageLimit > 0)) 
                $query->limit($pageLimit);
    
            $query->orderBy($orderBy, $order);
        }

        return $query;
    }

    public function getDTRowIdAttribute()
    {
        return $this->ord_id;
    }

    public function getOrdStatusAttribute($statusId)
    {
        $statusName = OrderStatusTranslation::getOrderTranslation($statusId, self::getLangId()) ?? null;

        if (!is_null($statusName))
            $statusName = $statusName->ostt_status_name;

        $class = ($statusId == 5) ?:'class="updateListStatus"';
        $disabled = ($statusId == 5)  ? 'disabled' : '';
        $status = '<a data-toggle="modal" href="#id_meliscommerce_order_list_modal_container" %s data-orderid="%s">
                        <span %s class="btn order-status-%s">%s</span>
                </a>';

        $status = sprintf($status, $class, $this->ord_id, $disabled, $statusId, $statusName);

        return $status;
    }

    public function getOrderTableCheckboxAttribute()
    {
        $checkBox = '<div class="checkbox checkbox-single margin-none" data-order-id="%s">
                        <label class="checkbox-custom">
                            <i class="fa fa-fw fa-square-o"></i>
                            <input type="checkbox" class="check-product">
                        </label>
                    </div>';

        return sprintf($checkBox, $this->ord_id);
    }

    public function getOrdReferenceAttribute($orderRef)
    {
        if (!self::getCountFiltered())
            return;

        $toolTipTextTag = '<a id="row-%s" class="clientOrderRefToolTipHoverEvent tooltipTable" data-orderId="%s" data-hasqtip="1" aria-describedby="qtip-%s">%s</a>';

        $rowId = uniqid();
        //for the tooltip of the reference order                
        self::getToolTipTableHelper()->setTable('orderBasketTable'.$this->ord_id, 'table-row-'.$rowId.' ' . 'orderBasketTable'.$this->ord_id, 'cursor:pointer;');
        self::getToolTipTableHelper()->setColumns(self::getToolTipTableColumns());
        // Detect if Mobile remove qTipTable
        $useragent=$_SERVER['HTTP_USER_AGENT'];

        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            $orderRef = sprintf($toolTipTextTag, $rowId, $this->ord_id, $rowId, $this->escapeHtml($orderRef));
        else 
            $orderRef = sprintf($toolTipTextTag, $rowId, $this->ord_id, $rowId, $this->escapeHtml($orderRef)) . self::getToolTipTableHelper()->render();

        return $orderRef;
    }

    public function escapeHtml($value)
    {
        if (!empty($value)) {
            $escaper = new \Laminas\Escaper\Escaper('utf-8');
            $value = $escaper->escapeHtml($value);
        }
        
        return $value;
    }

    /**
     * Get the value of countFiltered
     */ 
    public static function getCountFiltered()
    {
        return self::$countFiltered;
    }

    /**
     * Set the value of countFiltered
     *
     * @return  self
     */ 
    public static function setCountFiltered($countFiltered)
    {
        self::$countFiltered = $countFiltered;

        return new static;
    }

    public static function countFiltered()
    {
        self::setCountFiltered(true);

        return new static;
    }

    public function getArrayableAppends()
    {
        if (!self::getCountFiltered())
            $this->append([
                'DT_RowId',
                'order_table_checkbox'
            ]);

        return parent::getArrayableAppends();
    }

    /**
     * Get the value of langId
     */ 
    public static function getLangId()
    {
        return self::$langId;
    }

    /**
     * Set the value of langId
     *
     * @return  self
     */ 
    public static function setLangId($langId)
    {
        self::$langId = $langId;

        return new static;
    }

    /**
     * Get the value of toolTipTableHelper
     */ 
    public static function getToolTipTableHelper()
    {
        return self::$toolTipTableHelper;
    }

    /**
     * Set the value of toolTipTableHelper
     *
     * @return  self
     */ 
    public static function setToolTipTableHelper($toolTipTableHelper, $columns)
    {
        self::$toolTipTableHelper = $toolTipTableHelper;
        self::setToolTipTableColumns($columns);

        return new static;
    }

    /**
     * Get the value of toolTipTableColumns
     */ 
    public static function getToolTipTableColumns()
    {
        return self::$toolTipTableColumns;
    }

    /**
     * Set the value of toolTipTableColumns
     *
     * @return  self
     */ 
    public static function setToolTipTableColumns($toolTipTableColumns)
    {
        self::$toolTipTableColumns = $toolTipTableColumns;
    }
}
